<?php

declare(strict_types=1);

namespace App\Exceptions;

final class CartNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Cart Not Found', 404);
    }
}
