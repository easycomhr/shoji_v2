<?php
namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class QrCodeService
{

    protected UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function findByParams($params = []){
        return $this->userRepository->findByParams($params);
    }

    public function findById($id){
        return $this->userRepository->findById($id);
    }

    public function search($request){
        return $this->userRepository->search($request);
    }

    public function generate($request){

    }

    public function store($request){

        $params = $request->all();

        $birthday = $request->birthday ?? null;
        $avatar = $request->file('avatar');

        if($birthday){
            $params['birthday'] = Carbon::createFromFormat('d/m/Y', $birthday)->format('Y-m-d');
        }
        $params['is_login'] = isset($request->is_login) && $request->is_login == 'on' ? 1 : 0;

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $response = $this->userRepository->create($params);
            }else{
                $response = $this->userRepository->updateById($request->id, $params);
            }

            if($avatar){
                $avatar_name = bin2hex(random_bytes(8)) . '.' . $avatar->getClientOriginalExtension();
                $avatar->move(public_path('assets/avatars'), $avatar_name);
                $this->userRepository->updateById($response->id, ['avatar' => $avatar_name]);
            }

            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function saveLastedLogin(){
        return $this->userRepository->updateById(Auth::user()->id, ['lasted_login' => Carbon::now()->format('Y-m-d H:i:s')]);
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

    public function changeLogin($request){

        DB::beginTransaction();
        try {

            $response = $this->userRepository->findById($request->id);

            if(!$response){
                DB::rollBack();
                return false;
            }

            $this->userRepository->updateById($request->id, [
                'is_login' => $response->is_login ? 0 : 1
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserService@changeLogin %s", $e->getMessage()));
            return false;
        }

    }

    public function importExcel($request){

        $file = $request->file('file');

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($file->getPathName());

        // Get the active sheet (first sheet)
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(null, true, true, true);

        $hash_roles = [
            'Admin'     => UserRole::Admin,
            'Manager'   => UserRole::Manager,
            'Customer'  => UserRole::Customer,
        ];

        if($rows){
            unset($rows[1]);
        }

        $list_user = User::pluck('id', 'email');

        $list = [];
        foreach ($rows as $key => $item) {

            $email = trim(strtolower($item['C'] ?? ''));
            $name = trim($item['B'] ?? '');
            $password = trim($item['D'] ?? '');
            $role = $hash_roles[ $item['E'] ?? ''] ?? UserRole::Customer;
            $is_login = isset($item['F']) && $item['F'] == 1 ? 1 : 0;

            if(empty($email)){continue;}

            $id = $list_user[$email] ?? null;

            $cell_value = [
                'id'        => $id,
                'name'      => $name,
                'email'     => $email,
                'role'      => $role,
                'is_login'  => $is_login,
            ];

            if($id){$cell_value['id'] = $id;}
            if($password){$cell_value['password'] = $password;}

            $list[] = $cell_value;
        }

//        $chunks = array_chunk($list, config('constants.CHUNK_SIZE'));

        DB::beginTransaction();
        try {
            foreach ($list as $key => $user) {
                if(empty($user['id'])){
                    $this->userRepository->create($user);
                }else{
                    $this->userRepository->updateById($user['id'], $user);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserService@importExcel %s", $e->getMessage()));
            return false;
        }

    }

    public function exportExcel($request, $filename){

        $authUser = Auth::user();

        // Tạo mới file Excel
        $spreadsheet = new Spreadsheet();

        // Thêm thông tin bản quyền
        $spreadsheet->getProperties()
            ->setCreator(config('constants.COMPANY.name'))
            ->setLastModifiedBy($authUser->name ?? '')
            ->setTitle('File User List')
            ->setSubject('File User List')
            ->setDescription('List of users')
            ->setKeywords('User List')
            ->setCategory('Xlsx file');

        // Lấy sheet đang hoạt động
        $worksheet = $spreadsheet->getActiveSheet();

        $index_header = 4;
        // Đặt dữ liệu vào ô A1
        $worksheet
            ->setCellValue('A'.($index_header - 2), 'User List')
            ->setCellValue('A'.$index_header, 'No.')
            ->setCellValue('B'.$index_header, 'Name')
            ->setCellValue('C'.$index_header, 'Birthday')
            ->setCellValue('D'.$index_header, 'Email')
            ->setCellValue('E'.$index_header, 'Role')
            ->setCellValue('F'.$index_header, 'Lasted Login')

        ;
        $request->is_all = true;
        $list = $this->userRepository->search($request);

        $index_data = $index_header + 1;

        foreach ($list as $key => $item){
            $worksheet->setCellValue('A'.$index_data, $key + 1)
                ->setCellValue('B'.$index_data, $item->name ?? '')
                ->setCellValue('C'.$index_data, $item->birthday_format)
                ->setCellValue('D'.$index_data, $item->email)
                ->setCellValue('E'.$index_data, $item->role_name ?? '')
                ->setCellValue('F'.$index_data, $item->lasted_login ?? '')
            ;
            $index_data++;
        }

        $last_row = $index_data - 1;

        /**
         * Format worksheet
         */
        $worksheet->getPageSetup()->setVerticalCentered(true);
        $list_column = [
            ['label' => 'A', 'width' => 6],
            ['label' => 'B', 'width' => 24],
            ['label' => 'C', 'width' => 15],
            ['label' => 'D', 'width' => 30],
            ['label' => 'E', 'width' => 20],
            ['label' => 'F', 'width' => 20],
        ];

        foreach ($list_column as $column){
            $worksheet->getColumnDimension($column['label'])->setWidth($column['width']);
        }

        $worksheet->getRowDimension($index_header)->setRowHeight(21);
        $worksheet->getStyle('A'.$index_header.':F'.$index_header)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A'.($index_header + 1).':A'.$last_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('C'.($index_header + 1).':C'.$last_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('F'.($index_header + 1).':F'.$last_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:F'.$last_row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $worksheet->getStyle('A'.$index_header.':F'.$index_header)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('BFBFBF');
        $worksheet->getStyle('A'.$index_header.':F'.$last_row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('5F5F5F');

        $worksheet->mergeCells('A2:F2');
        $worksheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $worksheet->getStyle('A'.$index_header.':F'.$index_header)->getFont()->setBold(true)->setSize(11);
        $worksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $worksheet->setShowGridlines(false);

        // Đặt mật khẩu bảo vệ cho file Excel
        $spreadsheet->getSecurity()->setLockWindows(true);
        $spreadsheet->getSecurity()->setLockStructure(true);
        $spreadsheet->getSecurity()->setWorkbookPassword(config('constants.SECURITY.xlsx_password')); // Đặt mật khẩu tại đây

        // Lưu file với định dạng .xlsx
        $writer = new Xlsx($spreadsheet);

        // Lưu vào đường dẫn cụ thể (có thể là đường dẫn động)
        $writer->save($filename);

    }

}
