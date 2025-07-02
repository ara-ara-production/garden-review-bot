<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Models\Brunch;
use DateInterval;
use DateTime;
use Illuminate\Support\Collection;

class ReviewInfoDtoFactory
{
    public function fromTwoGis(array $data): ReviewInfoDto
    {
        $dateString = $data['date_edited'] ?? $data['date_created'];
        $match = [];

        preg_match_all('/\d\d\d\d-\d\d-\d\d/', $dateString, $match);
        $date = $match[0][0];
        preg_match_all('/T\d\d:\d\d/', $dateString, $match);
        $time = trim($match[0][0], 'T');

        $dateTime = (new DateTime("{$date} {$time}"))
            ->sub(DateInterval::createFromDateString('2 hours'));

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
            time: $dateTime,
            resource: '2Гис',
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
}
