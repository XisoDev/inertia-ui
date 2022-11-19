<?php

namespace Xiso\InertiaUI\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGeneratorFactory;
use Xiso\InertiaUI\Traits\Uuids;
use Illuminate\Support\Facades\Storage;

class Media extends BaseMedia{
    use Uuids;

    public function getUrl(string $conversionName = ''): string
    {
        $urlGenerator = UrlGeneratorFactory::createForMedia($this, $conversionName);

        return Storage::disk(config('media-library.disk_name'))->url($urlGenerator->getUrl());
    }
}
