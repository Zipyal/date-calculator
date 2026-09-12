<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DateCalculatorService
{
    public function calculate(Carbon $start, Carbon $end): array
    {
        $diff = $start->diff($end);

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
            'total_days' => (int) $totalDays,
            'weeks' => (int) floor($totalDays / 7),
            'remaining_days' => (int) ($totalDays % 7),
            'months' => $diff->m + ($diff->y * 12),
            'years' => $diff->y,
            'hours' => (int) $totalHours,
            'minutes' => (int) $totalMinutes,
            'seconds' => (int) $totalSeconds,
            'weekdays' => $weekdays,
            'weekends' => $weekends,
            'start_date_formatted' => $start->format('d.m.Y'),
            'end_date_formatted' => $end->format('d.m.Y'),
        ];
    }
}