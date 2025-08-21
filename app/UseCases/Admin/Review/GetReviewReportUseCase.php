<?php

namespace App\UseCases\Admin\Review;

use App\Models\Review;
use Inertia\Inertia;

class GetReviewReportUseCase
{
    public function use(array $data)
    {
        try {
            $prePagination =Review::getDataForIndex();

            if (key_exists('column', $data) && key_exists('orderBy', $data)) {
                $prePagination = $prePagination->orderBy($data['column'], $data['orderBy']);
            }

            $paginator = $prePagination->paginate(20);

            return Inertia::render('Review/Index', [
                'paginator' => $paginator,
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
