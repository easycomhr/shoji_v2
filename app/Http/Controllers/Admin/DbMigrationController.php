<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DbMigrationService;
use Illuminate\Http\Request;

class DbMigrationController extends Controller
{
    protected DbMigrationService $service;

    public function __construct(DbMigrationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $title  = 'DB Migration';
        $tables = $this->service->getTables();

        $statuses = $this->service->getStatuses();
        $migrationTables = array_values(array_map(
            fn ($k, $t) => [
                'key'       => $k,
                'label'     => $t['label'],
                'old_table' => $t['old_table'],
                'new_table' => $t['new_table'],
                'status'    => isset($statuses[$k]) ? 'migrated' : '',
                'synced_at' => $statuses[$k] ?? '',
                'result'    => isset($statuses[$k]) ? 'Đã migrate: ' . $statuses[$k] : '',
            ],
            array_keys($tables),
            $tables
        ));

        return view('admin.db_migration.index', compact('title', 'tables', 'migrationTables'));
    }

    public function preview(Request $request)
    {
        $key    = $request->query('table');
        $source = $request->query('source', 'old');

        $rows = $source === 'old'
            ? $this->service->previewOld($key)
            : $this->service->previewNew($key);

        return response()->json([
            'success' => true,
            'rows'    => $rows,
            'total'   => count($rows),
        ]);
    }

    public function sync(Request $request)
    {
        $key    = $request->input('table');
        $result = $this->service->sync($key);

        return response()->json($result);
    }
}
