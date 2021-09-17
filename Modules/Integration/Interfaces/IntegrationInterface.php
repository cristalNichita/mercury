<?php

namespace Modules\Integration\Interfaces;


interface IntegrationInterface
{
    public static function findByCode(string $code);

    public function getIntegrationCodeAttribute(): string;

    public static function integrationKey(): string;

    public static function prepareData(array $attrs): array;
    public static function fillFromParser(array $attrs);

}
