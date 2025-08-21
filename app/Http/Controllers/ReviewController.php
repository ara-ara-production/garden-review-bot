<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewApiRequest;
use App\Jobs\GetReviewsFromTwoGis;
use App\UseCases\Admin\Review\GetReviewReportUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsApiUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsTwoGisUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        protected NotifyAboutNewReviewsTwoGisUseCase $notifyAboutNewReviewsTwoGisUseCase,
        protected NotifyAboutNewReviewsApiUseCase $notifyAboutNewReviewsApiUseCase,
        protected GetReviewReportUseCase $useCase,
    ) {
    }

    public function findAndNotify()
    {
        $this->notifyAboutNewReviewsTwoGisUseCase->use();
//        GetReviewsFromTwoGis::dispatch();
    }

    public function index(Request $request)
    {
        return $this->useCase->use($request->all());
    }

    public function create(Request $request) {
        $this->notifyAboutNewReviewsApiUseCase->use($request->all());
    }
}
