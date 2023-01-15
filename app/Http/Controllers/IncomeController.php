<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IncomeController extends Controller
{
    public function store(Request $request)
    {
        Income::create($request->all());
        return response(['result' => true]);
    }

    public function getDailyTotalAmount(Request $request)
    {
        $now = new Carbon();
        $startDate = $now->startOfMonth()->startOfDay()->format('Y-m-d');
        $endDate = $now->endOfMonth()->endOfDay()->format('Y-m-d');
        $result = \DB::table('incomes')
            ->select('user_id', 'date')
            ->where('user_id', 1)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('SUM(amount) AS total_amount')
            ->groupBy('date')
            ->get();

        return response()->json($result);
    }
}
