<?php

namespace Modules\Integration\Traits;

use Illuminate\Support\Arr;

trait IntegrationTrait
{

    public static function findByCode($code) {

        return self::where(self::integrationKey(), $code)->first();
    }

    public function getIntegrationCodeAttribute(): string
    {
        return $this->{self::integrationKey()};
    }

    public static function integrationKey(): string
    {
        return 'id_1c';
    }

    public static function fillFromParser(array $attrs) {

        return self::updateOrCreate(
            [
                self::integrationKey() => $attrs[self::integrationKey()],
            ],
            Arr::except($attrs, [self::integrationKey()])
        );

    }

    public static function getForParser(array $attrs)
    {
        $selfModel = self::firstOrNew([
            self::integrationKey() => $attrs[self::integrationKey()],
        ]);

        return $selfModel;
    }
}
