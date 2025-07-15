<?php

namespace App\Dto\Admin\Entity;

class BrunchUpdateDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $user_id,
        public ?string $pupr_user_id,
        public ?string $two_gis_id,
    ){}
}
