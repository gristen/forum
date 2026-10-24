<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Message;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
/*TODO: собрать полный массив $stats куда все соберется*/
    public function getDashboardData(?string $period = null): array
    {
        $period = $period ?? 'month';

        /*growth - рост*/

        $growth = [
            'users' => $this->getGrowth(User::class, $period),
            'topics' => $this->getGrowth(Topic::class, $period),
            'messages' => $this->getGrowth(Message::class, $period),
        ];

        $totals = [
            'users' => User::count(),
            'topics' => Topic::count(),
            'messages' => Message::count(),
            'categories' => Category::count(),
        ];

        return [
            'totalUsers' => $totals['users'],
            'totalTopics' => $totals['topics'],
            'totalMessages' => $totals['messages'],
            'totalCategories' => $totals['categories'],

            'stats' => [
                'users' => [
                    ...config('adminStats.users'),
                    'total' => $totals['users'],
                    'growth' => $growth['users'],
                ],

                'topics' => [
                    ...config('adminStats.topics'),
                    'total' => $totals['topics'],
                    'growth' => $growth['topics'],
                ],

                'messages' => [
                    ...config('adminStats.messages'),
                    'total' => $totals['messages'],
                    'growth' => $growth['messages'],
                ],

                'categories' => [
                    ...config('adminStats.categories'),
                    'total' => $totals['categories'],
                ],
            ],
        ];
    }

    public function countForPeriod(string $model, string $period)
    {
        $now = Carbon::now();
        $dates = match ($period) {
            "year" => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
            ],
            "month" => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ],
            "week" => [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
            ],
            "day" => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            default => 0
        };

        $result = $model::whereBetween('created_at', $dates)->count();
        return $result;
    }

    public function getMonthlyRegistrations():array
    {

        $currentYear = Carbon::now()->year;

        $months = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
        ];

        $stats = [];

        foreach ($months as $monthNumber => $monthName) {
            $count = User::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $monthNumber)
                ->count();


            $stats[$monthName] = $count;
        }
        return $stats;
    }

    public function getGrowth(string $model, ?string $period = 'month'): array
    {
        $now = now();

        [$currentStart, $currentEnd, $prevStart, $prevEnd, $field] = match ($period) {
            'month' => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
                "за месяц"
            ],
            'week' => [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
                $now->copy()->subWeek()->startOfWeek(),
                $now->copy()->subWeek()->endOfWeek(),
            ],
            default => throw new \Exception('Unsupported period'),
        };

        $current = $model::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $previous = $model::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        if ($previous == 0) {
            $percent = $current > 0 ? 100 : 0;
        } else {
            $percent = (($current - $previous) / $previous) * 100;
        }

        return [
            'current' => $current,
            'previous' => $previous,
            'percent' => round($percent, 1),
            'field' => $field,
        ];
    }
}
