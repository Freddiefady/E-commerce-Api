<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusOrder: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
