<?php

namespace App\Http\Controllers;

use App\Models\DateCalculation;
use App\Services\DateCalculatorService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DateCalculatorController extends Controller
{
    public function __construct(
        private DateCalculatorService $calculator
    ) {}

    public function index()
    {
        $recentCalculations = DateCalculation::latest()
            ->take(5)
            ->get();

        return view('calculator.index', compact('recentCalculations'));
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.required' => 'Пожалуйста, укажите начальную дату',
            'end_date.required' => 'Пожалуйста, укажите конечную дату',
            'start_date.before_or_equal' => 'Начальная дата должна быть раньше конечной',
            'end_date.after_or_equal' => 'Конечная дата должна быть позже начальной',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $calculation = $this->calculator->calculate($startDate, $endDate);

        DateCalculation::create([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_difference' => $calculation['total_days'],
            'user_ip' => $request->ip(),
        ]);

        $recentCalculations = DateCalculation::latest()->take(5)->get();

        return view('calculator.index', [
            'calculation' => $calculation,
            'recentCalculations' => $recentCalculations,
            'selectedStartDate' => $validated['start_date'],
            'selectedEndDate' => $validated['end_date'],
        ])->with('success', 'Расчет успешно выполнен!');
    }

    public function addToDate(Request $request)
    {
        $validated = $request->validate([
            'base_date' => 'required|date',
            'amount' => 'required|integer|min:1|max:10000',
            'unit' => 'required|in:days,weeks,months,years',
        ], [
            'base_date.required' => 'Укажите начальную дату',
            'amount.required' => 'Введите число',
            'amount.integer' => 'Число должно быть целым',
            'amount.min' => 'Число должно быть больше 0',
            'unit.required' => 'Выберите единицу измерения',
        ]);

        $baseDate = Carbon::parse($validated['base_date']);
        $amount = (int) $validated['amount'];
        $unit = $validated['unit'];

        // Логика расчета — в сервисе
        $resultDate = $this->calculator->addToDate($baseDate, $amount, $unit);

        // Разницу считаем тем же методом calculate()
        $calculation = $this->calculator->calculate($baseDate, $resultDate);

        DateCalculation::create([
            'start_date' => $baseDate,
            'end_date' => $resultDate,
            'days_difference' => $calculation['total_days'],
            'user_ip' => $request->ip(),
        ]);

        $recentCalculations = DateCalculation::latest()->take(5)->get();

        return view('calculator.index', [
            'calculation' => $calculation,
            'recentCalculations' => $recentCalculations,
            'selectedStartDate' => $validated['base_date'],
            'selectedEndDate' => $resultDate->format('Y-m-d'),
            'activeMode' => 'add',
            'addResult' => [
                'base_date' => $baseDate->format('d.m.Y'),
                'result_date' => $resultDate->format('d.m.Y'),
                'amount' => $amount,
                'unit' => $unit,
                'unit_label' => match ($unit) {
                    'days' => 'дней',
                    'weeks' => 'недель',
                    'months' => 'месяцев',
                    'years' => 'лет',
                },
            ],
        ])->with('success', 'Расчет успешно выполнен!');
    }

        //Фукнция - очистка истории расчета
    public function clearHistory()
        {
            DateCalculation::truncate();

            return redirect()
                ->route('calculator.index')
                ->with('success', 'История очищена');
        }


        //Функция - отнять от даты
    public function subtractFromDate(Request $request)
        {
            $validated = $request->validate([
                'base_date' => 'required|date',
                'amount' => 'required|integer|min:1|max:10000',
                'unit' => 'required|in:days,weeks,months,years',
            ], [
                'base_date.required' => 'Укажите начальную дату',
                'amount.required' => 'Введите число',
                'amount.integer' => 'Число должно быть целым',
                'amount.min' => 'Число должно быть больше 0',
                'unit.required' => 'Выберите единицу измерения',
            ]);

            $baseDate = Carbon::parse($validated['base_date']);
            $amount = (int) $validated['amount'];
            $unit = $validated['unit'];

            $resultDate = $this->calculator->subtractFromDate($baseDate, $amount, $unit);

            $calculation = $this->calculator->calculate($resultDate, $baseDate);

            DateCalculation::create([
                'start_date' => $resultDate,
                'end_date' => $baseDate,
                'days_difference' => $calculation['total_days'],
                'user_ip' => $request->ip(),
            ]);

            $recentCalculations = DateCalculation::latest()->take(5)->get();

            return view('calculator.index', [
                'calculation' => $calculation,
                'recentCalculations' => $recentCalculations,
                'selectedStartDate' => $validated['base_date'],
                'selectedEndDate' => $resultDate->format('Y-m-d'),
                'activeMode' => 'subtract',
                'addResult' => [
                    'base_date' => $baseDate->format('d.m.Y'),
                    'result_date' => $resultDate->format('d.m.Y'),
                    'amount' => $amount,
                    'unit' => $unit,
                    'unit_label' => match ($unit) {
                        'days' => 'дней',
                        'weeks' => 'недель',
                        'months' => 'месяцев',
                        'years' => 'лет',
                    },
                ],
            ])->with('success', 'Расчет успешно выполнен!');
        }
}