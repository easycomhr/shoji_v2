<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserInsuranceService;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    protected UserInsuranceService $service;

    public function __construct(UserInsuranceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $title = 'Bảo Hiểm';

        return view('admin.insurance.index', compact('title'));
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
}
