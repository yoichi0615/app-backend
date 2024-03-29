<?php

namespace App\Http\Controllers;

use App\Interfaces\IncomeRepositoryInterface;
use Illuminate\Support\Collection;
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
        $requestData = $request->all();
        $this->incomeRepo->saveIncome($requestData);
        return response(['result' => true]);
    }

    public function getMonthlyData(Request $request)
    {
        if ($request->date) {
            $targetDate = Carbon::create($request->date);
            $targetStartDate = $targetDate->startOfMonth()->startOfDay()->format('Y-m-d');
            $targetEndDate = $targetDate->endOfMonth()->format('Y-m-d');
        } else {
            $now = Carbon::now();
            $targetStartDate = $now->startOfMonth()->startOfDay()->format('Y-m-d');
            $targetEndDate = $now->endOfMonth()->endOfDay()->format('Y-m-d');
        }

        $data = $this->incomeRepo->getMonthlyData($targetStartDate, $targetEndDate);
        return response()->json($data);
    }

    public function getDailyTotalAmount()
    {
        $now = new Carbon();
        $startDate = $now->startOfMonth()->startOfDay()->format('Y-m-d');
        $endDate = $now->endOfMonth()->endOfDay()->format('Y-m-d');
        $data = $this->incomeRepo->getDailyTotalAmount($startDate, $endDate);

        return response()->json($data);
    }

    public function getDailyAmount()
    {
        $now = new Carbon();
        $startDate = $now->startOfMonth()->startOfDay()->format('Y-m-d');
        $endDate = $now->endOfMonth()->endOfDay()->format('Y-m-d');
        $data = $this->incomeRepo->getDailyTotalAmount($startDate, $endDate);

        $dailyAmountData = $data->mapWithKeys(function ($_data) {
            $day = Carbon::parse($_data->date)->format('j');
            return [$day => $_data->total_amount];
        });

        $monthDays = intval(Carbon::parse($endDate)->format('j'));

        $amountData = Collection::times($monthDays, function ($index) use ($dailyAmountData) {
            if (isset($dailyAmountData[$index])) {
                return intval($dailyAmountData[$index]);
            }
            return 0;
        });

        return response()->json($amountData);
    }
}
