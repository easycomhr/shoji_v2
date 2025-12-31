<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LeaveGroupService;
use Illuminate\Http\Request;

class LeaveGroupController extends Controller
{
    protected LeaveGroupService $leaveGroupService;

    public function __construct(LeaveGroupService $leaveGroupService)
    {
        $this->leaveGroupService = $leaveGroupService;
    }

    public function index()
    {
        $title = "Leave Groups";
        return view('admin.leave_group.index', compact('title'));
    }

    public function search(Request $request)
    {
        $response = $this->leaveGroupService->search($request);

        return json_encode([
            "success" => true,
            "rows"    => $response['results'] ?? [],
            "total"   => $response['recordsTotal'] ?? 0,
        ]);
    }

    public function store(Request $request)
    {
        $response = $this->leaveGroupService->store($request);
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
        $response = $this->leaveGroupService->destroy($request);
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