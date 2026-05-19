<?php

namespace App\Services;

use App\Repositories\LabourContractRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LabourContractService extends BaseService
{
    protected LabourContractRepository $labourContractRepository;

    public function __construct(LabourContractRepository $labourContractRepository)
    {
        $this->labourContractRepository = $labourContractRepository;
    }

    public function search($request)
    {
        return $this->labourContractRepository->search($request);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if (empty($request->id)) {
                $response = $this->labourContractRepository->create([
                    'user_id'          => $request->user_id,
                    'contract_type_id' => $request->contract_type_id,
                    'start_date'       => $request->start_date,
                    'end_date'         => $request->end_date,
                    'signed_date'      => $request->signed_date,
                    'status'           => $request->status ?? 'active',
                    'notes'            => $request->notes,
                ]);
            } else {
                $data = [];
                if ($request->field) {
                    $data[$request->field] = $request->value;
                } else {
                    $data = $request->only([
                        'user_id',
                        'contract_type_id',
                        'start_date',
                        'end_date',
                        'signed_date',
                        'status',
                        'notes',
                    ]);
                }
                $response = $this->labourContractRepository->updateById($request->id, $data);
            }

            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("LabourContractService@store %s", $e->getMessage()));
            return false;
        }
    }

    public function exportExcel(int $id, string $filename): void
    {
        $authUser = Auth::user();

        $contract = $this->labourContractRepository->search((object)[
            'user_id' => null,
            'name'    => null,
            'status'  => null,
            'limit'   => 1,
            'start'   => 0,
        ]);

        // Fetch single record with joins
        $row = \Illuminate\Support\Facades\DB::table('labour_contracts')
            ->leftJoin('users', 'users.id', '=', 'labour_contracts.user_id')
            ->leftJoin('contract_types', 'contract_types.id', '=', 'labour_contracts.contract_type_id')
            ->select([
                'labour_contracts.id',
                'users.code',
                'users.name',
                'contract_types.name as contract_type_name',
                'labour_contracts.contract_number',
                'labour_contracts.start_date',
                'labour_contracts.end_date',
                'labour_contracts.signed_date',
                'labour_contracts.status',
                'labour_contracts.notes',
            ])
            ->where('labour_contracts.id', $id)
            ->first();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator(config('constants.COMPANY.name'))
            ->setLastModifiedBy($authUser->name ?? '')
            ->setTitle('Labour Contract')
            ->setSubject('Labour Contract Export')
            ->setKeywords('Labour Contract')
            ->setCategory('Xlsx file');

        $ws = $spreadsheet->getActiveSheet();

        $headerRow = 4;
        $ws->setCellValue('A2', 'HỢP ĐỒNG LAO ĐỘNG');
        $ws->setCellValue('A' . $headerRow, 'STT');
        $ws->setCellValue('B' . $headerRow, 'Mã NV');
        $ws->setCellValue('C' . $headerRow, 'Tên nhân viên');
        $ws->setCellValue('D' . $headerRow, 'Loại hợp đồng');
        $ws->setCellValue('E' . $headerRow, 'Số hợp đồng');
        $ws->setCellValue('F' . $headerRow, 'Ngày bắt đầu');
        $ws->setCellValue('G' . $headerRow, 'Ngày kết thúc');
        $ws->setCellValue('H' . $headerRow, 'Ngày ký');
        $ws->setCellValue('I' . $headerRow, 'Trạng thái');
        $ws->setCellValue('J' . $headerRow, 'Ghi chú');

        if ($row) {
            $dataRow = $headerRow + 1;
            $ws->setCellValue('A' . $dataRow, 1)
               ->setCellValue('B' . $dataRow, $row->code ?? '')
               ->setCellValue('C' . $dataRow, $row->name ?? '')
               ->setCellValue('D' . $dataRow, $row->contract_type_name ?? '')
               ->setCellValue('E' . $dataRow, $row->contract_number ?? '')
               ->setCellValue('F' . $dataRow, $row->start_date ?? '')
               ->setCellValue('G' . $dataRow, $row->end_date ?? '')
               ->setCellValue('H' . $dataRow, $row->signed_date ?? '')
               ->setCellValue('I' . $dataRow, $row->status ?? '')
               ->setCellValue('J' . $dataRow, $row->notes ?? '');
        }

        $lastRow = $headerRow + 1;
        $lastCol = 'J';

        foreach ([
            ['label' => 'A', 'width' => 6],
            ['label' => 'B', 'width' => 14],
            ['label' => 'C', 'width' => 24],
            ['label' => 'D', 'width' => 22],
            ['label' => 'E', 'width' => 18],
            ['label' => 'F', 'width' => 14],
            ['label' => 'G', 'width' => 14],
            ['label' => 'H', 'width' => 14],
            ['label' => 'I', 'width' => 14],
            ['label' => 'J', 'width' => 30],
        ] as $col) {
            $ws->getColumnDimension($col['label'])->setWidth($col['width']);
        }

        $ws->mergeCells('A2:' . $lastCol . '2');
        $ws->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $ws->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $ws->getRowDimension($headerRow)->setRowHeight(21);
        $ws->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)
           ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $ws->getStyle('A' . $headerRow . ':' . $lastCol . $lastRow)
           ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)
           ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('BFBFBF');
        $ws->getStyle('A' . $headerRow . ':' . $lastCol . $lastRow)
           ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('5F5F5F');
        $ws->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)
           ->getFont()->setBold(true)->setSize(11);

        $ws->setShowGridlines(false);

        $spreadsheet->getSecurity()->setLockWindows(true);
        $spreadsheet->getSecurity()->setLockStructure(true);
        $spreadsheet->getSecurity()->setWorkbookPassword(config('constants.SECURITY.xlsx_password'));

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
    }

    public function destroy($request)
    {
        DB::beginTransaction();
        try {
            $this->labourContractRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("LabourContractService@destroy %s", $e->getMessage()));
            return false;
        }
    }
}
