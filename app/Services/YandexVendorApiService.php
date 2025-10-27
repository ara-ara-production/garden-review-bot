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
            'X-App-Version: 1.76.3',
            'x-yarequestid: 4ba5122a0854f557955f949f6f06d0bf',
            'X-Language: ru',
            'X-Idempotency-Token: bea7b636-a62c-42f0-a82b-f498847b4ef1',
            'X-Oauth: Bearer y0__xCq14mVBhjq5Rog4o7lnxQwyKq3mgg2PndkhkBZigFbFAu1TlqXvEq3Ew',
            'X-Partner-Id: 65b15280-9e70-4018-93fe-5e919bd68d80',
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
