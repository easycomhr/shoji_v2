<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\FamilyRelationshipRepository;
use App\Services\BaseService;
use App\Services\FamilyRelationshipService;
use Illuminate\Http\Request;

class FamilyRelationshipController extends Controller
{
    protected FamilyRelationshipService $familyRelationshipService;

    public function __construct(FamilyRelationshipService $familyRelationshipService)
    {
        $this->familyRelationshipService = $familyRelationshipService;
    }

    public function index(){
        $title = "Family Relationships";

        return view('admin.family_relationship.index', compact('title'));
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

        $response = $this->familyRelationshipService->search($request);

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
        $response = $this->familyRelationshipService->store($request);
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
        $response = $this->familyRelationshipService->destroy($request);
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
