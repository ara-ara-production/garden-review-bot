<?php

namespace App\Services;

use App\Dtos\Factories\ReviewDtoFactory;
use App\Dtos\Models\ReviewDto;
use App\Models\Brunch;
use App\Models\Review;
use App\Services\BaseService;

class GoogleMapApiService extends ApiService
{
    protected const GOOGLE_MAP_API_URL = 'https://maps.googleapis.com/maps/api/place/details/json';

    public function __construct(
        protected \App\Dto\Telegram\Factory\ReviewDtoFactory $reviewDtoFactory,
    ) {
        parent::__construct();
    }

    public function foreachBrunches(): array
    {
        $reviews = collect();
        Brunch::select('google_map_id')
            ->whereNotNull('google_map_id')
            ->get()
            ->each(function (Brunch $brunch) use (&$reviews) {
                $reviews->push($this->requestReviewsFromApi($brunch->google_map_id));
            });

        return $reviews->toArray();
    }

    /**
     * @return array<\App\Dto\Telegram\Entity\ReviewDto>
     */
    public function requestReviewsFromApi(string $brunchId): array
    {
        $oCUrlSession = curl_init();

        curl_setopt($oCUrlSession, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCUrlSession, CURLOPT_RETURNTRANSFER, true);

        $params = [
            'key' => env('GOOGLE_MAP_API_KEY'),
            'language' => 'ru',
            'reviews_sort' => 'newest',
            'without_my_first_review' => true,
            'fields' => 'reviews',
            'placeid' => $brunchId,
        ];

        curl_setopt($oCUrlSession, CURLOPT_URL, self::GOOGLE_MAP_API_URL . '?' . http_build_query($params));

        $response = curl_exec($oCUrlSession);
        curl_close($oCUrlSession);

        return json_decode($response, true);
    }
}
