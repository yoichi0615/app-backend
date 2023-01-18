<?php

namespace App\Interfaces;

interface IncomeRepositoryInterface 
{
    public function saveIncome($income);
    public function getDailyTotalAmount($startDate, $endDate);
}
