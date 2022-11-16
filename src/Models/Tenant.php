<?php

namespace Xiso\InertiaUI\Models;

use Spatie\Translatable\HasTranslations;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasTranslations;
    use HasDatabase, HasDomains;
    public array $translatable = ['title','description'];

    public function themeConfig(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ThemeConfig::class)->withDefault();
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'hostname',
            'theme_config_id',
            'title',
            'description',
            'created_at',
            'updated_at'
        ];
    }


    protected static function boot()
    {
        parent::boot();
        Tenant::created(function ($model){
            $model->run(function(){
                activity()->log('create new tenant');
            });
        });
    }
}
