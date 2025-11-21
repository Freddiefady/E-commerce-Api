<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class BaseException extends Exception
{
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function render(): Response
    {
        return response([
            'status' => 'error',
            'message' => $this->getMessage(),
        ], 422);
    }
}
