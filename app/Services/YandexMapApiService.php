<?php

namespace App\Services;

use App\Dto\Telegram\Entity\ReviewDto;
use App\Dto\Telegram\Factory\ReviewDtoFactory;
use App\Models\Brunch;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Collection;

class YandexMapApiService extends ApiService
{
    protected const YANDEX_MAP_API_URL = 'https://yandex.ru/sprav/api/%brunch_id%/reviews';

    public function __construct(
        protected ReviewDtoFactory $reviewDtoFactory,
    ) {
        parent::__construct();
    }

    public function foreachBrunches(): array
    {
        $reviews = collect();
        Brunch::select('yandex_map_id')
            ->whereNotNull('yandex_map_id')
            ->get()
            ->each(function (Brunch $brunch) use (&$reviews) {
                $reviews = $reviews->merge($this->requestReviewsFromApi($brunch->yandex_map_id));
            });

        return $reviews->toArray();
    }


    /**
     * @return array<ReviewDto>
     * @throws \DateMalformedStringException
     */
    public function requestReviewsFromApi(int $brunchId): array
    {
        $curl = curl_init();

        $url = str_replace('%brunch_id%', $brunchId, self::YANDEX_MAP_API_URL);

        $params = [
            "ranking" => "by_time",
            "page" => 1
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . '?' . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-download-options:  noopen',
                'x-yandex-req-id:  1762975873082684-14542492272646849334-balancer-l7leveler-kubr-yp-vla-160-BAL',
                'x-dns-prefetch-control:  off',
                'etag:  W/"6be6-/AuxUxNfkQQt5hHH32PMCl47dxE"',
                'content-security-policy:  default-src \'none\'; script-src \'self\' \'nonce-FMu9HLbAjm4CXiyUYesJNw==\' \'unsafe-eval\' yastatic.net maps.yastatic.net *.yandex.ru yandex.ru *.ya.ru ya.ru *.maps.yandex.net yastat.net widget-pvz.dostavka.yandex.net yango.com smartcaptcha.yandexcloud.net yandex.co.il *.yandex.co.il; style-src \'self\' \'unsafe-inline\' yastatic.net mc.yandex.ru yastat.net *.yandex.ru yandex.ru *.ya.ru ya.ru maps.yastatic.net priority.s3.yandex.net yandex.co.il *.yandex.co.il; font-src \'self\' data: yastatic.net *.yandex.ru yandex.ru *.ya.ru ya.ru widget-pvz.dostavka.yandex.net maps.yastatic.net; img-src \'self\' data: avatars.mds.yandex.net avatars.yandex.net yastatic.net maps.yastatic.net *.maps.yandex.net yapic.yandex.net *.yandex.ru yandex.ru avatars.mdst.yandex.net mc.webvisor.org mc.yandex.ru mc.yandex.by mc.yandex.kz mc.yandex.com mc.yandex.com.tr mc.yandex.com.ge mc.yandex.uz static-maps.yandex.ru storage.mds.yandex.net eda.yandex mc.admetrica.ru mc.admetrica.by mc.admetrica.kz mc.admetrica.com mc.admetrica.com.tr mc.admetrica.com.ge mc.admetrica.uz files.messenger.yandex.net priority.s3.yandex.net wappalyzer-client.yandex.ru *.ya.ru ya.ru blob: files.messenger.yandex.ru favicon.yandex.net ads.adfox.ru priority.s3.yandex.net yandex.co.il *.yandex.co.il; object-src \'self\' *.yandex.ru yandex.ru *.ya.ru ya.ru; frame-src \'self\' api-maps.yandex.ru yandex.ru yandex.by yandex.kz yandex.com yandex.com.tr yandex.com.ge yandex.uz *.yandex.ru *.yandex.by *.yandex.kz *.yandex.com *.yandex.com.tr *.yandex.com.ge *.yandex.uz ya.ru *.ya.ru yango.com *.yango.com yandexmaps: priority.s3.yandex.net smartcaptcha.yandexcloud.net yastatic.net maps.yastatic.net yandex.co.il *.yandex.co.il; child-src \'self\' api-maps.yandex.ru awaps.yandex.ru; connect-src \'self\' mc.yandex.ru yandex.ru *.yandex.ru ya.ru *.ya.ru files.messenger.yandex.net api.passport.yandex.ru api.passport.yandex.by api.passport.yandex.kz api.passport.yandex.com api.passport.yandex.com.tr api.passport.yandex.com.ge api.passport.yandex.uz yastatic.net yandex.ru yandex.by yandex.kz yandex.com yandex.com.tr yandex.com.ge yandex.uz *.yandex.ru *.yandex.by *.yandex.kz *.yandex.com *.yandex.com.tr *.yandex.com.ge *.yandex.uz maps.yastatic.net *.business.yango.com widget-pvz.dostavka.yandex.net api-ext.vh.yandex.net maps-geoapp-goods-imports-stable.s3.yandex.net maps-geoapp-goods-synchronizations-stable.s3.yandex.net files.messenger.yandex.ru mc.yandex.md yango.com core-renderer-tiles.maps.yandex.net photo.upload.maps.yandex.ru photo.upload.maps.yandex.by photo.upload.maps.yandex.kz photo.upload.maps.yandex.com photo.upload.maps.yandex.com.tr photo.upload.maps.yandex.com.ge photo.upload.maps.yandex.uz *.maps.yango.com priority.s3.yandex.net blob: wss://mc.yandex.ru wss://push.yandex.ru yandex.co.il *.yandex.co.il; manifest-src \'self\'; worker-src data: blob:; media-src blob:; report-uri https://csp.yandex.net/csp?from=tycoon&yandex_login=tamanit.dev&yandexuid=7224345041753766765;',
                'vary:  Accept-Encoding',
                'date:  Wed, 12 Nov 2025 19:31:13 GMT',
                'accept-ch:  Sec-CH-UA-Platform-Version, Sec-CH-UA-Mobile, Sec-CH-UA-Model, Sec-CH-UA, Sec-CH-UA-Full-Version-List, Sec-CH-UA-WoW64, Sec-CH-UA-Arch, Sec-CH-UA-Bitness, Sec-CH-UA-Platform, Sec-CH-UA-Full-Version, Sec-CH-Viewport-Width, Viewport-Width, DPR, Device-Memory, RTT, Downlink, ECT, Width, Sec-Ch-Viewport-Height',
                'content-encoding:  gzip',
                'report-to:  { "group": "network-errors", "max_age": 100, "endpoints": [{"url": "https://dr.yandex.net/nel", "priority": 1}, {"url": "https://dr2.yandex.net/nel", "priority": 2}]}',
                'referrer-policy:  no-referrer',
                'x-permitted-cross-domain-policies:  none',
                'content-type:  application/json; charset=utf-8',
                'x-req-id:  1762975873082684-14542492272646849334-balancer-l7leveler-kubr-yp-vla-160-BAL',
                'nel:  {"report_to": "network-errors", "max_age": 100, "success_fraction": 0.001, "failure_fraction": 0.1}',
                'x-content-type-options:  nosniff',
                'x-content-type-options:  nosniff',
                'x-xss-protection:  0',
                'x-xss-protection:  1; mode=block',
                'X-Firefox-Spdy:  h2',
                'Cookie: Cookie_1=value; Session_id=3:1764629521.5.1.1753766806432:66__XA:4afe.1.2:1|212279663.-1.2.2:9208607.3:1762975413.6:2095146043.7:1762975413|2176632262.188641.2.2:188641.3:1753955447.6:2095146043.7:1762975413|1654811562.-1.0.2:2777551.3:1756544357.6:2202916168.7:1756544357|2050905407.7291280.2.2:7291280.3:1761058086|3:11454748.936170.VGLAxIRlFGg7-D83vrIPgQJhBUs; _yasc=yoNvMaF+Kwz/o5VMbRAfbZRS+V2ULqNbD2hQKnUwsZsoEV0PypnUrCl/PHv9rAg=; bh=YJrUuMkGagOAswE=; i=6FOeQufgX/0h2u/RjYRmDin1DPwpAY65Wz95twSScE9gl5Vn7uql7siuYnCnIk6Rp2HNJsSEhlw02G8DxF6lmoQ/Pnk=; is_gdpr=1; is_gdpr_b=CND3MBCR5gIYASgC; receive-cookie-deprecation=1; sessar=1.1396519.CiB5eFe8H43AkVeNdeQT_Kd1U6srjALClXvCh8WLUt1pSw.3PCPMKFLT1J7qtlIc0dh2puk0wB1ax1CNZxrQ0LrtGg; sessionid2=3:1764629521.5.1.1753766806432:66__XA:4afe.1.2:1|212279663.-1.2.2:9208607.3:1762975413.6:2095146043.7:1762975413|2176632262.188641.2.2:188641.3:1753955447.6:2095146043.7:1762975413|1654811562.-1.0.2:2777551.3:1756544357.6:2202916168.7:1756544357|2050905407.7291280.2.2:7291280.3:1761058086|3:11454748.936170.fakesign0000000000000000000; yandex_login=tamanit.dev; yandexuid=4893779281745447255; yashr=2862908841745447255'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = collect(json_decode($response, true));

        return $data->get('list')['items'];
    }
}
