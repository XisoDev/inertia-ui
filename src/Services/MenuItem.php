<?php

namespace Xiso\InertiaUI\Services;

use Xiso\InertiaUI\Services\Translate;

class MenuItem{
    public mixed $route = false;
    public String $link = "#";
    public array $children = [];
    public array $active_routes = [];
    public String $title = '';
    public String $description = '';

    public function __construct($title,$description)
    {
//        $this->title = Translate::getInstance()->set($title)->get();
//        $this->description = Translate::getInstance()->set($description)->get();
        $this->title = __($title);
        $this->description = __($description);
    }
}
