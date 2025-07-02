<?php

namespace App\UseCases\Admin\Review;

use App\Models\Review;
use Inertia\Inertia;

class GetReviewReportUseCase
{
    public function use()
    {
        try {
            $paginator = Review::getDataForIndex()->paginate(20);

            return Inertia::render('Review/Index', [
                'paginator' => $paginator,
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
