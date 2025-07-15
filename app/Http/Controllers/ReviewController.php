<?php

namespace App\Http\Controllers;

use App\Jobs\GetReviewsFromTwoGis;
use App\UseCases\Admin\Review\GetReviewReportUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsTwoGisUseCase;

class ReviewController extends Controller
{
    public function __construct(
        protected NotifyAboutNewReviewsTwoGisUseCase $notifyAboutNewReviewsTwoGisUseCase,
        protected GetReviewReportUseCase $useCase,
    ) {
    }

    public function findAndNotify()
    {
//        $this->notifyAboutNewReviewsTwoGisUseCase->use();
        GetReviewsFromTwoGis::dispatch();
    }

    public function index()
    {
        return $this->useCase->use();
    }
}
