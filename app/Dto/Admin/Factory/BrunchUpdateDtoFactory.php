<?php

namespace App\Dto\Admin\Factory;

use App\Dto\Admin\Entity\BrunchUpdateDto;
use App\Models\Brunch;

class BrunchUpdateDtoFactory
{
    public function fromModel(Brunch $brunch): BrunchUpdateDto
    {
        $dataCollection = collect($brunch->toArray());
        return new BrunchUpdateDto(
            $dataCollection->get('id'),
            $dataCollection->get('name'),
            $dataCollection->get('user_id'),
            $dataCollection->get('pupr_user_id'),
            $dataCollection->get('two_gis_id'),
            $dataCollection->get('yandex_vendor_id'),
            $dataCollection->get('yandex_map_id'),
            $dataCollection->get('google_map_id'),
            $dataCollection->get('address'),
        );
    }
}
