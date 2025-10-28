<?php

namespace App\UseCases\Admin\Review;

use App\Models\Brunch;
use App\Models\Review;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class GetReviewStatsUseCase
{
    public function use(array $data)
    {
//        try {
        $rawDataQuerySelect = Review::selectRaw('brunches.name, score, count(*) as count')
            ->leftJoin('brunches', 'brunches.id', '=', 'reviews.brunch_id')
            ->groupBy('brunches.name', 'score');

        $filters = collect($data);

        if ($filters->has('brunches')) {
            $filters['brunches'] = collect($filters['brunches'])
                ->map(fn($item) => key_exists('value', $item) ? $item['value'] : null)
                ->filter()
                ->values();
        }

        if ($filters->has('platform')) {
            $filters['platform'] = collect($filters['platform'])
                ->map(fn($item) => key_exists('value', $item) ? array_pop($item) : null)
                ->filter()
                ->values();
        }

        $rawData = $rawDataQuerySelect
            ->when(
                $filters->has('date') && is_array($filters['date']),
                fn() => $rawDataQuerySelect->whereBetween('posted_at', $filters['date'])
            )
            ->when(
                $filters->has('brunches'),
                fn() => $rawDataQuerySelect->whereIn('brunch_id', $filters['brunches'])
            )
            ->when(
                $filters->has('platform'),
                fn() => $rawDataQuerySelect->whereIn('resource', $filters['platform'])
            )
            ->get()
            ->toArray();

        $rawData = collect($rawData);

        $total = $rawData->sum('count');

        $brunches = $rawData
            ->pluck('name')
            ->unique()
            ->values();

        $dataForBarChar = $brunches->map(function ($item) use ($rawData, $total) {
            $bad = $rawData->where('name', $item)->where('score', '<', 4)->sum('count');
            $good = $rawData->where('name', $item)->where('score', 4)->sum('count');
            $best = $rawData->where('name', $item)->where('score', 5)->sum('count');

            $total = $best + $bad + $good;

            return [
                'name' => $item,

                'bad' => $bad,
                'good' => $good,
                'best' => $best,

                'bad_p' => round(($bad / $total) * 100),
                'good_p' => round(($good / $total) * 100),
                'best_p' => round(($best / $total) * 100),

                'bad_title' => $bad ? "{$bad} (" . round(($bad / $total) * 100) . '%)' : null,
                'good_title' => $good ? "{$good} (" . round(($good / $total) * 100) . '%)' : null,
                'best_title' => $best ? "{$best} (" . round(($best / $total) * 100) . '%)' : null,
            ];
        })
            ->sortByDesc('best_p')
            ->values()
            ->toArray();

        $dataForPieChart = [
            [
                'name' => 'Положительных',
                'value' => $rawData->where('score', 5)->sum('count'),
                'percent' => round(($rawData->where('score', 5)->sum('count')) / $total * 100),
                'color' => '#4fd69c'
            ],
            [
                'name' => 'Нейтральных',
                'value' => $rawData->where('score', 4)->sum('count'),
                'percent' => round(($rawData->where('score', 4)->sum('count')) / $total * 100),
                'color' => '#FFC107',
            ],
            [
                'name' => 'Отрицательных',
                'value' => $rawData->where('score', '<', 4)->sum('count'),
                'percent' => round(($rawData->where('score', '<', 4)->sum('count')) / $total * 100),
                'color' => '#f75676',
            ],
        ];

        $twoGisCurrentRate = Review::query()
            ->selectRaw(
                'DISTINCT ON (reviews.brunch_id) brunches.name as brunch_name, reviews.total_brunch_rate as total_brunch_rate'
            )
            ->leftJoin('brunches', 'brunches.id', '=', 'reviews.brunch_id')
            ->when(
                $filters->has('date') && is_array($filters['date']),
                fn() => $rawDataQuerySelect->whereBetween('posted_at', $filters['date'])
            )
            ->where('resource', '2Гис')
            ->orderBy('brunch_id', 'desc')
            ->get();

        $yandexEdaCurrentRate = Review::query()
            ->selectRaw('brunches.name as brunch_name, avg(score) as avg_score')
            ->leftJoin('brunches', 'brunches.id', '=', 'reviews.brunch_id')
            ->when(
                $filters->has('date') && is_array($filters['date']),
                fn() => $rawDataQuerySelect->whereBetween('posted_at', $filters['date'])
            )
            ->where('resource', 'Яндекс.Еда')
            ->groupBy('brunches.name')
            ->get();

        $readyBrunchRate = $brunches
            ->map(function ($item) use ($rawData, $yandexEdaCurrentRate, $twoGisCurrentRate) {
                $brunchData = $rawData->where('name', $item);

                $topParameter = $brunchData
                    ->map(
                        fn($item) => (key_exists('score', $item) && key_exists('count', $item))
                            ? $item['score'] * $item['count']
                            : 0
                    )
                    ->sum();

                $bottomParameter = $brunchData->sum('count');

                return [
                    'name' => $item,
                    'selectedDateRange' => round(($topParameter / $bottomParameter), 2),
                    'twoGis' => $twoGisCurrentRate
                        ->where('brunch_name', $item)
                        ->first()['total_brunch_rate'],
                    'yEda' => round(
                        collect(
                            $yandexEdaCurrentRate
                                ->where('brunch_name', $item)
                                ->first()
                        )->get('avg_score'),
                        1
                    ),
                ];
            })
            ->sortByDesc('selectedDateRange')
            ->values()
            ->toArray();

        return Inertia::render('Review/Stats', [
            'barChartData' => $dataForBarChar,
            'pieChartData' => $dataForPieChart,
            'statsBrunchRate' => $readyBrunchRate,
            'brunches' => Brunch::dataForFilter()->get(),
            'filtersAndSort' => $filters,
        ]);
//        } catch
//        (\Throwable $exception) {
//            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
//        }
    }
}
