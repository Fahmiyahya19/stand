<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\App;

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
        $transactions = Transaction::paid();
        $totalTransaksiNow = $transactions->today()->select(DB::raw('SUM(total) AS totals'))->first()->totals;
        $jumlahTransaksiNow = $transactions->today()->count();
        $totalTransaksiNow = $transactions->today()->select(DB::raw('SUM(total) AS totals'))->first()->totals;
        $jumlahTransaksiNow = $transactions->today()->count();
        $totalTransaksiMonth = $transactions->month()->select(DB::raw('SUM(total) AS totals'))->first()->totals;
        $jumlahTransaksiMonth = $transactions->month()->count();
        $totalTransaksiMonth = $transactions->month()->select(DB::raw('SUM(total) AS totals'))->first()->totals;
        $jumlahTransaksiMonth = $transactions->month()->count();
        return view('home', compact('totalTransaksiNow', 'jumlahTransaksiNow', 'totalTransaksiMonth', 'jumlahTransaksiMonth'));
    }

    public function cetakNota($invoice){
        $model = Transaction::with(['products.product'])->paid()->findOrFail($invoice);
        return view('layouts.nota', compact('model'));
    }
}
