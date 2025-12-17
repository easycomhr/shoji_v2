<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\WorkShiftRepository;
use App\Services\BaseService;
use App\Services\UserLeaveService;
use App\Services\WorkShiftService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    protected WorkShiftService $workShiftService;
    protected UserLeaveService $userLeaveService;

    public function __construct(
        WorkShiftService $workShiftService,
        UserLeaveService $userLeaveService,
    )
    {
        $this->workShiftService = $workShiftService;
        $this->userLeaveService = $userLeaveService;
    }

    public function index(){
        $title = "Management Leaves";

        return view('admin.leave.index', compact('title'));
    }

    public function search(Request $request)
    {

        $action = $request->action ?? config('constant.actions.view');


        $response = $this->userLeaveService->search($request);

        return json_encode([
            "success"   => true,
            "rows"      => $response['results'] ?? [],
            "total"     => $response['recordsTotal'] ?? 0,
        ]);
    }

    function store(Request $request){

        $action = $request->action ?? config('constant.actions.insert');
        $isAllow = BaseService::verifyAction($request, $action);

        if(!$isAllow){
            return json_encode([
                'success' => false,
                'message' => __(config('constant.messages.errors.not_enough_permission'))
            ]);
        }

        $response = $this->workShiftService->store($request);
        if($response){

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

    function destroy(Request $request){

        $action = $request->action ?? config('constant.actions.insert');
        $isAllow = BaseService::verifyAction($request, $action);

        if(!$isAllow){
            return json_encode([
                'success' => false,
                'message' => __(config('constant.messages.errors.not_enough_permission'))
            ]);
        }
        $response = $this->workShiftService->destroy($request);
        if($response){

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

    public function import(Request $request){
        $title = __("Import Leave");

        return view('admin.leave.import', compact('title'));
    }

    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $response = $this->userLeaveService->importExcel($request);

        if ($response['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Import data successfully',
                'imported_count' => $response['count'] // Nếu $response là array
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Import data failed！'
        ], 422);
    }
}
