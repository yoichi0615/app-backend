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

    const USER_ID = 1;

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
        // Collection::times(15, function () {
            Income::factory(5)->create([
                'date' => $this->faker->dateTimeBetween($startDate = '2022-12-01', $endDate = '2023-06-11'),
            ]);
        // });

        $request = isset($request['date']) ? $request : null;
        if ($request) {
            $targetDate = Carbon::create($request['date']);
            $targetStartDate = $targetDate->startOfMonth()->startOfDay()->format('Y-m-d');
            $targetEndDate = $targetDate->endOfMonth()->format('Y-m-d');
        } else {
            $now = Carbon::now();
            $targetStartDate = $now->startOfMonth()->startOfDay()->format('Y-m-d');
            $targetEndDate = $now->endOfMonth()->endOfDay()->format('Y-m-d');
        }

        $response = $this->get(route('get', $request));
        $expected = Income::whereBetween('date', [$targetStartDate, $targetEndDate])->get();

        $response->assertStatus(200)
            ->assertExactJson($expected->toArray());
    }

    public function testGetDailyAmount()
    {
        Collection::times(10, function () {
            Income::factory()->create([
                'date' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = 'now'),
                'user_id' => self::USER_ID
            ]);
        });

        $now = new Carbon();
        $startDate = $now->startOfMonth()->startOfDay()->format('Y-m-d');
        $endDate = $now->endOfMonth()->endOfDay()->format('Y-m-d');
        $data = Income::select('user_id', 'date')
            ->where('user_id', self::USER_ID)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('SUM(amount) AS total_amount')
            ->groupBy('date')
            ->get();

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

        $response = $this->get(route('get_daily_amount'));
        $response->assertStatus(200)
            ->assertExactJson($amountData->toArray());
    }

    public function provideAdditionTestParams()
    {
        return [
            [
                [
                    'date' => Carbon::parse('2022-11-11')->format('Y-m-d')
                ]
            ],
            [
                [
                    'date' => Carbon::parse('2023-03-11')->format('Y-m-d')
                ]
            ],
            [
                [
                    'date' => Carbon::parse('2021-11-12')->format('Y-m-d')
                ]
            ],
            [
                [],
            ],
        ];
    }
}
