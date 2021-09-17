<?php

namespace Modules\Integration\Interfaces;

interface IntegrationFileInterface
{


    public function getIntegrationFilesAttribute();

    //Warning: слишком длинное - фиг дочитаешь до конца
    public function getIntegrationGalleryKeyAttribute(): string;
}


