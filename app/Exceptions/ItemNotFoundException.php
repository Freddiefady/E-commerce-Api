<?php

declare(strict_types=1);

namespace App\Exceptions;

final class ItemNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Item Not Found', 404);
    }
}
