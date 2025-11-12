<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Models\Brunch;
use App\Models\Review;
use App\Services\ReviewService;
use DateInterval;
use DateTime;
use Illuminate\Support\Collection;

class ReviewInfoDtoFactory
{
    public function __construct(
        protected BranchDtoFactory $branchDtoFactory
    ) {
    }

    public function fromYandexVendorArray(array $data): Collection
    {
        $reviews = collect();
        collect($data)
            ->each(function ($item) use (&$reviews) {
                $reviews->push($this->fromYandexVendor($item));
            });

        return $reviews->flatten();
    }

    public function fromYandexVendor(array $data): ReviewInfoDto
    {
        if (Brunch::where('yandex_vendor_id', $data['order']['place_id'])->exists()) {
            $branch = Brunch::where('yandex_vendor_id', $data['order']['place_id'])->first();
        } else {
            $branchFaked = new Brunch;
            $branchFaked->yandex_vendor_id = $data['order']['place_id'];
            $branchFaked->name = $data['order']['place_id'];
            $branch = $branchFaked;
        }

        $text = '';

        collect($data['order_feedback']['predefined_comments'])->each(function ($item) use (&$text) {
            $text .= "{$item['comment']}, ";
        });
         $text = rtrim($text, ' ,');
         $text = $text !== '' ? "[{$text}]\n" : '';

         $text .= key_exists('comment', $data['order_feedback']) ? $data['order_feedback']['comment'] : '';


        return new ReviewInfoDto(
            id: 'y-' . $data['order_feedback']['id'],
            text: $text,
            rating: $data['order_feedback']['rating'],
            sender: $data['order']['eater_name'],
            time: $this->parseDate($data['order_feedback']['feedback_filled_at']),
            resource: 'Яндекс.Еда',
            totalsRate: '-',
            branchDto: BranchDtoFactory::create($branch),
            extraData: mb_substr(
                $data['order']['order_nr'],
                strlen($data['order']['order_nr']) - 4,
                4
            )
        );
    }

    public function withMeta(array $data): Collection
    {
        $reviews = collect();
        collect($data)->each(function ($item) use (&$reviews) {
            $reviews->push(
                collect($item['reviews'])
                    ->map(function ($review) use ($item) {
                        $review['totalRate'] = $item['meta']['branch_rating'];

                        return $this->fromTwoGis($review);
                    })
            );
        });

        return $reviews->flatten();
    }

    public function fromTwoGis(array $data): ReviewInfoDto
    {
        if (Brunch::where('two_gis_id', $data['object']['id'])->exists()) {
            $branch = Brunch::where('two_gis_id', $data['object']['id'])->first();
        } else {
            $branchFaked = new Brunch;
            $branchFaked->two_gis_id = $data['object']['id'];
            $branchFaked->name = $data['object']['id'];
            $branch = $branchFaked;
        }

        $photosUrls = null;
        if (!empty($data['photos'])) {
            if (count($data['photos']) > 10) {
                $data['photos'] = array_slice($data['photos'], 0, 10);
            }

            foreach ($data['photos'] as $photo) {
                $photosUrls[] = [
                    'media' => $photo['preview_urls']['url'],
                    'type' => 'photo',
                ];
            }
        }

        return new ReviewInfoDto(
            id: $data['id'],
            text: $data['text'],
            rating: $data['rating'],
            sender: $data['user']['name'],
            time: $this->parseDate($data['date_edited'] ?? $data['date_created']),
            resource: '2Гис',
            totalsRate: $data['totalRate'],
            finalAnswer: $data['official_answer'] !== null ? $data['official_answer']['text'] : '',
            answerDate: $data['official_answer'] !== null ? $this->parseDate(
                $data['official_answer']['date_created']
            ) : null,
            isOnCHeck: $data['is_hidden'],
            link: "https://2gis.ru/reviews/{$data['object']['id']}/review/{$data['id']}",
            photos: $photosUrls,
            isEdited: $data['date_edited'] != null,
            branchDto: BranchDtoFactory::create($branch),
        );
    }

    public function fromTwoGisCollection(array $reviews): Collection
    {
        return collect($reviews)->map(
            fn($review) => $this->fromTwoGis($review)
        );
    }

    public function fromEntity(Review $review): ReviewInfoDto
    {
        return new ReviewInfoDto(
            id: $review->key,
            text: $review->comment,
            rating: $review->score,
            sender: $review->sender,
            time: $review->posted_at,
            resource: $review->resource,
            totalsRate: $review->total_brunch_rate,
            finalAnswer: $review->final_answer,
            controlReview: $review->control_review,
            answerDate: null,
            isOnCHeck: $review->is_on_check,
            link: $review->link,
            photos: $review->photos,
            isEdited: $review->is_edited,
            branchDto: $this->branchDtoFactory->create($review->brunch),
            dbId: $review->id,
            extraData: $review->extra_data,
        );
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function fromApi(array $data): ReviewInfoDto
    {
        return new ReviewInfoDto(
            id: $data['inner_id'],
            text: $data['text'],
            rating: $data['score'],
            sender: $data['sender'],
            time: new DateTime($data['posted_at']),
            resource: 'Бот',
            totalsRate: '',
            branchDto: BranchDtoFactory::create(Brunch::where('address', $data['brunch'])->first()),
        );
    }

    protected function parseDate(string $dateString): DateTime
    {
        preg_match_all('/\d\d\d\d-\d\d-\d\d/', $dateString, $match);
        $date = $match[0][0];
        preg_match_all('/T\d\d:\d\d/', $dateString, $match);
        $time = trim($match[0][0], 'T');

        return (new DateTime("{$date} {$time}"))
            ->sub(DateInterval::createFromDateString('2 hours'));
    }
}
