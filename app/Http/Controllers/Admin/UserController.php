<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\BaseService;
use App\Services\DepartmentService;
use App\Services\NationService;
use App\Services\PositionService;
use App\Services\UserService;
use App\Services\UserStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected UserService $userService;
    protected DepartmentService  $departmentService;
    protected PositionService $positionService;
    protected NationService  $nationService;
    protected UserStatusService  $userStatusService;

    public function __construct(
        UserService $userService,
        DepartmentService $departmentService,
        PositionService $positionService,
        NationService $nationService,
        UserStatusService $userStatusService
    )
    {
        $this->userService = $userService;
        $this->departmentService = $departmentService;
        $this->positionService = $positionService;
        $this->nationService = $nationService;
        $this->userStatusService = $userStatusService;
    }

    public function index(){
        $title = "Users";

        return view('admin.user.index', compact('title'));
    }

    public function search(Request $request)
    {

        $response = $this->userService->search($request);

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
        $response = $this->userService->store($request);
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
        $response = $this->userService->destroy($request);
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
        $title = __("Import Employees");

        return view('admin.user.import', compact('title'));
    }

    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $response = $this->userService->importExcel($request);

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

    public function profile(Request $request, $page, $code){
        $title = __("Profile of :code", ['code' => $code]);

        $employee = $this->userService->getEmployeeInfo($request, $code);

        $departments = $this->departmentService->getAll($request);
        $positions = $this->positionService->getAll($request);
        $nations = $this->nationService->getAll($request);
        $user_statuses = $this->userStatusService->getAll($request);

        return view('admin.user.profile', compact(
            'title',
            'employee',
            'departments',
            'positions',
            'nations',
            'user_statuses',
            'page',
            'code',
        ));
    }

    public function storeGeneral(Request $request)
    {

        $type = $request->type ?? '';

        if($type == 'basic_information'){
            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'gender' => 'required|in:0,1',
                'id' => 'nullable|integer|exists:users,id',
                'code' => 'nullable|string|max:50',
            ], [
                // Custom error messages
                'name.required' => __('Full name is required.'),
                'name.string' => __('Full name must be a string.'),
                'name.max' => __('Full name may not be greater than 255 characters.'),
                'gender.required' => __('Gender is required.'),
                'gender.in' => __('Gender must be either Male or Female.'),
                'id.integer' => __('Invalid employee ID.'),
                'id.exists' => __('Employee not found.'),
                'code.max' => __('Employee code may not be greater than 50 characters.'),
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', __('Please correct the errors below.'));
            }
        }



        try {
            // Call UserService to handle the business logic
            $result = $this->userService->storeGeneral($request);

            if ($result['success']) {
                return redirect()->back()
                    ->with('success', $result['message'] ?? __('Employee information updated successfully.'));
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $result['message'] ?? __('An error occurred while updating employee information.'));
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in storeGeneral: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', __('An unexpected error occurred. Please try again.'));
        }
    }
    public function storePersonal(Request $request)
    {

        $type = $request->type ?? '';



        try {
            // Call UserService to handle the business logic
            $result = $this->userService->storePersonal($request);

            if ($result['success']) {
                return redirect()->back()
                    ->with('success', $result['message'] ?? __('Employee information updated successfully.'));
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $result['message'] ?? __('An error occurred while updating employee information.'));
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in storeGeneral: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', __('An unexpected error occurred. Please try again.'));
        }
    }

    public function terminate(Request $request){
        $title = __("Employee terminates");

        return view('admin.user.terminate', compact('title'));
    }

    public function searchTerminate(Request $request)
    {

        $response = $this->userService->searchTerminate($request);

        return json_encode([
            "success"   => true,
            "rows"      => $response['results'] ?? [],
            "total"     => $response['recordsTotal'] ?? 0,
        ]);
    }

}
