<?php

namespace Xiso\InertiaUI\Handlers;

use App\Models\Domain;
use App\Models\Tenant;
use App\Models\ThemeConfig;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;

class ThemeHandler{
    private string $current = 'default';

    private string $defaultThemePath = '';
    private string $themePath = '';

    private array $menuList = [];

    #[NoReturn] public function __construct($hostname = false)
    {
        $this->defaultThemePath = resource_path('/themes/');

        $tenant = $hostname ? Tenant::where('hostname',$hostname)->with('themeConfig')->first() : $this->getCurrentTenant();
        if($tenant){
            $this->tenant = $tenant;
            $this->set($tenant->themeConfig->theme_id);
        }else{
            $this->set('default');
        }
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

    public function set($theme): bool
    {
        if($this->getThemePath($theme)){
            $this->current = $theme;
            $this->themePath = 'themes/' . $theme;

            Inertia::share('path',$this->themePath . "/Pages/");
            return true;
        }else{
            return false;
        }
    }

    public function setMenuList(array $menuList = []){
        $this->menuList = $menuList;
    }

    public function setMenu($menu_id, array $menuList = []){
        $this->menuList[$menu_id] = $menuList;
    }

    public function getMenuList(array $menuList = []): array
    {
        $arrangedMenuList = [];
        foreach($this->menuList as $menu_id => $menu){
            $arrangedMenuList[$menu_id] = $this->getMenu($menu_id);
        }
        return $arrangedMenuList;
    }

    public function getMenu($menu_id){
        $menu = $this->menuList[$menu_id];

        foreach($menu as $key => $menuItem){
            $menu[$key] = $this->arrangeMenuChildren($menuItem);
        }

        return $menu;
    }

    public function addMenu($menu_id, $menuItem, $order = false){
        if($order !== false){
            $originalMenu = $this->menuList[$menu_id];
            $prevMenu = array_slice($this->menuList[$menu_id],0,$order);
            $nextMenu = array_slice($this->menuList[$menu_id],$order,count($originalMenu));
            $this->menuList[$menu_id] = array_merge($prevMenu,[$menuItem],$nextMenu);
        }else{
            $this->menuList[$menu_id][] = $menuItem;
        }
    }

    public function getThemeList(): array
    {
        $themeDirectories = scandir($this->defaultThemePath);
        $themeList = [];
        foreach($themeDirectories as $directory){
            if($directory == "." || $directory == "..") continue;

            $themeInfo = $this->getTheme($directory);
            if($themeInfo != false){
                if(!isset($themeList[$themeInfo['type']])) $themeList[$themeInfo['type']] = [];
                $themeList[$themeInfo['type']][] = $themeInfo;
            }
        }
        return $themeList;
    }

    public function getTheme($themeId = ''): bool|array
    {
        if(!$themeId) $themeId = $this->get();

        $infoFilePath = sprintf("%s/%s/themeSettings.json",$this->defaultThemePath,$themeId);
        if(file_exists($infoFilePath)){
            $info = json_decode(file_get_contents($infoFilePath, true));

            $theme = [];
            $theme['type'] = $info->themeType;
            $theme['id'] = $themeId;
            $theme['title'] = (array) $info->title ?? [ "ko" => "" ];
            $theme['description'] = $info->description ?? [ "ko" => "" ];
            $theme['options'] = $info->options ?? [];
            $theme['current'] = $this->current == $themeId;

            return $theme;
        }else{
            return false;
        }
    }

    public function getThemeByConfigId($configId): bool|array
    {
        $themeConfig = ThemeConfig::find($configId);
        if($themeConfig == null) return false;

        return $this->getTheme($themeConfig->theme_id);
    }

    public function render($component, array $props = []): \Inertia\Response
    {
        //share arranged menu list
        Inertia::share('menuList',$this->getMenuList());
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
