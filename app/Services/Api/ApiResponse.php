<?php

namespace App\Services\Api;

use Illuminate\Support\Collection;

abstract class ApiResponse
{
    public function __construct(
        public int $statusCode,
        public string $message,
        public ?Collection $data = null,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            statusCode: $data['status_code'],
            message: $data['message'],
            data: isset($data['data']) ? static::parseData($data['data']) : null,
        );
    }

    abstract protected static function parseData(array $data): Collection;

    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }
}