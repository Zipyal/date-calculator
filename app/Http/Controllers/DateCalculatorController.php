<?php

namespace App\Http\Controllers;

use App\Models\DateCalculation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DateCalculatorController extends Controller
{
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
        
        $calculation = $this->calculateDateDifference($startDate, $endDate);
        
        // Сохранении истории
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

    private function calculateDateDifference(Carbon $start, Carbon $end)
    {
        $totalDays = $start->diffInDays($end);
        $totalHours = $start->diffInHours($end);
        $totalMinutes = $start->diffInMinutes($end);
        $totalSeconds = $start->diffInSeconds($end);
        
        // Период для подсчета рабочих дней
        $period = CarbonPeriod::create($start, $end);
        
        $weekdays = 0;
        $weekends = 0;
        
        foreach ($period as $date) {
            if ($date->isWeekend()) {
                $weekends++;
            } else {
                $weekdays++;
            }
        }
        
        return [
            'total_days' => $totalDays,
            'weeks' => floor($totalDays / 7),
            'remaining_days' => $totalDays % 7,
            'months' => $start->diffInMonths($end),
            'years' => $start->diffInYears($end),
            'hours' => $totalHours,
            'minutes' => $totalMinutes,
            'seconds' => $totalSeconds,
            'weekdays' => $weekdays,
            'weekends' => $weekends,
            'start_date_formatted' => $start->format('d.m.Y'),
            'end_date_formatted' => $end->format('d.m.Y'),
        ];
    }
}
