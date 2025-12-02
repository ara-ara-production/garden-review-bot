<?php

namespace App\UseCases\Admin\Review;

use App\Models\Brunch;
use App\Models\Review;
use Inertia\Inertia;

class GetReviewReportUseCase
{
    public function use(array $data)
    {
        try {
            $prePagination = Review::getDataForIndex();
            $filters = collect($data);

            if ($filters->has('brunches')) {
                $filters['brunches'] = collect($filters['brunches'])
                    ->map(fn($item) => key_exists('value', $item) ? $item['value'] : null)
                    ->filter()
                    ->values();
            }

            if ($filters->has('platform')) {
                $filters['platform'] = collect($filters['platform'])
                    ->map(fn($item) => key_exists('value', $item) ? $item['value'] : null)
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
                ->when(
                    $filters->has('orderBy') && $filters['orderBy'] && $filters->has('sort') && $filters['sort'],
                    fn() => $prePagination->orderBy($filters['sort'], $filters['orderBy']),
                    fn() => $prePagination->orderBy('posted_at', 'desc')
                )
                ->when($filters->has('without_reply') && $filters['without_reply'] === 'true', fn() => $prePagination->where(fn ($query) => $query->whereNull('final_answer')->OrWhere('final_answer', '=', '')) )
                ->paginate(20);

            return Inertia::render('Review/Index', [
                'paginator' => $paginator,
                'brunches' => Brunch::dataForFilter()->get(),
                'filtersAndSort' => $filters,
            ]);
        } catch
        (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
