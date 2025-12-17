<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request){
        $title = "QrCode Lists";
        return view('admin.qr_code.index', compact('title'));
    }

    public function show(Request $request){
        $title = "QrCode";
        $url = 'https://docs.google.com/forms/d/e/1FAIpQLSeV-OZOL1Yu7XIv1IEl1c0niVy4Gearc-_LWKIVSFAloi52kQ/viewform?vc=0&c=0&w=1&flr=0';
        QrCode::format('png')->size(512)->generate($url, public_path('images/qr_codes/qrcode.png'));
        $qrCode = QrCode::size(512)->generate($url);

        return view('admin.qr_code.show', compact('title', 'qrCode'));
    }

    public function add(){
        $title = "Create QrCode";
        return view('admin.qr_code.add', compact('title'));
    }

    public function store(UserRequest $request){

        $response = $this->userService->store($request);
        if($response){
            $message = $request->id ? "Account ".$request->name." has been updated!" : "Account ".$request->name." has been created!";

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->withErrors(['error' => 'Save data failed！']);

    }

    public function detail(Request $request){

        $response = $this->userService->findById($request->id);
        if($response){
            return response()->json([
                'success'   => true,
                'data'      => $response,
            ]);
        }

        return response()->json([
            'success'   => false,
            'message'   => "保存に失敗しました。",
        ]);

    }

    public function destroy(Request $request){

        $response = $this->userService->destroy($request);

        if($response){
            return redirect()->back()->with('success', 'Delete account successfully。');
        }

        return redirect()->back()->with('error', 'Delete account failed。');

    }

    public function changeLogin(Request $request){

        $response = $this->userService->changeLogin($request);

        if($response){
            return redirect()->back()->with('success', 'Change login permission successfully。');
        }

        return redirect()->back()->with('error', 'Change login permission failed。');

    }

    public function import(){
        $title = "Import User";
        return view('admin.user.import', compact('title'));
    }

    public function importExcel(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $response = $this->userService->importExcel($request);
        if ($response) {
            $message = "Import data successfully";

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->withErrors(['error' => 'Import data failed！']);


    }

    public function exportExcel(Request $request)
    {
        $filename = 'user_'.time().'.xlsx';
        $this->userService->exportExcel($request, $filename);

        return response()->download(public_path($filename))->setContentDisposition('attachment')->deleteFileAfterSend();
    }


}
