<?php

namespace App\Http\Controllers;

use App\Exports\GoodsSupplyExport;
use App\Exports\ReviewExport;
use App\Http\Requests\ReviewApiRequest;
use App\Jobs\GetReviewsFromGoogleMaps;
use App\Jobs\GetReviewsFromTwoGis;
use App\Jobs\GetReviewsFromYandexVendor;
use App\Models\Review;
use App\UseCases\Admin\Review\GetReviewReportUseCase;
use App\UseCases\Admin\Review\GetReviewStatsUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsApiUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsGoogleMapsUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsTwoGisUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReviewController extends Controller
{
    public function __construct(
        protected NotifyAboutNewReviewsTwoGisUseCase $notifyAboutNewReviewsTwoGisUseCase,
        protected NotifyAboutNewReviewsApiUseCase $notifyAboutNewReviewsApiUseCase,
        protected GetReviewReportUseCase $useCase,
        protected GetReviewStatsUseCase $statsUseCase,
    ) {
    }

    public function findAndNotify()
    {
        GetReviewsFromYandexVendor::dispatch();
        GetReviewsFromTwoGis::dispatch();
//        GetReviewsFromGoogleMaps::dispatch();
    }

    public function index(Request $request)
    {
        return $this->useCase->use($request->query());
    }

    public function create(ReviewApiRequest $request)
    {
        $this->notifyAboutNewReviewsApiUseCase->use($request->validated());
    }

    public function export(Request $request)
    {
        $prePagination = Review::getDataForIndex();
        $filters = collect($request->query());

        if ($filters->has('brunches')) {
            $filters['brunches'] = collect($filters['brunches'])
                ->map(fn($item) => $item['value'])
                ->filter()
                ->values();
        }

        if ($filters->has('platform')) {
            $filters['platform'] = collect($filters['platform'])
                ->map(fn($item) => $item['value'])
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
        ->get();

        return Excel::download(new GoodsSupplyExport($paginator), "export.xlsx");
    }

    public function stats(Request $request)
    {
        return $this->statsUseCase->use($request->query());
    }
}
