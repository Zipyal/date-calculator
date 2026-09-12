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

        // Одна строка вместо вызова приватного метода
        $calculation = $this->calculator->calculate($startDate, $endDate);

        DateCalculation::create([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_difference' => $calculation['total_days'],
            'user_ip' => $request->ip(),
        ]);

        $recentCalculations = DateCalculation::latest()
            ->take(5)
            ->get();

        return view('calculator.index', compact('calculation', 'recentCalculations'))
            ->with('success', 'Расчет успешно выполнен!');
    }
}