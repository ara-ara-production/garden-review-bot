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
                'Host:  yandex.ru',
                'User-Agent:  Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0',
                'Accept:  application/json; charset=UTF-8',
                'Accept-Language:  en-US,en;q=0.5',
                'Accept-Encoding:  gzip, deflate, br, zstd',
                'X-Requested-With:  XMLHttpRequest',
                'Connection:  keep-alive',
                'Cookie:  _yasc=LL+ufquFGPxXfx6W87ISyWFOKbMFFKF9mDn+kRdgXgFug887QFzsG8xaV3tGlq36; i=GV+I3bLDa7cwjCJK+o4wqfqhWpwOoUG19t9FBUdGhJ/ZbcxkM3LmRE+DPgxkAEIJG+O4aXP3vWVWABQjvzW2ZAl2A5Q=; yandexuid=7224345041753766765; yashr=6625159121753766765; bh=YM7u2csGahLcyumIDvKso64E9ObwjgP7mgM=; is_gdpr=1; is_gdpr_b=CLOoOBC28AIYASgC; Session_id=3:1769371479.5.1.1753766806432:66__XA:4afe.1.2:1|212279663.12116581.2.2:12116581.3:1765883387.6:2095146043.7:1762975413|2176632262.188641.2.2:188641.3:1753955447.6:2095146043.7:1762975413|1654811562.-1.0.2:2777551.3:1756544357.6:2202916168.7:1756544357|2050905407.7291280.2.2:7291280.3:1761058086|3:11650960.955635.YN9bsGejb9JpGXmVRlpN_T5nEZg; sessar=1.1615350.CiAMUYbqemADuUA5Jj3QQ79eyzFOK3xv8LxQVOhhux3g1A.4516I3978kx-kNQnAMbE2eTIIrt6BNnEqISGRwai5v4; sessionid2=3:1769371479.5.1.1753766806432:66__XA:4afe.1.2:1|212279663.12116581.2.2:12116581.3:1765883387.6:2095146043.7:1762975413|2176632262.188641.2.2:188641.3:1753955447.6:2095146043.7:1762975413|1654811562.-1.0.2:2777551.3:1756544357.6:2202916168.7:1756544357|2050905407.7291280.2.2:7291280.3:1761058086|3:11650960.955635.fakesign0000000000000000000; yp=2084731479.udn.cDp0YW1hbml0LmRldg%3D%3D#2069315447.multib.1#2076418186.2fa.1#2077014224.pcs.0#1777110331.sz.1920x1080x1#2076862861.skin.s#1767640309.szm.1:1080x1920:963x905; L=eiIGfwtLbUdkA2NBAENnRFNfQ3Z9cngEOFEIDlYcAWEBFg8=.1769371479.1644225.378395.36dd50657fa25654dfec6859889aa10e; yandex_login=tamanit.dev; _ym_uid=1754503159236610093; font_loaded=YSv1; my=YwA=; amcuid=509874051761587696; seenNewCompanyMain=1; skid=9583132701764252751; gdpr=0; _ym_d=1765555111; instruction=1; ys=udn.cDp0YW1hbml0LmRldg%3D%3D#c_chck.2367749907',
                'Sec-Fetch-Dest:  empty',
                'Sec-Fetch-Mode:  cors',
                'Sec-Fetch-Site:  same-origin',
                'Priority:  u=0',
                'Pragma:  no-cache',
                'Cache-Control:  no-cache',
                'TE:  trailers'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = collect(json_decode($response, true));

        return $data->get('list')['items'];
    }
}
