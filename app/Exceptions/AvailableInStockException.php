<?php

declare(strict_types=1);

namespace App\Exceptions;

final class AvailableInStockException extends BaseException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
