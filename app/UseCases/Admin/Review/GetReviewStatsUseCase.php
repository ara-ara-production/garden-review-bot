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
        $prePagination = Review::selectRaw('brunches.name, score, count(*) as count')
            ->leftJoin('brunches', 'brunches.id', '=', 'reviews.brunch_id')
            ->groupBy('brunches.name', 'score');

        $twoGisCurrentRate = Review::query()
            ->selectRaw('DISTINCT ON (reviews.brunch_id) brunches.name as brunch_name, reviews.total_brunch_rate as total_brunch_rate')
            ->leftJoin('brunches', 'brunches.id', '=', 'reviews.brunch_id')
            ->where('resource', '2Гис')
            ->orderBy('brunch_id', 'desc')
            ->get()
            ->toArray();

        $twoGisCurrentRate = collect($twoGisCurrentRate);

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

        $paginator = $prePagination
            ->when(
                $filters->has('date') && is_array($filters['date']),
                fn() => $prePagination->whereBetween('posted_at', $filters['date'])
            )
            ->when(
                $filters->has('brunches'),
                fn() => $prePagination->whereIn('brunch_id', $filters['brunches'])
            )
            ->when(
                $filters->has('platform'),
                fn() => $prePagination->whereIn('resource', $filters['platform'])
            )
            ->get()
            ->toArray();


        $readyDataChart = [];
        $readyDataPercentRate = [
            5 => 0,
            4 => 0,
            '1-3' => 0
        ];
        $readyBrunchRate = [];
        collect($paginator)
            ->each(function ($item) use (&$readyDataChart) {
                if (!key_exists($item['name'], $readyDataChart)) {
                    $readyDataChart[$item['name']] = [];
                }

                $readyDataChart[$item['name']]['name'] = $item['name'];
                if ($item['score'] < 4) {
                    if (!key_exists('1-3', $readyDataChart[$item['name']])) {
                        $readyDataChart[$item['name']]['1-3'] = 0;
                    }

                    $readyDataChart[$item['name']]['1-3'] += $item['count'];
                } else {
                    $readyDataChart[$item['name']][$item['score']] = $item['count'];
                }
            })
            ->each(function ($item) use (&$readyDataPercentRate) {
                if ($item['score'] < 4) {
                    if (!key_exists('1-3', $readyDataPercentRate)) {
                        $readyDataPercentRate['1-3'] = 0;
                    }

                    $readyDataPercentRate['1-3'] += $item['count'];
                } else {
                    if (!key_exists($item['score'], $readyDataPercentRate)) {
                        $readyDataPercentRate[$item['score']] = 0;
                    }

                    $readyDataPercentRate[$item['score']] += $item['count'];
                }
            })
            ->groupBy('name')
            ->each(function ($item) use (&$readyBrunchRate, $twoGisCurrentRate) {
                $sum = 0;
                $count = 0;
                collect($item)->each(function ($item) use (&$sum, &$count) {
                    $sum += $item['score'] * $item['count'];
                    $count += $item['count'];
                });
                $readyBrunchRate[] = [
                    'name' => $item[0]['name'],
                    'avg' => round($sum / $count, 1),
                    'twoGis' => $twoGisCurrentRate
                        ->where('brunch_name', $item[0]['name'])
                        ->first()['total_brunch_rate'],
                ];
            });

        $readyBrunchRate = collect($readyBrunchRate)
            ->sortByDesc('avg')
            ->values()
            ->toArray();


        $readyDataPercentRate = collect($readyDataPercentRate);

        $sum = $readyDataPercentRate->sum();

        $readyDataPercentRate = $readyDataPercentRate->map(function ($item) use ($sum) {
            return [
                'count' => $item,
                'percent' => round($item / $sum * 100)
            ];
        });

        $readyDataChart = array_values($readyDataChart);

        return Inertia::render('Review/Stats', [
            'statsDataChart' => $readyDataChart,
            'statsDataPercent' => $readyDataPercentRate,
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
