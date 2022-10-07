<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Responses;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

class SiteverifyResponse implements Arrayable
{
    public function __construct(
        public bool  $success,
        public array $errorCodes,
    ) {
    }

    #[ArrayShape(['success' => "bool", 'error-codes' => "array"])] public function toArray(): array
    {
        return [
            'success' => $this->success,
            'error-codes' => $this->errorCodes,
        ];
    }
}
