<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::orderBy("sale_id","desc")->paginate(10);
        return view("purchasehistory", compact("sales"));
    }
}
