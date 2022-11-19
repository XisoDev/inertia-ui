<?php

namespace Xiso\InertiaUI\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Xiso\InertiaUI\Handlers\FormHandler;
use Xiso\InertiaUI\Forms\Span;

class Tenant extends BaseTenant  implements HasMedia
{
    use InteractsWithMedia;
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
            'type',
            'parent_id',
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

    public function getForms($locale = false): FormHandler
    {
        if(!$locale) $locale = app()->getLocale();

        $formHandler = new FormHandler();
        $formHandler->form->setPOST();

        $section = $formHandler->createSection('tenant_default')
            ->setTitle('기본정보')
            ->setDescription('새 테넌트를생성합니다. 생성된 테넌트는 여러개의 테넌트를 종속시킬 수 있습니다.')
            ->disableBtn();

        $append = new Span(str_replace("https://","",env('APP_URL')));
        $append->addClass("bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-3 inline-flex items-center text-gray-500 sm:text-sm");

        $section->addField('text','hostname', true)
            ->setTitle("테넌트 호스트")
            ->setDescription("영문 및 숫자, 언더스코어(_)만 사용 가능합니다.")->setPlaceholder()
            ->addClass("focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 sm:text-sm rounded-l-md border-r-0 border-gray-300")
            ->addSpan($append);

        $section->addField('text','title')
            ->setTitle("사이트 명")
            ->setDescription("테넌트의 제목을 입력합니다.")->setPlaceholder()
            ->addClass("focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 sm:text-sm rounded-md border-gray-300");

        $section->addField('textarea','description')
            ->setTitle("간단 설명")
            ->setDescription("간단한 설명을 추가 해 주세요.")->setPlaceholder()
            ->addAttr('rows','3')
            ->addClass("shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md");


        $section = $formHandler->createSection('identity')
            ->setTitle('아이덴티티 설정')
            ->setDescription('로고 및 커버이미지, 주 색상 등에관한 브랜드 아이덴티티를 설정할 수 있습니다.')
            ->disableBtn();

        $section->addField('image','main_logo')
            ->setTitle('로고')
            ->addAttr('max',3)
            ->setDescription('주 색상포인트가 반영된 로고를 업로드합니다.')->setPlaceHolder();

        $section = $formHandler->createSection('theme_options')
            ->setTitle('테마 설정')
            ->setDescription('테마 설정을 생성하거나, 생성된 테마 설정을 적용할 수 있습니다.');

        $themeField = $section->addField('radio-group','theme_config_id')
            ->setTitle('사용할 테마')
            ->setDescription('테마는 생성된 테마 설정만을 주 테마로 지정할 수 있습니다.')
            ->setPlaceHolder('테마 설정을 선택합니다.');

        $themeConfigs = ThemeConfig::orderBy('theme_id')->get();
        foreach($themeConfigs as $themeConfig){
            $theme = $themeConfig->getTheme();
            $text = sprintf("%s",$themeConfig->title);
            $themeField->addOption($themeConfig->id,$text);
            $themeField->addOptionAttr($themeConfig->id,'description',$themeConfig->description);

            $themeField->addOptionAttr($themeConfig->id,'data-group-title',$theme->title[$locale]);
            $themeField->addOptionAttr($themeConfig->id,'data-group-key',$themeConfig->theme_id);
            $themeField->addOptionAttr($themeConfig->id,'data-group-link',route('settings.theme.configs',['themeId'=>$themeConfig->theme_id]));
            $themeField->addOptionAttr($themeConfig->id,'data-group-link-title',__('새 설정 추가'));
        }

        return $formHandler;
    }
}
