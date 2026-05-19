<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChangeEmployeeCodeService extends BaseService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Generate new employee code and update the user record.
     *
     * New code format: YYMMDD-SHORTNAME
     * where SHORTNAME = LastWord + initials of preceding words (uppercase)
     * e.g. "Nguyễn Văn An" on 2026-03-23 → "260323-ANNV"
     */
    public function process(int $userId, string $onDate): array
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->getById($userId);
            if (!$user) {
                return ['success' => false, 'message' => 'Nhân viên không tồn tại.'];
            }

            $newCode = $this->generateCode($user->name, $onDate);

            // Check if the new code already belongs to another employee
            $duplicate = DB::table('users')
                ->where('code', $newCode)
                ->where('id', '!=', $userId)
                ->exists();

            if ($duplicate) {
                return ['success' => false, 'message' => "Mã mới '{$newCode}' đã được sử dụng bởi nhân viên khác."];
            }

            $this->userRepository->updateById($userId, [
                'code'            => $newCode,
                'join_date'       => $onDate,
                'probation_start' => $onDate,
            ]);

            DB::commit();

            return ['success' => true, 'new_code' => $newCode];
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf('ChangeEmployeeCodeService@process %s', $e->getMessage()));

            return ['success' => false, 'message' => 'Đã có lỗi xảy ra, vui lòng thử lại.'];
        }
    }

    /**
     * Build code: YYMMDD-<LAST_WORD><INITIALS_OF_REST> all uppercase.
     */
    private function generateCode(string $name, string $date): string
    {
        // Strip Vietnamese diacritics
        $ascii = Str::ascii($name, 'vi');
        $parts = preg_split('/\s+/', trim($ascii));
        $parts = array_filter($parts);
        $parts = array_values($parts);

        $last = end($parts);
        $shortName = $last;
        for ($i = 0; $i < count($parts) - 1; $i++) {
            $shortName .= strtoupper(substr($parts[$i], 0, 1));
        }

        $shortDate = date('dmy', strtotime($date));

        return $shortDate . '-' . strtoupper($shortName);
    }
}
