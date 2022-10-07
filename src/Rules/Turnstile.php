<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Rules;

use Illuminate\Contracts\Validation\Rule;
use RyanChandler\LaravelCloudflareTurnstile\TurnstileClient;

class Turnstile implements Rule
{
    protected array $messages = [];

    public function __construct(
        protected TurnstileClient $turnstile,
    ) {
    }

    public function passes($attribute, $value): bool
    {
        $response = $this->turnstile->siteverify($value);

        if ($response->success) {
            return true;
        }

        foreach ($response->errorCodes as $errorCode) {
            $this->messages[] = $this->mapErrorCodeToMessage($errorCode);
        }

        return false;
    }

    public function message(): array|string
    {
        return $this->messages;
    }

    protected function mapErrorCodeToMessage(string $code): string
    {
        return match ($code) {
            'missing-input-secret' => 'Секретный ключ не был передан.',
            'invalid-input-secret' => 'Секретный ключ недействителен или не существует.',
            'missing-input-response' => 'Параметр ответа не был передан.',
            'invalid-input-response' => 'Параметр ответа недействителен или срок его действия истек.',
            'bad-request' => 'Запрос был отклонен из-за неправильного формата.',
            'timeout-or-duplicate' => 'Параметр ответа уже был проверен ранее.',
            'internal-error' => 'Произошла внутренняя ошибка при проверке ответа.',
            default => 'Произошла непредвиденная ошибка.',
        };
    }
}
