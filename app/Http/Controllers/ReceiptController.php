<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    //
    public function index(Request $request)
    {
        $yearRanges = Salary::groupBy('year')
            ->pluck('year')->toArray();

        $latestRow = Salary::limit(1)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get()->first();

        $year = null;
        $month = null;

        $year = $request->has('year') ? $request->year : ($latestRow ? $latestRow->year : Carbon::now()->year);
        $month = $request->has('month') ? $request->month : ($latestRow ? $latestRow->month : Carbon::now()->month);

        $salaries = Salary::select(['salaries.id as id', 'salaries.a1 as name', 'salaries.a2 as year',
            'salaries.a3 as month'])
            ->where('year', $year)
            ->where('month', $month)
            ->get();

        $readSalaries = Receipt::where('year', $year)
            ->where('month', $month)
            ->get();

        $readList = $readSalaries->pluck('salary_id')->toArray();

        return view('receipt.index', compact('yearRanges', 'salaries', 'year', 'month',
            'readSalaries', 'readList'));
    }
}
