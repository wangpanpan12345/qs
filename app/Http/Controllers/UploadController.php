<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect, Input, Response;

class UploadController extends Controller
{

    //Ajax上传图片
    public function imgUpload(Request $request)
    {
//        return Response::json(
//            [
//                'success' => true,
////                'pic' => asset($destinationPath.$fileName),
//                'id' => $request['file']->getClientOriginalExtension()
//            ]
//        );

        $file = $request['file'];
        $id = $request['id'];
        $allowed_extensions = ["png", "jpg", "gif","JPG","jpeg"];
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return ['error' => 'You may only upload png, jpg or gif.'];
        }

        $destinationPath = storage_path('namecard');
        $extension = $file->getClientOriginalExtension();

        $fileName = str_random(10).'.'.$extension;
        $file->move($destinationPath, $fileName);
        return Response::json(
            [
                'success' => true,
                'pic' => asset('namecard/'.$fileName),
                'id' => $id
            ]
        );
    }
}