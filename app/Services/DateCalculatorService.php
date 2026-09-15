<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DateCalculatorService
{
    // Режим 1: расчет между датами
    public function calculate(Carbon $start, Carbon $end): array
    {
        $diff = $start->diff($end);

        $totalDays = $start->diffInDays($end);
        $totalHours = $start->diffInHours($end);
        $totalMinutes = $start->diffInMinutes($end);
        $totalSeconds = $start->diffInSeconds($end);

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

    // Режим 2: прибавить к дате
    public function addToDate(Carbon $baseDate, int $amount, string $unit): Carbon
    {
        return match ($unit) {
            'days' => $baseDate->copy()->addDays($amount),
            'weeks' => $baseDate->copy()->addWeeks($amount),
            'months' => $baseDate->copy()->addMonths($amount),
            'years' => $baseDate->copy()->addYears($amount),
        };
    }

    // Режим 3: Отнять от даты
    public function subtractFromDate(Carbon $baseDate, int $amount, string $unit): Carbon
    {
        return match ($unit) {
            'days' => $baseDate->copy()->subDays($amount),
            'weeks' => $baseDate->copy()->subWeeks($amount),
            'months' => $baseDate->copy()->subMonths($amount),
            'years' => $baseDate->copy()->subYears($amount),
        };
    }

    public function getRemaining(): array
    {
        $now = Carbon::now();

        return [
            'days_left_in_year' => (int) $now->diffInDays($now->copy()->endOfYear()),
            'days_left_in_month' => (int) $now->diffInDays($now->copy()->endOfMonth()),
            'days_left_in_week' => (int) $now->diffInDays($now->copy()->endOfWeek()),
        ];
    }
}