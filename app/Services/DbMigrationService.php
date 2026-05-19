<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DbMigrationService
{
    public function getTables(): array
    {
        return config('db_migration.tables', []);
    }

    public function getTableConfig(string $key): ?array
    {
        return config("db_migration.tables.{$key}");
    }

    public function previewOld(string $key, int $limit = 50): array
    {
        $cfg = $this->getTableConfig($key);
        if (!$cfg) return [];

        $rows = DB::connection('mysql_old')->table($cfg['old_table'])->limit($limit)->get();

        return $rows->toArray();
    }

    public function previewNew(string $key, int $limit = 50): array
    {
        $cfg = $this->getTableConfig($key);
        if (!$cfg) return [];

        $rows = DB::table($cfg['new_table'])->limit($limit)->get();

        return $rows->toArray();
    }

    /**
     * Dispatcher — routes each table key to its dedicated private sync method.
     */
    public function sync(string $key): array
    {
        return match($key) {
            'users'             => $this->syncUsers(),
            'user_insurances'   => $this->syncUserInsurances(),
            'labour_contracts'  => $this->syncLabourContracts(),
            default             => [
                'success'  => false,
                'message'  => "Không tìm thấy handler cho: {$key}",
                'upserted' => 0,
                'skipped'  => 0,
                'errors'   => [],
            ],
        };
    }

    /**
     * Sync tblusers → users
     *
     * Column mapping (old → new):
     *   userid                    → code          (upsert key)
     *   realname                  → name
     *   nickname                  → nickname
     *   nationality               → nationality
     *   religion                  → religion
     *   married                   → is_married
     *   sex                       → gender
     *   birthdate                 → birthday
     *   birthplace                → birth_place
     *   idnumber                  → id_card
     *   idissuedate               → id_card_issue_date
     *   idissueplace              → id_card_issue_place
     *   passportno                → passport
     *   passportissuedate         → passport_issue_date
     *   passportexprieddate       → passport_expiry_date
     *   passportissueplace        → passport_issue_place
     *   employeecardid            → timekeeper_card_id
     *   employeecode              → employee_code
     *   homeaddress               → home_address
     *   tempaddress               → temporary_address
     *   extension                 → extension
     *   mobilephone               → phone
     *   homephone                 → home_phone
     *   office_phone              → office_phone
     *   companyemail              → company_email
     *   privateemail              → private_email
     *   joindate                  → join_date
     *   probationstart            → probation_start
     *   probationend              → probation_end
     *   permanent_date            → seniority_date
     *   terminatedate             → termination_date
     *   terminatedatereg          → termination_date_registered
     *   userstatusid              → user_status_id
     *   statusfromdate            → status_from_date
     *   bankaccountnumber         → bank_account_number
     *   bank                      → bank_name
     *   bankbranch                → bank_branch
     *   insurancenumber           → insurance_number
     *   healthcareinsurancenumber → health_insurance_number
     *   sidate                    → social_insurance_date
     *   siplace                   → social_insurance_place
     *   taxcode                   → tax_code
     *   acc_code                  → acc_code
     *   lastvisitdate             → last_visit_date
     *   blocked                   → is_blocked
     *   notes                     → notes
     *   is_foreigner              → is_foreigner
     *   is_office                 → is_office
     *   is_lunch_allow            → is_lunch_allow
     *   menusystem                → menu_system
     *   flag                      → flag
     *   countryid                 → country_id
     *
     * Special:
     *   - email: companyemail → privateemail → {code}@easycom.local (fallback)
     *   - password: bcrypt('Easycom@2024') pre-hashed once to avoid per-row timeout
     *   - 0000-00-00 date values converted to null
     */
    private function syncUsers(): array
    {
        $key = 'users';
        $cfg = $this->getTableConfig($key);
        if (!$cfg) {
            return ['success' => false, 'message' => 'Config not found', 'upserted' => 0, 'skipped' => 0, 'errors' => []];
        }

        set_time_limit(0); // large tables can take > 30s

        $oldRows  = DB::connection('mysql_old')->table($cfg['old_table'])->get();
        $upserted = 0;
        $skipped  = 0;
        $errors   = [];

        $emailFromFields = $cfg['email_from'] ?? [];

        // Pre-hash once — bcrypt is intentionally slow; calling it per-row causes timeout
        $defaultPassword = bcrypt('Easycom@2024');

        DB::beginTransaction();
        try {
            foreach ($oldRows as $oldRow) {
                $mapped = [];

                foreach ($cfg['column_map'] as $oldCol => $newCol) {
                    $val = $oldRow->$oldCol ?? null;
                    // Convert MySQL zero-date to null
                    if (is_string($val) && preg_match('/^0000-00-00/', $val)) {
                        $val = null;
                    }
                    $mapped[$newCol] = $val;
                }

                // Derive email: companyemail → privateemail → {code}@easycom.local
                $email = null;
                foreach ($emailFromFields as $f) {
                    if (!empty($oldRow->$f)) {
                        $email = $oldRow->$f;
                        break;
                    }
                }
                $mapped['email'] = $email ?? ($mapped['code'] . '@easycom.local');

                // Set default password (pre-hashed above to avoid per-row bcrypt timeout)
                if (empty($mapped['password'])) {
                    $mapped['password'] = $defaultPassword;
                }

                $mapped['updated_at'] = now();

                $upsertKey = $cfg['upsert_key'];
                $existing  = DB::table($cfg['new_table'])->where($upsertKey, $mapped[$upsertKey])->first();

                if ($existing) {
                    DB::table($cfg['new_table'])->where($upsertKey, $mapped[$upsertKey])->update($mapped);
                } else {
                    $mapped['created_at'] = now();
                    DB::table($cfg['new_table'])->insert($mapped);
                }

                $upserted++;
            }

            DB::commit();

            \App\Models\MigrationLog::updateOrCreate(
                ['table_key' => $key],
                ['synced_at' => now(), 'upserted' => $upserted, 'skipped' => $skipped]
            );

            return [
                'success'  => true,
                'upserted' => $upserted,
                'skipped'  => $skipped,
                'errors'   => $errors,
                'message'  => "Sync hoàn tất: {$upserted} bản ghi. Bỏ qua: {$skipped}.",
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['success' => false, 'message' => $e->getMessage(), 'upserted' => 0, 'skipped' => 0, 'errors' => []];
        }
    }

    /**
     * Sync tbluserinsurances → user_insurances
     *
     * Column mapping (old → new):
     *   userid                    → user_id       (resolved via users.code lookup — skips row if not found)
     *   insurancenumber           → social_insurance_number
     *   sidate                    → social_insurance_start_date
     *   end_date                  → social_insurance_end_date
     *   si_place                  → social_insurance_place
     *   healthcareinsurancenumber → health_insurance_number
     *   healthplace               → health_insurance_place
     *   is_locked                 → is_locked
     *
     * Special:
     *   - user_id resolved by matching tbluserinsurances.userid against users.code
     *   - Rows where userid has no match in users are skipped (logged in errors)
     *   - 0000-00-00 date values converted to null
     */
    private function syncUserInsurances(): array
    {
        $key = 'user_insurances';
        $cfg = $this->getTableConfig($key);
        if (!$cfg) {
            return ['success' => false, 'message' => 'Config not found', 'upserted' => 0, 'skipped' => 0, 'errors' => []];
        }

        set_time_limit(0); // large tables can take > 30s

        $oldRows  = DB::connection('mysql_old')->table($cfg['old_table'])->get();
        $upserted = 0;
        $skipped  = 0;
        $errors   = [];

        // Pre-build userid → users.id lookup map (tbluserinsurances.userid = users.code)
        $lookupField = $cfg['user_id_lookup']['lookup_field']; // 'code'
        $userIdMap   = [];
        DB::table('users')
            ->whereNotNull($lookupField)
            ->select('id', $lookupField)
            ->get()
            ->each(function ($u) use ($lookupField, &$userIdMap) {
                $userIdMap[$u->$lookupField] = $u->id;
            });

        DB::beginTransaction();
        try {
            foreach ($oldRows as $oldRow) {
                $mapped = [];

                // Resolve user_id via users.code lookup
                $oldField = $cfg['user_id_lookup']['old_field']; // 'userid'
                $oldCode  = $oldRow->$oldField ?? null;
                $userId   = $userIdMap[$oldCode] ?? null;

                if (!$userId) {
                    $errors[] = "Không tìm thấy user cho userid: {$oldCode}";
                    $skipped++;
                    continue;
                }

                $mapped['user_id']       = $userId;
                $mapped['employee_code'] = $oldCode;

                foreach ($cfg['column_map'] as $oldCol => $newCol) {
                    $val = $oldRow->$oldCol ?? null;
                    // Convert MySQL zero-date to null
                    if (is_string($val) && preg_match('/^0000-00-00/', $val)) {
                        $val = null;
                    }
                    $mapped[$newCol] = $val;
                }

                // is_locked is NOT NULL in new table; coerce null → 0
                $mapped['is_locked'] = (int) ($mapped['is_locked'] ?? 0);

                $mapped['updated_at'] = now();

                $upsertKey = $cfg['upsert_key']; // 'user_id'
                $existing  = DB::table($cfg['new_table'])->where($upsertKey, $mapped[$upsertKey])->first();

                if ($existing) {
                    DB::table($cfg['new_table'])->where($upsertKey, $mapped[$upsertKey])->update($mapped);
                } else {
                    $mapped['created_at'] = now();
                    DB::table($cfg['new_table'])->insert($mapped);
                }

                $upserted++;
            }

            DB::commit();

            \App\Models\MigrationLog::updateOrCreate(
                ['table_key' => $key],
                ['synced_at' => now(), 'upserted' => $upserted, 'skipped' => $skipped]
            );

            return [
                'success'  => true,
                'upserted' => $upserted,
                'skipped'  => $skipped,
                'errors'   => $errors,
                'message'  => "Sync hoàn tất: {$upserted} bản ghi. Bỏ qua: {$skipped}.",
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['success' => false, 'message' => $e->getMessage(), 'upserted' => 0, 'skipped' => 0, 'errors' => []];
        }
    }

    /**
     * Sync tbllabourcontracts → labour_contracts
     *
     * Column mapping (old → new):
     *   userid           → user_id          (resolved via users.code lookup — skips row if not found)
     *   contracttypeid   → contract_type_id
     *   contractno       → contract_number
     *   contractstartday → start_date
     *   contractendday   → end_date
     *   comment          → notes
     *
     * Special:
     *   - user_id resolved by matching tbllabourcontracts.userid against users.code
     *   - Rows where userid has no match in users are skipped (logged in errors)
     *   - 0000-00-00 date values converted to null for start_date and end_date
     *   - Defaults: signed_date = null, status = 'active'
     *   - Composite upsert key: (user_id, start_date)
     */
    private function syncLabourContracts(): array
    {
        $key = 'labour_contracts';
        $cfg = $this->getTableConfig($key);
        if (!$cfg) {
            return ['success' => false, 'message' => 'Config not found', 'upserted' => 0, 'skipped' => 0, 'errors' => []];
        }

        set_time_limit(0);

        $oldRows  = DB::connection('mysql_old')->table($cfg['old_table'])->get();
        $upserted = 0;
        $skipped  = 0;
        $errors   = [];

        // Pre-build userid → users.id lookup map
        $lookupField = $cfg['user_id_lookup']['lookup_field']; // 'code'
        $userIdMap   = [];
        DB::table('users')
            ->whereNotNull($lookupField)
            ->select('id', $lookupField)
            ->get()
            ->each(function ($u) use ($lookupField, &$userIdMap) {
                $userIdMap[$u->$lookupField] = $u->id;
            });

        DB::beginTransaction();
        try {
            foreach ($oldRows as $oldRow) {
                $mapped = [];

                // Resolve user_id via users.code lookup
                $oldField = $cfg['user_id_lookup']['old_field']; // 'userid'
                $oldCode  = $oldRow->$oldField ?? null;
                $userId   = $userIdMap[$oldCode] ?? null;

                if (!$userId) {
                    $errors[] = "Không tìm thấy user cho userid: {$oldCode}";
                    $skipped++;
                    continue;
                }

                $mapped['user_id'] = $userId;

                foreach ($cfg['column_map'] as $oldCol => $newCol) {
                    $val = $oldRow->$oldCol ?? null;
                    // Convert MySQL zero-date to null
                    if (is_string($val) && preg_match('/^0000-00-00/', $val)) {
                        $val = null;
                    }
                    $mapped[$newCol] = $val;
                }

                // Set defaults
                $mapped['signed_date'] = null;
                $mapped['status']      = 'active';
                $mapped['updated_at']  = now();

                // Composite upsert: (user_id, start_date)
                $existing = DB::table($cfg['new_table'])
                    ->where('user_id', $userId)
                    ->where('start_date', $mapped['start_date'])
                    ->first();

                if ($existing) {
                    DB::table($cfg['new_table'])
                        ->where('user_id', $userId)
                        ->where('start_date', $mapped['start_date'])
                        ->update($mapped);
                } else {
                    $mapped['created_at'] = now();
                    DB::table($cfg['new_table'])->insert($mapped);
                }

                $upserted++;
            }

            DB::commit();

            \App\Models\MigrationLog::updateOrCreate(
                ['table_key' => $key],
                ['synced_at' => now(), 'upserted' => $upserted, 'skipped' => $skipped]
            );

            return [
                'success'  => true,
                'upserted' => $upserted,
                'skipped'  => $skipped,
                'errors'   => $errors,
                'message'  => "Sync hoàn tất: {$upserted} bản ghi. Bỏ qua: {$skipped}.",
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['success' => false, 'message' => $e->getMessage(), 'upserted' => 0, 'skipped' => 0, 'errors' => []];
        }
    }

    public function getStatuses(): array
    {
        return \App\Models\MigrationLog::all()
            ->keyBy('table_key')
            ->map(fn($log) => $log->synced_at?->format('d/m/Y H:i'))
            ->toArray();
    }
}
