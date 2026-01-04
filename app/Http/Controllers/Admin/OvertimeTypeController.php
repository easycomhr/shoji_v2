<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OvertimeTypeService;
use Illuminate\Http\Request;

class OvertimeTypeController extends Controller
{
    protected OvertimeTypeService $overtimeTypeService;

    public function __construct(OvertimeTypeService $overtimeTypeService)
    {
        $this->overtimeTypeService = $overtimeTypeService;
    }

    public function index()
    {
        $title = "Overtime Types";

        return view('admin.overtime_type.index', compact('title'));
    }

    public function search(Request $request)
    {
        $response = $this->overtimeTypeService->search($request);

        return json_encode([
            "success" => true,
            "rows"    => $response['results'] ?? [],
            "total"   => $response['recordsTotal'] ?? 0,
        ]);
    }

    function store(Request $request)
    {
        $response = $this->overtimeTypeService->store($request);

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

    function destroy(Request $request)
    {
        $response = $this->overtimeTypeService->destroy($request);

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