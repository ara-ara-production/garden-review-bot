<?php

namespace App\Dto\Admin\Entity;

class BrunchCreateDto
{
    public function __construct(
        public string $name,
        public ?string $user_id,
        public ?string $pupr_user_id,
        public ?string $two_gis_id,
        public ?string $address,
    ){}
}
