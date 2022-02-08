<?php

namespace App\Http\Controllers\dashboard;
use App\Http\Controllers\Controller;
use App\Models\shop\Shop;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

	 public function dashboard()
    {

$status=0;
$shops = Shop::where('check_point', 1)->with(['unitmovement'=> function ($query) use( $status) {
    $query->where('current_shop','>', $status);
}]) ->get();
                           
   return view('dashboard.index')->with(compact('shops'));

   }
}
