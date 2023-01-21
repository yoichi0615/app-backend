<?php

namespace Tests\Unit\Income;

use App\Models\Income;
use Tests\TestCase;

class IncomeControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testStore()
    {
        $income = Income::factory()->make();
        $response = $this->post(route('store'), $income->toArray());
        $response->assertStatus(200);
        $this->assertDatabaseHas('incomes', $income->toArray());
    }
}
