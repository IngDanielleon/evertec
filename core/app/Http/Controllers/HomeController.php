<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    function index()
    {
        return view('home');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    function list()
    {
        $orders = Orders::all();
        return view('admin', compact('orders'));
    }
}
