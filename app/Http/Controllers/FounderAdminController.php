<?php

namespace App\Http\Controllers;
use App\Founders;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests;

class FounderAdminController extends Controller
{

    public function index(){

    }

    public function founder_detail_id($id){
        $detail = Founders::find($id);
        return view('admin/founder')->with('detail',$detail);
    }
}
