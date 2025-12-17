<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ErrorController extends Controller
{


    public function __construct()
    {

    }

    public function permissionDenied(Request $request){

        return view('admin.error.permission_denied');
    }
}
