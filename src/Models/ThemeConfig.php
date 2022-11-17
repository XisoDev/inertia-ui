<?php

namespace Xiso\InertiaUI\Models;

use Xiso\InertiaUI\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

use Xiso\InertiaUI\Handlers\ThemeHandler;
use Xiso\InertiaUI\Services\Theme;

class ThemeConfig extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTranslations;
    use Uuids;

    public Theme $theme;
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

    public function getTheme(): Theme
    {
        if(!isset($this->theme)){
            $themeHandler = new ThemeHandler();
            $themeInfo = $themeHandler->getTheme($this->theme_id);
            $this->theme = $themeInfo;
        }
        return $this->theme;
    }

//테마핸들러 변경으로 무한루프돌게되어서 사용못함
//    protected static function boot()
//    {
//        parent::boot();
//        ThemeConfig::retrieved(function ($model) {
//            $themeHandler = new ThemeHandler();
//            $themeInfo = $themeHandler->getTheme($model->__get('theme_id'));
//            $model->__set('theme',$themeInfo);
//        });
//
//        ThemeConfig::saving(function ($model){
//            unset($model->theme);
//        });
//    }
}
