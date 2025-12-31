<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AllowanceTypeService;
use Illuminate\Http\Request;

class AllowanceTypeController extends Controller
{
    protected AllowanceTypeService $allowanceTypeService;

    public function __construct(AllowanceTypeService $allowanceTypeService)
    {
        $this->allowanceTypeService = $allowanceTypeService;
    }

    public function index()
    {
        $title = "Allowance Types";
        return view('admin.allowance_type.index', compact('title'));
    }

    public function search(Request $request)
    {
        $response = $this->allowanceTypeService->search($request);

        return json_encode([
            "success" => true,
            "rows" => $response['results'] ?? [],
            "total" => $response['recordsTotal'] ?? 0,
        ]);
    }

    public function store(Request $request)
    {
        $response = $this->allowanceTypeService->store($request);

        if ($response) {
            return response()->json([
                'success' => true,
                'is_continue' => $request->is_continue == "on" ? 1 : 0,
                'message' => $request->id ? __(config('messages.commons.update_success')) :
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
        $response = $this->allowanceTypeService->destroy($request);

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