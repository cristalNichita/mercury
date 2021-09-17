<?php

namespace Modules\Integration\Traits;


trait IntegrationFileTrait {


    public function getIntegrationFilesAttribute() {

        return $this->getMedia($this->integration_gallery_key);
    }

    public function getIntegrationGalleryKeyAttribute(): string
    {
        return 'files';
    }

}
