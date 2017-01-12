<?php

namespace App\Http\Controllers;

use App\Companies;
use App\Founders;
use App\Invests;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests;

class InvestAdminController extends Controller
{

    public function index()
    {

        $limit = 30;
        $projections = ['*'];
        $invests = Invests::paginate($limit, $projections);

        $result = array(
            'invests' => $invests,
        );
        return view("admin.invest")->with($result);
    }

    public function show($id)
    {
        $invest = Invests::find($id);
        return view("admin.invest_detail")->with("invest",$invest);
    }
}
