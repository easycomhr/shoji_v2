<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\UserLeave;
use App\Repositories\UserLeaveRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserLeaveService
{
    protected UserRepository $userRepository;
    protected UserLeaveRepository $userLeaveRepository;

    public function __construct(
        UserRepository $userRepository,
        UserLeaveRepository $userLeaveRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->userLeaveRepository = $userLeaveRepository;
    }

    public function search($request){
        return $this->userLeaveRepository->search($request);
    }

    public function getAll($request){
        return $this->userRepository->getAll($request);
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
    public function importExcel($request){

        $company_id = config('constants.COMPANY_ID');

        $file = $request->file('file');

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($file->getPathName());

        // Get the active sheet (first sheet)
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(null, true, true, true);

        $hash_sessions = [
            'Sáng'     => 1,
            'Chiều'    => 2,
            'Cả ngày'  => 3,
        ];

        if($rows){
            unset($rows[1]);
        }

        $list_users = User::query()->where('company_id', config('constants.COMPANY_ID'))->get();
        $list_user_codes = $list_users->pluck('id', 'code')->toArray();

        $list_leave_types = LeaveType::pluck('id', 'name')->toArray();

        $list = [];
        foreach ($rows as $key => $item) {

            $user_code          = trim($item['A']);
            $leave_date         = trim($item['C']);
            $leave_amount       = trim($item['D']);
            $leave_type_name    = trim($item['E']);
            $leave_session_name = trim($item['F']);
            $approved           = trim($item['G']);
            $note               = trim($item['H']);

            $user_id = $list_user_codes[$user_code] ?? '';
            $leave_type_id = $list_leave_types[$leave_type_name] ?? '';
            $leave_session_id = $hash_sessions[$leave_session_name] ?? '';

            if(empty($user_id)){ continue; }

            $leave_date = Carbon::createFromFormat('d/m/Y', $leave_date)->format('Y-m-d');

            $user_leave = UserLeave::query()
                ->where('user_id', $user_id)
                ->where('leave_date', $leave_date)
                ->where('leave_type_id', $leave_type_id)
                ->first();

            $id = $user_leave->id ?? '';

            $list[] = [
                'id' => $id,
                'company_id' => $company_id,
                'user_id' => $user_id,
                'user_code' => $user_code,
                'leave_date' => $leave_date,
                'leave_amount' => $leave_amount,
                'leave_type_id' => $leave_type_id,
                'leave_session_id' => $leave_session_id,
                'approved' => $approved,
                'note' => $note,
            ];
        }

//        $chunks = array_chunk($list, config('constants.CHUNK_SIZE'));

        DB::beginTransaction();
        try {
            foreach ($list as $key => $item) {
                if(empty($item['id'])){
                    $this->userLeaveRepository->create($item);
                }else{
                    $this->userLeaveRepository->updateById($item['id'], $item);
                }
            }

            DB::commit();
            return [
                'success' => true,
                'count' => number_format(count($list)),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserLeaveService@importExcel %s", $e->getMessage()));
            return ['success' => false, 'error' => $e->getMessage()];
        }

    }


}
