<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function store(Request $request)
    {
        Income::create($request->all());
        return response(['result' => true]);
    }

    public function getDailyTotalAmount(Request $request)
    {
        $result = \DB::table('incomes')
            ->select('user_id', 'date')
            ->where('user_id', 1)
            ->selectRaw('SUM(amount) AS total_amount')
            ->groupBy('date')
            ->get();

        return response()->json($result);
    }
}
