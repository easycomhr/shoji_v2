<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChangeEmployeeCodeService;
use Illuminate\Http\Request;

class ChangeEmployeeCodeController extends Controller
{
    protected ChangeEmployeeCodeService $service;

    public function __construct(ChangeEmployeeCodeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $title = 'Thay Đổi Mã Nhân Viên';

        return view('admin.change_employee_code.index', compact('title'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'on_date' => 'required|date',
        ]);

        $result = $this->service->process((int) $request->user_id, $request->on_date);

        return response()->json($result);
    }
}
