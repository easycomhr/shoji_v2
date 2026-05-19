<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LabourContractService;
use Illuminate\Http\Request;

class LabourContractController extends Controller
{
    protected LabourContractService $service;

    public function __construct(LabourContractService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $title = 'Hợp Đồng Lao Động';

        return view('admin.labour_contract.index', compact('title'));
    }

    public function search(Request $request)
    {
        $result = $this->service->search($request);

        return json_encode([
            'success' => true,
            'rows'    => $result['data'],
            'total'   => $result['total'],
        ]);
    }

    public function store(Request $request)
    {
        $response = $this->service->store($request);

        return response()->json(['success' => (bool) $response]);
    }

    public function destroy(Request $request)
    {
        $response = $this->service->destroy($request);

        return response()->json(['success' => (bool) $response]);
    }

    public function exportExcel(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:labour_contracts,id']);

        $filename = public_path('labour_contract_' . time() . '.xlsx');
        $this->service->exportExcel((int) $request->id, $filename);

        return response()->download($filename)->deleteFileAfterSend();
    }
}
