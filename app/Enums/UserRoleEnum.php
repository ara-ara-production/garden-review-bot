<?php

namespace App\Enums;


use Illuminate\Support\Collection;

enum UserRoleEnum: string
{
    case NullRole = "-";
    case Control = "П.Упр.|Упр.";
    case Founder = "Учредитель";
    case Ssm = "SMM";

    public static function toArray(): Collection
    {
        return collect(self::cases())->map(function (UserRoleEnum $item) {
            return [
                'name' => $item->name,
                'value' => $item->value,
            ];
        });
    }

    public static function tryFromName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }
        return null;
    }
}


