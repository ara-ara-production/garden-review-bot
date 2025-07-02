<?php

namespace App\Services;

use App\Dto\Telegram\Entity\ReviewDto;
use App\Dto\Telegram\Factory\ReviewDtoFactory;

class TwoGisApiService extends ApiService
{
    protected const TWO_GIS_API_REVIEWS = 'https://public-api.reviews.2gis.com/2.0/orgs/70000001020476016/reviews';

    public function __construct(
        protected ReviewDtoFactory $reviewDtoFactory,
    ) {
        parent::__construct();
    }

    /**
     * @return array<ReviewDto>
     */
    public function requestReviewsFromApi(): array
    {
        $oCUrlSession = curl_init();

        curl_setopt($oCUrlSession, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCUrlSession, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCUrlSession, CURLOPT_RETURNTRANSFER, true);

        $params = [
            'key' => config('api_keys.two_gis'),
            'locale' => 'ru_RU',
            'limit' => 5,
            'sort_by' => 'date_edited',
            'without_my_first_review' => true,

        ];

        curl_setopt($oCUrlSession, CURLOPT_URL, self::TWO_GIS_API_REVIEWS . '?' . http_build_query($params));

        $response = curl_exec($oCUrlSession);
        curl_close($oCUrlSession);

        return json_decode($response, true)['reviews'];
    }
}
