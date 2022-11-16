<?php

namespace Xiso\InertiaUI\Models;

use Xiso\InertiaUI\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class ThemeConfig extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTranslations;
    use Uuids;

    protected $guarded = [];
    public array $translatable = ['title','description'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function tenants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tenant::class);
    }
}
