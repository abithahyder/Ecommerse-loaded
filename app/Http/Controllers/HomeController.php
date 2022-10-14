<?php

namespace App\Http\Controllers;

use App\Client;
use App\Coupons;
use App\Order;
use App\Ordermaster;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $Total_Users=Client::count();
        $Total_Orders=Ordermaster::count();
        $Total_Products=Product::count();
        $Total_Coupons=Coupons::count();

       return view('home',compact('Total_Users','Total_Products','Total_Coupons','Total_Orders'));
    }
}
