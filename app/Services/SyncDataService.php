<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class SyncDataService
{
    /**
     * Đồng bộ dữ liệu từ database cũ sang database mới
     *
     * UPDATED: Thêm tính năng truncate (clear data) trước khi sync
     */
    public function syncData($request)
    {
        $originTable = trim($request->origin_table);
        $syncTable = trim($request->sync_table);

        // Parse columns từ string "a,b,c" thành array
        $originColumns = array_map('trim', explode(',', $request->origin_columns));
        $syncColumns = array_map('trim', explode(',', $request->sync_columns));

        // Kiểm tra số lượng columns phải bằng nhau
        if (count($originColumns) !== count($syncColumns)) {
            return [
                'success' => false,
                'message' => 'Số lượng cột Origin và Sync phải bằng nhau'
            ];
        }

        // Biến để track transaction
        $transactionStarted = false;

        try {
            // ========== BƯỚC 1: Check table tồn tại ==========

            try {
                $database = DB::connection('mysql_old')->getDatabaseName();

                // Check origin table exists
                $originExists = DB::connection('mysql_old')
                    ->table('information_schema.tables')
                    ->where('table_schema', $database)
                    ->where('table_name', $originTable)
                    ->exists();

                if (!$originExists) {
                    Log::warning("Origin table not found", [
                        'database' => $database,
                        'table' => $originTable
                    ]);

                    return [
                        'success' => false,
                        'message' => "Origin table '{$originTable}' không tồn tại trong database '{$database}'"
                    ];
                }

                Log::info("Origin table found", [
                    'database' => $database,
                    'table' => $originTable
                ]);

                // Check sync table exists
                if (!Schema::hasTable($syncTable)) {
                    Log::warning("Sync table not found", [
                        'table' => $syncTable
                    ]);

                    return [
                        'success' => false,
                        'message' => "Sync table '{$syncTable}' không tồn tại trong database mới"
                    ];
                }

                Log::info("Sync table found", [
                    'table' => $syncTable
                ]);

            } catch (\Exception $e) {
                Log::error("SyncDataService@syncData - Error checking tables", [
                    'origin_table' => $originTable,
                    'sync_table' => $syncTable,
                    'error' => $e->getMessage()
                ]);

                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối database: ' . $e->getMessage()
                ];
            }

            // ========== BƯỚC 2: CLEAR DATA (TRUNCATE) ==========
            // ✅ Truncate TRƯỚC transaction
            // Vì truncate auto-commit, phải làm trước transaction

            try {
                DB::table($syncTable)->truncate();
                Log::info("Table truncated successfully", [
                    'table' => $syncTable
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to truncate table", [
                    'table' => $syncTable,
                    'error' => $e->getMessage()
                ]);

                // Nếu truncate fail, vẫn tiếp tục (sẽ insert thêm vào data cũ)
                Log::warning("Continuing without truncate - data will be appended");
            }

            // ========== BƯỚC 3: TRANSACTION + INSERT ==========
            // Transaction bắt đầu SAU khi truncate xong

            DB::beginTransaction();
            $transactionStarted = true;

            Log::info("SyncDataService@syncData - Transaction started", [
                'origin_table' => $originTable,
                'sync_table' => $syncTable
            ]);

            // Lấy dữ liệu từ database cũ
            $originData = DB::connection('mysql_old')
                ->table($originTable)
                ->select($originColumns)
                ->get();

            $total = $originData->count();
            $inserted = 0;
            $failed = 0;
            $errors = [];

            Log::info("SyncDataService@syncData - Fetched data", [
                'origin_table' => $originTable,
                'total_rows' => $total
            ]);

            // Loop qua từng record và insert vào database mới
            foreach ($originData as $index => $row) {
                try {
                    // Map data từ origin columns sang sync columns
                    $insertData = [
                        'company_id' => config('constants.COMPANY_ID'),
                    ];
                    foreach ($originColumns as $colIndex => $originCol) {
                        $syncCol = $syncColumns[$colIndex];

                        // Check column exists in row
                        if (!property_exists($row, $originCol)) {
                            throw new \Exception("Column '{$originCol}' không tồn tại trong origin table");
                        }

                        $insertData[$syncCol] = $row->$originCol;
                    }

                    // Thêm timestamp nếu table có columns created_at và updated_at
                    if (Schema::hasColumn($syncTable, 'created_at') && !isset($insertData['created_at'])) {
                        $insertData['created_at'] = now();
                    }
                    if (Schema::hasColumn($syncTable, 'updated_at') && !isset($insertData['updated_at'])) {
                        $insertData['updated_at'] = now();
                    }

                    // Insert vào database mới
                    DB::table($syncTable)->insert($insertData);
                    $inserted++;

                } catch (\Exception $e) {
                    $failed++;
                    $errorMsg = sprintf(
                        "Row %d: %s",
                        $index + 1,
                        $e->getMessage()
                    );
                    $errors[] = $errorMsg;

                    Log::error("SyncDataService@syncData - Failed to insert row", [
                        'row' => $index + 1,
                        'origin_table' => $originTable,
                        'sync_table' => $syncTable,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Commit transaction
            if ($transactionStarted) {
                DB::commit();
                $transactionStarted = false;

                Log::info("SyncDataService@syncData - Transaction committed", [
                    'origin_table' => $originTable,
                    'sync_table' => $syncTable,
                    'total' => $total,
                    'inserted' => $inserted,
                    'failed' => $failed
                ]);
            }

            return [
                'success' => true,
                'total' => $total,
                'inserted' => $inserted,
                'failed' => $failed,
                'errors' => $errors
            ];

        } catch (\Exception $e) {
            // Chỉ rollback nếu transaction đã được start
            if ($transactionStarted) {
                try {
                    DB::rollBack();
                    Log::info("SyncDataService@syncData - Transaction rolled back");
                } catch (\Exception $rollbackError) {
                    Log::error("SyncDataService@syncData - Error rolling back transaction", [
                        'error' => $rollbackError->getMessage()
                    ]);
                }
            }

            Log::error("SyncDataService@syncData - Exception caught", [
                'origin_table' => $originTable,
                'sync_table' => $syncTable,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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
            $database = DB::connection($connection)->getDatabaseName();

            $tables = DB::connection($connection)
                ->table('information_schema.tables')
                ->where('table_schema', $database)
                ->pluck('table_name')
                ->toArray();

            return $tables;

        } catch (\Exception $e) {
            Log::error("SyncDataService@getTables", [
                'type' => $type,
                'error' => $e->getMessage()
            ]);
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
            $database = DB::connection($connection)->getDatabaseName();

            $columns = DB::connection($connection)
                ->table('information_schema.columns')
                ->where('table_schema', $database)
                ->where('table_name', $table)
                ->pluck('column_name')
                ->toArray();

            return $columns;

        } catch (\Exception $e) {
            Log::error("SyncDataService@getColumns", [
                'table' => $table,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Truncate table trước khi sync (tùy chọn)
     *
     * DEPRECATED: Chức năng truncate đã được tích hợp vào syncData()
     */
    public function truncateTable($table)
    {
        try {
            DB::table($table)->truncate();
            Log::info("Table truncated", ['table' => $table]);
            return true;
        } catch (\Exception $e) {
            Log::error("SyncDataService@truncateTable", [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Import file Excel và sync data
     */
    public function importAndSync($file)
    {
        try {
            Log::info("SyncDataService@importAndSync - Start", [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize()
            ]);

            // Load file Excel
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            $totalTables = 0;
            $successTables = 0;
            $failedTables = 0;
            $totalRecords = 0;
            $insertedRecords = 0;
            $failedRecords = 0;
            $details = [];

            // Bắt đầu từ row 2 (row 1 là header)
            for ($row = 2; $row <= $highestRow; $row++) {
                // Đọc dữ liệu từ các cột
                $stt = $sheet->getCell('A' . $row)->getValue();
                $originTable = trim($sheet->getCell('B' . $row)->getValue());
                $originColumns = trim($sheet->getCell('C' . $row)->getValue());
                $syncTable = trim($sheet->getCell('D' . $row)->getValue());
                $syncColumns = trim($sheet->getCell('E' . $row)->getValue());

                // Skip nếu row trống
                if (empty($originTable) || empty($syncTable)) {
                    Log::info("SyncDataService@importAndSync - Skipping empty row", ['row' => $row]);
                    continue;
                }

                $totalTables++;

                Log::info("SyncDataService@importAndSync - Processing table", [
                    'row' => $row,
                    'origin_table' => $originTable,
                    'sync_table' => $syncTable
                ]);

                // Tạo request object để sync
                $request = new \Illuminate\Http\Request([
                    'origin_table' => $originTable,
                    'sync_table' => $syncTable,
                    'origin_columns' => $originColumns,
                    'sync_columns' => $syncColumns,
                ]);

                // Thực hiện sync (sẽ tự động truncate trước khi sync)
                $result = $this->syncData($request);

                if ($result['success']) {
                    $successTables++;
                    $totalRecords += $result['total'];
                    $insertedRecords += $result['inserted'];
                    $failedRecords += $result['failed'];

                    $details[] = [
                        'row' => $row,
                        'stt' => $stt,
                        'origin_table' => $originTable,
                        'sync_table' => $syncTable,
                        'status' => 'success',
                        'total' => $result['total'],
                        'inserted' => $result['inserted'],
                        'failed' => $result['failed'],
                        'message' => 'Đồng bộ thành công'
                    ];
                } else {
                    $failedTables++;
                    $details[] = [
                        'row' => $row,
                        'stt' => $stt,
                        'origin_table' => $originTable,
                        'sync_table' => $syncTable,
                        'status' => 'failed',
                        'message' => $result['message'] ?? 'Lỗi không xác định'
                    ];

                    Log::warning("SyncDataService@importAndSync - Table sync failed", [
                        'row' => $row,
                        'origin_table' => $originTable,
                        'sync_table' => $syncTable,
                        'message' => $result['message'] ?? 'Unknown error'
                    ]);
                }
            }

            Log::info("SyncDataService@importAndSync - Completed", [
                'total_tables' => $totalTables,
                'success_tables' => $successTables,
                'failed_tables' => $failedTables,
                'total_records' => $totalRecords,
                'inserted_records' => $insertedRecords,
                'failed_records' => $failedRecords
            ]);

            return [
                'success' => true,
                'total_tables' => $totalTables,
                'success_tables' => $successTables,
                'failed_tables' => $failedTables,
                'total_records' => $totalRecords,
                'inserted_records' => $insertedRecords,
                'failed_records' => $failedRecords,
                'details' => $details
            ];

        } catch (\Exception $e) {
            Log::error("SyncDataService@importAndSync - Exception", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Tạo file template Excel
     */
    public function generateTemplate()
    {
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $sheet->setCellValue('A1', 'STT');
            $sheet->setCellValue('B1', 'Origin Table');
            $sheet->setCellValue('C1', 'Origin Columns');
            $sheet->setCellValue('D1', 'Sync Table');
            $sheet->setCellValue('E1', 'Sync Columns');

            // Style header
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(25);
            $sheet->getColumnDimension('E')->setWidth(35);

            // Add example data
            $sheet->setCellValue('A2', '1');
            $sheet->setCellValue('B2', 'tblskills');
            $sheet->setCellValue('C2', 'skill,allowance');
            $sheet->setCellValue('D2', 'skills');
            $sheet->setCellValue('E2', 'name,allowance');

            // Add notes
            $sheet->setCellValue('A5', 'Lưu ý:');
            $sheet->setCellValue('A6', '- Dữ liệu bắt đầu từ row 2 (row 1 là header)');
            $sheet->setCellValue('A7', '- Các cột phân cách bằng dấu phẩy (,)');
            $sheet->setCellValue('A8', '- Số lượng cột Origin Columns và Sync Columns phải bằng nhau');
            $sheet->setCellValue('A9', '- Table sẽ được TRUNCATE (xóa hết data cũ) trước khi sync');

            // Style notes
            $sheet->getStyle('A5')->getFont()->setBold(true);

            // Save file
            $fileName = 'sync_data_template_' . time() . '.xlsx';
            $filePath = storage_path('app/public/' . $fileName);

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists(storage_path('app/public'))) {
                mkdir(storage_path('app/public'), 0755, true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);

            return $filePath;

        } catch (\Exception $e) {
            Log::error("SyncDataService@generateTemplate", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}