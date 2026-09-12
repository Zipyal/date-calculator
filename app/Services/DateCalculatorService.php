<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DateCalculatorService
{
    public function calculate(Carbon $start, Carbon $end): array
    {
        $totalDays = $start->diffInDays($end);
        $totalHours = $start->diffInHours($end);
        $totalMinutes = $start->diffInMinutes($end);
        $totalSeconds = $start->diffInSeconds($end);

        // Создаем период для подсчета рабочих дней
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