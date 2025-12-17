<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\TransportationTypeRepository;
use App\Services\BaseService;
use App\Services\TransportationTypeService;
use Illuminate\Http\Request;

class TransportationTypeController extends Controller
{
    protected TransportationTypeService $transportationTypeService;

    public function __construct(TransportationTypeService $transportationTypeService)
    {
        $this->transportationTypeService = $transportationTypeService;
    }

    public function index(){
        $title = "Transportation Types";

        return view('admin.transportation_type.index', compact('title'));
    }

    public function search(Request $request)
    {

        $action = $request->action ?? config('constant.actions.view');
        $isAllow = BaseService::verifyAction($request, $action);

        if(!$isAllow){
            return json_encode([
                'success' => false,
                'message' => __(config('messages.errors.not_enough_permission'))
            ]);
        }

        $response = $this->transportationTypeService->search($request);

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
        $response = $this->transportationTypeService->store($request);
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
        $response = $this->transportationTypeService->destroy($request);
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
}
