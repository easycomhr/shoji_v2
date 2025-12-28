<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SyncDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SyncDataController extends Controller
{
    protected $syncDataService;

    public function __construct(SyncDataService $syncDataService)
    {
        $this->syncDataService = $syncDataService;
    }

    /**
     * Hiển thị trang sync data
     */
    public function index()
    {
        return view('admin.sync_data.index');
    }

    /**
     * Import file Excel và sync nhiều tables
     */
    public function import(Request $request)
    {
        // Validate request
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('import_file');

            Log::info("SyncDataController@import - Start", [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize()
            ]);

            // Import và sync
            $result = $this->syncDataService->importAndSync($file);

            if ($result['success']) {
                Log::info("SyncDataController@import - Success", [
                    'total_tables' => $result['total_tables'],
                    'success_tables' => $result['success_tables'],
                    'failed_tables' => $result['failed_tables']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Import và đồng bộ hoàn tất',
                    'data' => $result
                ]);
            } else {
                Log::error("SyncDataController@import - Failed", [
                    'message' => $result['message']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error("SyncDataController@import - Exception", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi import: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync manual - đồng bộ từng table một
     *
     * NEW: Endpoint để sync nhập tay
     */
    public function syncManual(Request $request)
    {
        // Validate request
        $request->validate([
            'origin_table' => 'required|string',
            'origin_columns' => 'required|string',
            'sync_table' => 'required|string',
            'sync_columns' => 'required|string',
        ]);

        try {
            Log::info("SyncDataController@syncManual - Start", [
                'origin_table' => $request->origin_table,
                'sync_table' => $request->sync_table
            ]);

            // Sync data
            $result = $this->syncDataService->syncData($request);

            if ($result['success']) {
                Log::info("SyncDataController@syncManual - Success", [
                    'origin_table' => $request->origin_table,
                    'sync_table' => $request->sync_table,
                    'total' => $result['total'],
                    'inserted' => $result['inserted'],
                    'failed' => $result['failed']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đồng bộ thành công',
                    'data' => $result
                ]);
            } else {
                Log::error("SyncDataController@syncManual - Failed", [
                    'message' => $result['message']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error("SyncDataController@syncManual - Exception", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi sync: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tải template Excel
     */
    public function downloadTemplate()
    {
        try {
            $filePath = $this->syncDataService->generateTemplate();

            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error("SyncDataController@downloadTemplate - Exception", [
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Không thể tạo template: ' . $e->getMessage());
        }
    }
}