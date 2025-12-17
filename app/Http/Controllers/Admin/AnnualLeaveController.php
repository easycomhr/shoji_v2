<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\WorkShiftRepository;
use App\Services\AnnualLeaveService;
use App\Services\BaseService;
use App\Services\WorkShiftService;
use Illuminate\Http\Request;

class AnnualLeaveController extends Controller
{
    protected AnnualLeaveService  $annualLeaveService;

    public function __construct(
        AnnualLeaveService $annualLeaveService,
    )
    {
        $this->annualLeaveService  = $annualLeaveService;
    }

    public function index(){
        $title = __("Annual Leave Management");

        return view('admin.annual_leave.index', compact('title'));
    }

    public function search(Request $request)
    {

        $response = $this->annualLeaveService->search($request);

        return json_encode([
            "success"   => true,
            "rows"      => $response['results'] ?? [],
            "total"     => $response['recordsTotal'] ?? 0,
        ]);
    }

    function store(Request $request){

        $response = $this->annualLeaveService->store($request);
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
        $response = $this->annualLeaveService->destroy($request);
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

    public function process(){
        $title = __("Process Annual Leave");

        return view('admin.annual_leave.process', compact('title'));
    }

    function processData(Request $request){


        $response = $this->annualLeaveService->processData($request);
        if($response){

            return response()->json([
                'success' => true,
                'message' => __(config('messages.commons.process_success')),
            ]);

        }

        return response()->json([
            'success' => false,
            'message' => __(config('messages.commons.process_failed')),
        ]);
    }
}
