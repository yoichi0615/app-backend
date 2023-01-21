<?php

namespace App\Interfaces;

interface IncomeRepositoryInterface
{
    public function saveIncome($requestData);
    public function getMonthlyData($targetStartDate, $targetEndDate);
    public function getDailyTotalAmount($startDate, $endDate);
}
