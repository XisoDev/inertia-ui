<?php

namespace Xiso\InertiaUI\Handlers;

use Xiso\InertiaUI\Models\Domain;
use Xiso\InertiaUI\Models\Tenant;
use Xiso\InertiaUI\Models\ThemeConfig;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;
use Xiso\InertiaUI\Services\Theme;

use Lavary\Menu\Builder as MenuBuilder;


class ThemeHandler{
    private string $current = 'default';

    private string $defaultThemePath = '';
    private string $themePath = '';
    private Theme $theme;

    public MenuHandler $menuHandler;

    #[NoReturn] public function __construct($hostname = false, $theme_id = '')
    {
        $this->defaultThemePath = resource_path('/themes/');
        $this->menuHandler = new MenuHandler();

        if($theme_id != ''){
            $this->set($theme_id);
        }else{
            $tenant = $hostname ?
                Tenant::where('hostname',$hostname)->with('themeConfig')->first()
                : $this->getCurrentTenant();
            if($tenant->id){
                $this->tenant = $tenant;
                $this->set($tenant->themeConfig->theme_id);
            }else{
                $this->set(env('MAIN_THEME','default'));
            }
        }
    }

    static public function getThemeTypeList(){
        return apply_filters('theme_type_list', [
            'system' => [
                'title' => __('시스템'),
                'description' => __('시스템에서 사용되는 테마입니다. 변경되거나 Super Admin 권한이 없는경우 선택할 수 없습니다.'),
            ],
            'public' => [
                'title' => __('일반'),
                'description' => __('일반적으로 공개되는 테마입니다. 테넌트가 생성될 때 할당 가능한 테마입니다.'),
            ]
        ]);
    }

    function getLocaleList(){
        return app()->config->get('app.locales');
    }

    function getCurrentTenant(): Tenant
    {
        if (Schema::hasTable('domains')) {
            $oDomain = Domain::where('domain', request()->getHost())->first();
            if ($oDomain == null) return new Tenant();
            return $oDomain->tenant;
        } else {
            return new Tenant();
        }
    }

    public function get(): string
    {
        return $this->current;
    }

    public function getPath(): string
    {
        return $this->themePath;
    }

    public function set($themeId): bool
    {
        if($this->getThemePath($themeId)){
            $this->current = $themeId;
            $this->themePath = 'themes/' . $themeId;
            $this->theme = $this->getTheme($themeId);

            $this->menuHandler->setMenuList($this->theme->menuList);

            Inertia::share('path',$this->themePath . "/Pages/");
            return true;
        }else{
            return false;
        }
    }

    public function getMenu($menu_id): MenuBuilder
    {
        return $this->menuHandler->get($menu_id);
    }

    public function getThemeList(): array
    {
        $themeDirectories = scandir($this->defaultThemePath);
        $themeList = [];
        foreach($themeDirectories as $directory){
            if($directory == "." || $directory == "..") continue;

            $themeInfo = $this->getTheme($directory);
            if($themeInfo->isExists()){
                if(!isset($themeList[$themeInfo->type])) $themeList[$themeInfo->type] = [];
                $themeList[$themeInfo->type][] = $themeInfo;
            }
        }
        return $themeList;
    }

    public function getTheme($themeId = false):Theme
    {
        if(!$themeId) $themeId = $this->get();

        $infoFilePath = sprintf("%s/%s/themeSettings.json",$this->defaultThemePath,$themeId);
        $theme = new Theme($themeId, $infoFilePath);
        if($this->current == $themeId) $theme->setCurrent();

        return $theme;
    }

    public function getThemeByConfigId($configId): bool|Theme
    {
        $themeConfig = ThemeConfig::find($configId);
        if($themeConfig == null) return false;

        return $this->getTheme($themeConfig->theme_id);
    }

    public function render($component, array $props = []): \Inertia\Response
    {
        $primary_menu = $this->menuHandler->get($this->theme->primaryMenuId);
        Inertia::share('menuList',$this->menuHandler->getMenuObject());
        if($primary_menu->active()) Inertia::share('breadCrumb',$primary_menu->crumbMenu()->all());
        Inertia::share('locale',App::currentLocale());

        $component = [
            'path' => $this->getPath() . "/Pages/",
            'component' => $component
        ];
        return Inertia::render(json_encode($component), $props);
    }

    #[Pure] private function getThemePath($themeId = false): bool|string
    {
        if(!$themeId) $themeId = $this->get();

        $parentDir = resource_path($themeId);
        if(file_exists($parentDir)) return $parentDir;

        $path = $this->defaultThemePath . "/" . $themeId;
        if(file_exists($path)) return $path;
        else return false;
    }

    //재귀함수로 호출되어 모든 메뉴아이템을 동일한 아키텍쳐로 만들어준다.
    private function arrangeMenuChildren($menuItem){
        //set default menuItem architecture
        if(!isset($menuItem['route'])) $menuItem['route'] = false;
        if(!isset($menuItem['link'])) $menuItem['link'] = false;
        if(!isset($menuItem['children']) || !is_array($menuItem['children'])) $menuItem['children'] = [];

        //make active routes and add self route
        $menuItem['active_routes'] = [];
        if($menuItem['route'] !== false) $menuItem['active_routes'][] = $menuItem['route'];
        if($menuItem['route']) $menuItem['active_routes'][] = $menuItem['route'];

//        if($menuItem['link'] === false && $menuItem['route'] !== false) $menuItem['link'] = route($menuItem['route']);

        if(count($menuItem['children']) > 0){
            //위 재귀가 끝나고나면 route key 가 항상 있게되므로 별도로 조건검사를 해주지 않는다.
            foreach($menuItem['children'] as $key => $child){
                $child = $this->arrangeMenuChildren($child);
                if($child['route'] !== false) $menuItem['active_routes'][] = $child['route'];

                //save child item
                $menuItem['children'][$key] = $child;
            }
        }

        $menuItem['active_routes'] = array_unique($menuItem['active_routes']);
        sort($menuItem['active_routes']);

        return $menuItem;
    }
}
