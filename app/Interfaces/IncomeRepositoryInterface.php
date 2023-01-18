<?php

namespace App\Interfaces;

interface IncomeRepositoryInterface 
{
    public function store($income);
    public function getDailyTotalAmount($startDate, $endDate);
}
