<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NationService;
use Illuminate\Http\Request;

class NationController extends Controller
{
    protected NationService $nationService;

    public function __construct(NationService $nationService)
    {
        $this->nationService = $nationService;
    }

    public function index()
    {
        $title = "Nations";
        return view('admin.nation.index', compact('title'));
    }

    public function search(Request $request)
    {
        // $this->verifyAction('nation.view'); // Uncomment if permission check is needed

        $response = $this->nationService->search($request);

        return json_encode([
            "success" => true,
            "rows"    => $response['results'] ?? [],
            "total"   => $response['recordsTotal'] ?? 0,
        ]);
    }

    public function store(Request $request)
    {
        // $this->verifyAction('nation.create'); // Uncomment if permission check is needed

        $response = $this->nationService->store($request);
        if ($response) {
            return response()->json([
                'success'     => true,
                'is_continue' => $request->is_continue == "on" ? 1 : 0,
                'message'     => $request->id ? __(config('messages.commons.update_success')) :
                    __(config('messages.commons.create_success')),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $request->id ? __(config('messages.commons.update_failed')) :
                __(config('messages.commons.create_failed')),
        ]);
    }

    public function destroy(Request $request)
    {
        // $this->verifyAction('nation.delete'); // Uncomment if permission check is needed

        $response = $this->nationService->destroy($request);
        if ($response) {
            return response()->json([
                'success' => true,
                'message' => __(config('messages.commons.delete_success')),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __(config('messages.commons.delete_failed')),
        ]);
    }
}