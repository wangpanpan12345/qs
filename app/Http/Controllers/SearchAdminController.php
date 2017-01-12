<?php

namespace App\Http\Controllers;
use App\Companies;
use App\Founders;
use Illuminate\Http\Request;
use App\Http\Requests;

class SearchAdminController extends Controller
{

    public function index(){

    }

    public function name_k(Request $r){
//        dd($r["k"]);
        $k = $r->input("k");
        $s_n_r = Companies::where('name','like','%'.$k.'%')->get();
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
        );

        return view('admin/search')->with($result);
    }
    public function name_k_dy(Request $r){
        $k = $r["name"];
        $s_n_r = Companies::where('name','like','%'.$k.'%')->get()->take(10);
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
            "error" => 0,
        );

        return $result;
    }
    public function name_k_p_dy(Request $r){
        $k = $r["name"];
        $s_n_r = Founders::where('name','like','%'.$k.'%')->get()->take(10);
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
            "error" => 0,
        );

        return $result;
    }
    public function insert_search(Request $r){
        $k = $r->input("name");
        $s_n_r = Companies::where('name','like','%'.$k.'%')->take(10)->get();
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
        );
        return $result;
    }
}
