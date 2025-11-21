<?php

declare(strict_types=1);

namespace App\Exceptions;

final class CartEmptyException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Your cart is empty');
    }
}
