<?php

namespace App\Services;

use App\Models\LeaveType;
use App\Models\User;
use App\Models\UserLeave;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function search($request){
        return $this->userRepository->search($request);
    }

    public function searchTerminate($request){
        return $this->userRepository->searchTerminate($request);
    }

    public function getAll($request){
        return $this->userRepository->getAll($request);
    }


    public function getEmployeeInfo($request, $code){
        return $this->userRepository->getEmployeeInfo($request, $code);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->userRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->userRepository->update($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->userRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserService@destroy %s", $e->getMessage()));

            return false;
        }

    }
    public function saveLastedLogin(){
        return $this->userRepository->updateById(Auth::user()->id, ['lasted_login' => Carbon::now()->format('Y-m-d H:i:s')]);
    }

    public function importExcel($request){

        $company_id = config('constants.COMPANY_ID');

        $file = $request->file('file');

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($file->getPathName());

        // Get the active sheet (first sheet)
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(null, true, true, true);

        $hash_genders = [
            'Nam'   => 1,
            'Nữ'    => 0,
            'Khác'  => 3,
        ];

        if($rows){
            unset($rows[1]);
        }

        $list_users = User::query()->where('company_id', config('constants.COMPANY_ID'))->get();
        $list_user_codes = $list_users->pluck('id', 'code')->toArray();

        $list = [];
        foreach ($rows as $key => $item) {

            $code               = trim($item['A']);
            $name               = trim($item['B']);
            $birthday           = trim($item['C']);
            $gender_name        = trim($item['D']);
            $id_card            = trim($item['E']);
            $id_card_issue_date = trim($item['F']);
            $id_card_issue_place= trim($item['G']);
            $phone              = trim($item['H']);
            $email              = trim($item['I']);
            $address            = trim($item['J']);

            $id = $list_user_codes[$code] ?? '';

            if(empty($code)){ continue; }

            $birthday = Carbon::createFromFormat('d/m/Y', $birthday)->format('Y-m-d');
            $id_card_issue_date = Carbon::createFromFormat('d/m/Y', $id_card_issue_date)->format('Y-m-d');
            $gender = $hash_genders[$gender_name] ?? '';

            $list[] = [
                'id' => $id,
                'company_id' => $company_id,
                'code' => $code,
                'name' => $name,
                'birthday' => $birthday,
                'gender' => $gender,
                'id_card' => $id_card,
                'id_card_issue_date' => $id_card_issue_date,
                'id_card_issue_place' => $id_card_issue_place,
                'phone' => $phone,
                'email' => $email,
                'password' => Hash::make('easy4test'),
                'address' => $address,
            ];
        }

//        $chunks = array_chunk($list, config('constants.CHUNK_SIZE'));

        DB::beginTransaction();
        try {
            foreach ($list as $key => $item) {
                if(empty($item['id'])){
                    $this->userRepository->create($item);
                }else{
                    $this->userRepository->updateById($item['id'], $item);
                }
            }

            DB::commit();
            return [
                'success' => true,
                'count' => number_format(count($list)),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserService@importExcel %s", $e->getMessage()));
            return ['success' => false, 'error' => $e->getMessage()];
        }

    }

    public function storeGeneral($request)
    {
        try {
            DB::beginTransaction();

            $type = $request->type ?? null;

            $data = $request->all();

            $employeeId = $data['id'] ?? null;

            if (empty($employeeId)) {
                return [
                    'success' => false,
                    'message' => __('Employee ID can not be empty'),
                ];

            }

            unset($data['code']);

            if ($request->hasFile('avatar')) {
                $request->validate([
                    'avatar' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
                ]);

                $avatar = $request->file('avatar');
                $randomFileName = Str::random(20) . '.' . $avatar->getClientOriginalExtension(); // Tạo tên ngẫu nhiên

               $path = $avatar->storeAs('uploads/employees', $randomFileName, 'public');
                $data['avatar'] = $path;
            }

            if (!empty($data['join_date'])) {
                try {
                    $data['join_date'] = Carbon::createFromFormat('d/m/Y', $data['join_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    return [
                        'success' => false,
                        'message' => __('Invalid date format for join_date. Please use dd/mm/yyyy'),
                    ];
                }
            }

            if (!empty($data['seniority_date'])) {
                try {
                    $data['seniority_date'] = Carbon::createFromFormat('d/m/Y', $data['seniority_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    return [
                        'success' => false,
                        'message' => __('Invalid date format for seniority_date. Please use dd/mm/yyyy'),
                    ];
                }
            }

            if (!empty($data['termination_date'])) {
                try {
                    $data['termination_date'] = Carbon::createFromFormat('d/m/Y', $data['termination_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    return [
                        'success' => false,
                        'message' => __('Invalid date format for termination_date. Please use dd/mm/yyyy'),
                    ];
                }
            }

            if($type == 'position_office'){
                if(empty($data['is_foreigner'])){ $data['is_foreigner'] = 0; }
                if(empty($data['is_office'])){ $data['is_office'] = 0; }
            }

            $employee = User::find($employeeId);
            $employee->fill($data);
            $employee->save($data);
            $message = __('Employee information updated successfully.');

            DB::commit();

            return [
                'success' => true,
                'message' => $message,
                'data' => $employee
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UserService::storeGeneral - Error: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => __('An error occurred while processing employee information.'),
                'error' => $e->getMessage()
            ];
        }
    }

    public function storePersonal($request)
    {
        try {
            DB::beginTransaction();

            $type = $request->type ?? null;

            $data = $request->all();

            $employeeId = $data['id'] ?? null;

            if (empty($employeeId)) {
                return [
                    'success' => false,
                    'message' => __('Employee ID can not be empty'),
                ];

            }

            unset($data['code']);

            if (!empty($data['birthday'])) {
                try {
                    $data['birthday'] = Carbon::createFromFormat('d/m/Y', $data['birthday'])->format('Y-m-d');
                } catch (\Exception $e) {
                    return [
                        'success' => false,
                        'message' => __('Invalid date format for join_date. Please use dd/mm/yyyy'),
                    ];
                }
            }

            if($type == 'personal_info'){
                if(empty($data['is_married'])){ $data['is_married'] = 0; }
            }

            if($type == 'cccd_passport'){
                $data['id_card_issue_date'] = convertDateFormat($data['id_card_issue_date']);
                $data['passport_issue_date'] = convertDateFormat($data['passport_issue_date']);
                $data['passport_expiry_date'] = convertDateFormat($data['passport_expiry_date']);
            }

            if($type == 'insurance'){
                $data['social_insurance_date'] = convertDateFormat($data['social_insurance_date']);
            }

            if($type == 'employee_status'){
                $data['user_status_from_date'] = convertDateFormat($data['user_status_from_date']);

                if($data['user_status_id'] == User::USER_STATUS_TERMINATE_ID){
                    $data['terminate_date'] = $data['user_status_from_date'];
                }

            }



            $employee = User::find($employeeId);
            $employee->fill($data);
            $employee->save($data);
            $message = __('Employee information updated successfully.');

            DB::commit();

            return [
                'success' => true,
                'message' => $message,
                'data' => $employee
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UserService::storeGeneral - Error: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => __('An error occurred while processing employee information.'),
                'error' => $e->getMessage()
            ];
        }
    }

}
