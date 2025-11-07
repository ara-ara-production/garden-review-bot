<?php

namespace App\Services;

use App\Dto\Telegram\Entity\ReviewDto;
use App\Dto\Telegram\Factory\ReviewDtoFactory;
use App\Models\Brunch;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Collection;

class YandexVendorApiService extends ApiService
{
    protected const YANDEX_VENDOR_API_URL = 'https://vendor.yandex.ru/4.0/restapp-front/eats-place-rating/v1/places-order-feedbacks';

    public function __construct(
        protected ReviewDtoFactory $reviewDtoFactory,
    ) {
        parent::__construct();
    }

    public function foreachBrunches(): array
    {
        $reviews = collect();
       Brunch::select('yandex_vendor_id')
           ->whereNotNull('yandex_vendor_id')
           ->get()
       ->each(function (Brunch $brunch) use (&$reviews) {
           $reviews = $reviews->merge($this->requestReviewsFromApi($brunch->yandex_vendor_id));
       });

       return $reviews->toArray();
    }


    /**
     * @return array<ReviewDto>
     * @throws \DateMalformedStringException
     */
    public function requestReviewsFromApi(int $brunchId): array
    {
        $date = new DateTime('today 23:59:59', new DateTimeZone('UTC'));
        $startDate = clone $date;

        $data = [
            "limit" => 20,
            "from" => $startDate->modify('-1 month')->format('Y-m-d\TH:i:s\Z'),
            "place_ids" => [$brunchId],
            "to" => $date->format('Y-m-d\TH:i:s\Z'),
        ];

        // Заголовки
        $headers = [
            'Content-Type: application/json',
            'X-App-Version: 1.79.0',
            'x-yarequestid: 67bc1e6409831a9feefffcd6994a8841',
            'X-Language: ru',
            'X-Idempotency-Token: 81fab4a3-e2e3-424d-896b-0372d3b16d10',
            'X-Oauth: Bearer y0_AgAAAAB0C0r4AAay6gAAAAD6VB-2AABHxjnpN-lHd6vGou-AOiUEq62Lrg',
            'X-Partner-Id: 4677519f-2328-4ffc-8af2-37074392b420',
            'X-Device-Id: web_device_id'
        ];

        $oCUrlSession = curl_init(self::YANDEX_VENDOR_API_URL);

        curl_setopt($oCUrlSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($oCUrlSession, CURLOPT_POST, true);
        curl_setopt($oCUrlSession, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($oCUrlSession, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($oCUrlSession);
        curl_close($oCUrlSession);

        $data = collect(json_decode($response, true));

        return $data->get('feedbacks');
    }
}
