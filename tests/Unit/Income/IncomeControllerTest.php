<?php

namespace Tests\Unit\Income;

use App\Models\Income;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;

class IncomeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

    /**
     *  @dataProvider provideAdditionTestParams
     */
    public function testGetMonthlyData($request)
    {
        Collection::times(10, function() {
            Income::factory()->create([
                'date' => $this->faker->dateTimeBetween($startDate = '-2 week', $endDate = '+2 week'),
            ]);
        });

        $now = Carbon::now();
        $response = $this->get(route('get', $request));
        $expected = Income::whereBetween('date', [$now->startOfMonth()->startOfDay()->format('Y-m-d'), $now->endOfMonth()->endOfDay()->format('Y-m-d')])->get();
        $response->assertStatus(200)
            ->assertExactJson($expected->toArray());
    }


    public function provideAdditionTestParams()
    {
        $now = Carbon::now();
        return [
            //テスト1週目
            [
                [
                    'date' => $now->format('Y-m-d')
                ],
            ],
            //テスト2週目
            [
                [],
            ],
        ];
    }
}
