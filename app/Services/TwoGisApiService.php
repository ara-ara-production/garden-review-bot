<?php

namespace App\Services;

use App\Dto\Telegram\Entity\ReviewDto;
use App\Dto\Telegram\Factory\ReviewDtoFactory;
use App\Models\Brunch;
use Illuminate\Support\Collection;

class TwoGisApiService extends ApiService
{
    protected const TWO_GIS_API_REVIEWS = 'https://public-api.reviews.2gis.com/2.0/branches/%brunch_id%/reviews';

    public function __construct(
        protected ReviewDtoFactory $reviewDtoFactory,
    ) {
        parent::__construct();
    }

    public function foreachBrunches(): array
    {
        $reviews = collect();
       Brunch::select('two_gis_id')
           ->get()
       ->each(function (Brunch $brunch) use (&$reviews) {
           $reviews->push($this->requestReviewsFromApi($brunch->two_gis_id));
       });

       return $reviews->toArray();
    }


    /**
     * @return array<ReviewDto>
     */
    public function requestReviewsFromApi(string $brunchId): array
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
//            'without_my_first_review' => true,
            'fields' => 'meta.providers,meta.branch_rating,meta.branch_reviews_count,meta.total_count,reviews.hiding_reason,reviews.is_verified,reviews.emojis'
        ];

        $url = str_replace('%brunch_id%', $brunchId, self::TWO_GIS_API_REVIEWS);

        curl_setopt($oCUrlSession, CURLOPT_URL, $url . '?' . http_build_query($params));

        $response = curl_exec($oCUrlSession);
        curl_close($oCUrlSession);

        return json_decode($response, true);
    }
}
