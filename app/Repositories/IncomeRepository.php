<?php

namespace App\Repositories;

use App\Interfaces\IncomeRepositoryInterface;
use App\Models\Income;

class IncomeRepository implements IncomeRepositoryInterface
{
    public function store($income)
    {
        Income::create($income);
    }

    public function getDailyTotalAmount($startDate, $endDate)
    {
        $result = \DB::table('incomes')
        ->select('user_id', 'date')
        ->where('user_id', 1)
        ->whereBetween('date', [$startDate, $endDate])
        ->selectRaw('SUM(amount) AS total_amount')
        ->groupBy('date')
        ->get();

        return $result;
    }
}
