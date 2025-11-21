<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusProduct: int
{
    case Active = 1;
    case Inactive = 0;
}
