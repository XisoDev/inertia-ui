<?php

namespace Xiso\InertiaUI\Handlers;

use Lavary\Menu\Builder;
use Lavary\Menu\Menu;

class MenuHandler extends Menu
{
    public function __construct()
    {
        //set parent collection
        parent::__construct();
    }

    public function setMenuList(array $menuList){
        foreach($menuList as $menuId){
            $this->make($menuId, function(Builder $menu) use ($menuId){
                apply_filters('inertia_menu_' . $menuId, $menu);
            });
        }
    }

    public function getMenuObject(): array
    {
        //share arranged menu list
        $menuList = [];
        foreach($this->all() as $menuId => $menu){
            $menuList[$menuId] = [];
            foreach($menu->sortBy('order')->all() as $menuItem){
                $menuItem->url = $menuItem->url();
                $menuList[$menuId][] = $menuItem;
            }
        }
        return $menuList;
    }
}
