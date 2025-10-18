<?php

namespace App\Dto\Admin\Factory;

use App\Dto\Admin\Entity\BrunchCreateDto;

class BrunchCreateDtoFactory
{
    public function fromArray(array $data): BrunchCreateDto
    {
        $dataCollection = collect($data);

        return new BrunchCreateDto(
            $dataCollection->get('name'),
            $dataCollection->get('user_id'),
            $dataCollection->get('pupr_user_id'),
            $dataCollection->get('two_gis_id'),
            $dataCollection->get('yandex_vendor_id'),
            $dataCollection->get('google_map_id'),
            $dataCollection->get('address'),
        );
    }
}
