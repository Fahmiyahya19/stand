<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalTransaksiNow = Transaction::today()->select(DB::raw('SUM(total) AS totals'))->first()->totals;
        $jumlahTransaksiNow = Transaction::today()->count();
        $totalTransaksiNow = Transaction::today()->select(DB::raw('SUM(total) AS totals'))->first()->totals;
        $jumlahTransaksiNow = Transaction::today()->count();
        $totalTransaksiMonth = Transaction::month()->select(DB::raw('SUM(total) AS totals'))->first()->totals;
        $jumlahTransaksiMonth = Transaction::month()->count();
        $totalTransaksiMonth = Transaction::month()->select(DB::raw('SUM(total) AS totals'))->first()->totals;
        $jumlahTransaksiMonth = Transaction::month()->count();
        return view('home', compact('totalTransaksiNow', 'jumlahTransaksiNow', 'totalTransaksiMonth', 'jumlahTransaksiMonth'));
    }
}
