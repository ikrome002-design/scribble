<?php

namespace App\Http\Controllers;

use App\Client;
use App\Invoices;
use App\Models\ProSubscriptionFile;
use App\Models\Visitor;
use App\Receipts;
use App\SenderIdFiles;
use App\SenderIdManage;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:client,admin,staff');
    }

    public function senderId($filename)
    {

        $checkfile = SenderIdFiles::where('filename', $filename)->first();
        $path = storage_path('/app/private/senderid/' . $filename);
        $sender_id_clients = json_decode($checkfile->senderId->cl_id);

        if (Auth::guard('client')->check()) {
            if (!in_array(Auth::guard('client')->user()->id, $sender_id_clients)) {
                abort(404);
            }
        }

        if (!File::exists($path)) {

            abort(404);
        }


        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file, 200);

        $response->header("Content-Type", $type);
        return $response;
    }

    public function clientProfileImage($id, $image)
    {

        $client = Client::where([['image', $image], ['id', $id]])->first();
        $path = storage_path('/app/private/profile/client/' . $image);
        if (Auth::guard('admin')->check()) {
            if (!File::exists($path)) {

                abort(404);
            }

            $file = File::get($path);

            $type = File::mimeType($path);

            $response = Response::make($file, 200);

            $response->header("Content-Type", $type);

            return $response;
        }

        if (Auth::guard('client')->check()) {
            if (Auth::guard('client')->user()->id != $client->id) {
                abort(404);
            }

            if (!File::exists($path)) {

                abort(404);
            }

            $file = File::get($path);

            $type = File::mimeType($path);

            $response = Response::make($file, 200);

            $response->header("Content-Type", $type);

            return $response;
        }
    }

    public function adminProfileImage($image)
    {

        $path = storage_path('/app/private/profile/admin/' . $image);
        if (!Auth::guard('admin')->check()) {
            abort(404);
        }

        if (!File::exists($path)) {

            abort(404);
        }

        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file, 200);

        $response->header("Content-Type", $type);

        return $response;
    }

    public function visitorImage($image)
    {
        if (auth('client')->check() || auth('staff')->check() || auth('admin')->check()) {
        } else {
            abort(404);
        }
        $path = storage_path('/app/private/visitor/' . $image);

        if (!File::exists($path)) {

            abort(404);
        }

        $v = Visitor::where('image', $image)->first();

        if (!$v) {
            abort(404);
        }
        if (auth('client')->check()) {
            $check = $v->whereHas('proSubscription', function ($q) {
                $q->where('cl_id', auth('client')->user()->id);
            })->first();
            if (!$check) {
                abort(404);
            }
        }
        if (auth('staff')->check()) {
            $check = $v->whereHas('proSubscription', function ($q) {
                $q->where('cl_id', auth('staff')->user()->cl_id);
            })->first();
            if (!$check) {
                abort(404);
            }
        }

        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file, 200);

        $response->header("Content-Type", $type);

        return $response;
    }

    public function staffImage($image)
    {

        $path = storage_path('/app/private/staff/' . $image);


        if (!File::exists($path)) {

            abort(404);
        }

        $s = Staff::where('image', $image)->first();

        if (!$s) {
            abort(404);
        }

        if (auth('client')->check()) {
            if ($s->cl_id != auth('client')->user()->id) {
                abort(404);
            }
        }

        if (auth('staff')->check()) {
            if ($s->cl_id != auth('staff')->user()->cl_id) {
                abort(404);
            }
        }

        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file, 200);

        $response->header("Content-Type", $type);

        return $response;
    }
}
