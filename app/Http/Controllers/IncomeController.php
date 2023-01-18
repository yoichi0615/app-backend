<?php

namespace App\Http\Controllers;

use App\Interfaces\IncomeRepositoryInterface;
use App\Models\Income;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IncomeController extends Controller
{
    private IncomeRepositoryInterface $incomeRepo;

    public function __construct(IncomeRepositoryInterface $incomeRepository)
    {
        $this->incomeRepo = $incomeRepository;
    }

    public function store(Request $request)
    {
        $income = $request->all();
        $this->incomeRepo->store($income);
        return response(['result' => true]);
    }

    public function getDailyTotalAmount()
    {
        $now = new Carbon();
        $startDate = $now->startOfMonth()->startOfDay()->format('Y-m-d');
        $endDate = $now->endOfMonth()->endOfDay()->format('Y-m-d');
        $result = $this->incomeRepo->getDailyTotalAmount($startDate, $endDate);

        return response()->json($result);
    }
}
