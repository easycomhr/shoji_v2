<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncDataServiceAdvanced
{
    /**
     * Đồng bộ dữ liệu từ database cũ sang database mới (Version nâng cao)
     */
    public function syncData($request)
    {
        $originTable = trim($request->origin_table);
        $syncTable = trim($request->sync_table);

        // Parse columns từ string "a,b,c" thành array
        $originColumns = array_map('trim', explode(',', $request->origin_columns));
        $syncColumns = array_map('trim', explode(',', $request->sync_columns));

        // Options
        $truncate = $request->truncate ?? false; // Xóa dữ liệu cũ trước khi sync
        $batchSize = $request->batch_size ?? 100; // Số lượng records insert 1 lần
        $whereCondition = $request->where_condition ?? null; // Điều kiện where (optional)

        // Kiểm tra số lượng columns phải bằng nhau
        if (count($originColumns) !== count($syncColumns)) {
            return [
                'success' => false,
                'message' => 'Số lượng cột Origin và Sync phải bằng nhau'
            ];
        }

        DB::beginTransaction();
        try {
            // Truncate table nếu option được bật
            if ($truncate) {
                DB::table($syncTable)->truncate();
            }

            // Lấy dữ liệu từ database cũ
            $query = DB::connection('mysql_old')
                ->table($originTable)
                ->select($originColumns);

            // Thêm điều kiện where nếu có
            if ($whereCondition) {
                // Parse where condition: "status=1 AND active=1"
                $query->whereRaw($whereCondition);
            }

            // Sử dụng chunk để xử lý từng batch
            $total = 0;
            $inserted = 0;
            $failed = 0;

            $query->chunk($batchSize, function ($originData) use (
                $originColumns,
                $syncColumns,
                $syncTable,
                &$total,
                &$inserted,
                &$failed
            ) {
                $batchData = [];

                foreach ($originData as $row) {
                    $total++;

                    try {
                        // Map data từ origin columns sang sync columns
                        $insertData = [];
                        foreach ($originColumns as $index => $originCol) {
                            $syncCol = $syncColumns[$index];
                            $insertData[$syncCol] = $row->$originCol;
                        }

                        // Thêm timestamp nếu table có columns created_at và updated_at
                        if (Schema::hasColumn($syncTable, 'created_at') && !isset($insertData['created_at'])) {
                            $insertData['created_at'] = now();
                        }
                        if (Schema::hasColumn($syncTable, 'updated_at') && !isset($insertData['updated_at'])) {
                            $insertData['updated_at'] = now();
                        }

                        $batchData[] = $insertData;

                    } catch (\Exception $e) {
                        $failed++;
                        logger()->error(sprintf(
                            "SyncDataService@syncData - Failed to prepare row: %s",
                            $e->getMessage()
                        ));
                    }
                }

                // Batch insert
                if (!empty($batchData)) {
                    try {
                        DB::table($syncTable)->insert($batchData);
                        $inserted += count($batchData);
                    } catch (\Exception $e) {
                        $failed += count($batchData);
                        logger()->error(sprintf(
                            "SyncDataService@syncData - Batch insert failed: %s",
                            $e->getMessage()
                        ));
                    }
                }
            });

            DB::commit();

            return [
                'success' => true,
                'total' => $total,
                'inserted' => $inserted,
                'failed' => $failed
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("SyncDataService@syncData %s", $e->getMessage()));

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Đồng bộ với transform data
     */
    public function syncDataWithTransform($request, callable $transformer = null)
    {
        $originTable = trim($request->origin_table);
        $syncTable = trim($request->sync_table);

        $originColumns = array_map('trim', explode(',', $request->origin_columns));
        $syncColumns = array_map('trim', explode(',', $request->sync_columns));

        if (count($originColumns) !== count($syncColumns)) {
            return [
                'success' => false,
                'message' => 'Số lượng cột Origin và Sync phải bằng nhau'
            ];
        }

        DB::beginTransaction();
        try {
            $originData = DB::connection('mysql_old')
                ->table($originTable)
                ->select($originColumns)
                ->get();

            $total = $originData->count();
            $inserted = 0;
            $failed = 0;

            foreach ($originData as $row) {
                try {
                    $insertData = [];
                    foreach ($originColumns as $index => $originCol) {
                        $syncCol = $syncColumns[$index];
                        $value = $row->$originCol;

                        // Transform data nếu có transformer
                        if ($transformer) {
                            $value = $transformer($syncCol, $value, $row);
                        }

                        $insertData[$syncCol] = $value;
                    }

                    if (Schema::hasColumn($syncTable, 'created_at') && !isset($insertData['created_at'])) {
                        $insertData['created_at'] = now();
                    }
                    if (Schema::hasColumn($syncTable, 'updated_at') && !isset($insertData['updated_at'])) {
                        $insertData['updated_at'] = now();
                    }

                    DB::table($syncTable)->insert($insertData);
                    $inserted++;

                } catch (\Exception $e) {
                    $failed++;
                    logger()->error(sprintf(
                        "SyncDataService@syncDataWithTransform - Failed: %s",
                        $e->getMessage()
                    ));
                }
            }

            DB::commit();

            return [
                'success' => true,
                'total' => $total,
                'inserted' => $inserted,
                'failed' => $failed
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("SyncDataService@syncDataWithTransform %s", $e->getMessage()));

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách tables từ database
     */
    public function getTables($type = 'origin')
    {
        $connection = $type === 'origin' ? 'mysql_old' : 'mysql';

        try {
            $tables = DB::connection($connection)
                ->select('SHOW TABLES');

            $database = DB::connection($connection)->getDatabaseName();
            $key = "Tables_in_{$database}";

            return array_map(function($table) use ($key) {
                return $table->$key;
            }, $tables);

        } catch (\Exception $e) {
            logger()->error(sprintf("SyncDataService@getTables %s", $e->getMessage()));
            throw $e;
        }
    }

    /**
     * Lấy danh sách columns của table
     */
    public function getColumns($table, $type = 'origin')
    {
        $connection = $type === 'origin' ? 'mysql_old' : 'mysql';

        try {
            $columns = DB::connection($connection)
                ->select("SHOW COLUMNS FROM {$table}");

            return array_map(function($column) {
                return $column->Field;
            }, $columns);

        } catch (\Exception $e) {
            logger()->error(sprintf("SyncDataService@getColumns %s", $e->getMessage()));
            throw $e;
        }
    }

    /**
     * Lấy preview data
     */
    public function previewData($table, $columns, $type = 'origin', $limit = 10)
    {
        $connection = $type === 'origin' ? 'mysql_old' : 'mysql';

        try {
            $columnsArray = array_map('trim', explode(',', $columns));

            return DB::connection($connection)
                ->table($table)
                ->select($columnsArray)
                ->limit($limit)
                ->get();

        } catch (\Exception $e) {
            logger()->error(sprintf("SyncDataService@previewData %s", $e->getMessage()));
            throw $e;
        }
    }
}