<?php

namespace App\Services;

use App\Dto\Telegram\Entity\ReviewDto;
use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Dto\Telegram\Entity\TelegramMessageReviewDto;
use App\Exeptions\Api\NullReviewsException;
use App\Models\Review;
use DateTime;
use Illuminate\Support\Collection;

class ReviewService
{
    /**
     * @throws NullReviewsException если был передан пустой массив отзывов
     */
    public function removeExistedReviews(null|Collection $reviewDtos): ?Collection
    {
        if (empty($reviewDtos)) {
            throw new NullReviewsException();
        }

        /** @var Collection $reviews */
        $reviews = Review::select('key')
            ->whereIn('key', $reviewDtos->pluck('id'))
            ->whereIn('posted_at', $reviewDtos->pluck('time'))
            ->get();

        return $reviewDtos->map(function (ReviewInfoDto $dto) use ($reviews) {
            if ($reviews->where('key', '=', $dto->id)->isNotEmpty()) {
                $dto->updateOnlySmmMessage = true;
            }
            return $dto;
        });
    }

    public function storeReviews(Collection $reviewDtos): Collection
    {
        return $reviewDtos->each(function (ReviewInfoDto $reviewDto) {
            $review = Review::updateOrCreate(
                ['key' => $reviewDto->id],
                [
                    'review' => $reviewDto->resource,
                    'resource' => $reviewDto->resource,
                    'posted_at' => $reviewDto->time,
                    'brunch_id' => $reviewDto->branchDto?->id,
                    'score' => $reviewDto->rating,
                    'comment' => $reviewDto->text,
                    'sender' => $reviewDto->sender,
                    'link' => $reviewDto->link,
                    'final_answer' => $reviewDto->finalAnswer,
                    'is_edited' => $reviewDto->isEdited,
                    'is_on_check' => $reviewDto->isOnCHeck,
                    'total_brunch_rate' => $reviewDto->totalsRate,
                ]
            );

            $review->save();

            $reviewDto->dbId = $review->id;
        });
    }
}
