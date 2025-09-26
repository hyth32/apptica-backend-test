<?php

namespace App\Services\Api;

use Illuminate\Support\Collection;

abstract class ApiResponse
{
    private const SUCCESS_STATUS_MIN = 200;
    private const SUCCESS_STATUS_MAX = 299;

    public function __construct(
        public readonly int $statusCode,
        public readonly string $message,
        public readonly ?Collection $data = null,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            statusCode: $data['status_code'] ?? 500,
            message: $data['message'] ?? 'Unknown error',
            data: isset($data['data']) && is_array($data['data']) 
                ? static::parseData($data['data']) 
                : null,
        );
    }

    abstract protected static function parseData(array $data): Collection;

    public function isSuccessful(): bool
    {
        return $this->statusCode >= self::SUCCESS_STATUS_MIN 
            && $this->statusCode <= self::SUCCESS_STATUS_MAX;
    }

    public function hasData(): bool
    {
        return $this->data !== null && $this->data->isNotEmpty();
    }
}