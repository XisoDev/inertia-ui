<?php
namespace Xiso\InertiaUI\Services;

use Xiso\InertiaUI\Handlers\FormHandler;

Class Theme{
    public string $type = 'public'; // or system
    public string $id = '';
    public array $title = [];
    public array $description = [];
    public bool $current = false;

    public bool $isExists = false;

    public String $primaryMenuId = '';
    public array $menuList = [];
    public array $options = [];
    public FormHandler $configForm;

    public function __construct($id, $infoFilePath)
    {
        if(file_exists($infoFilePath)){
            $info = json_decode(file_get_contents($infoFilePath, true));

            $this->type = $info->themeType;
            $this->id = $id;
            $this->title = (array) $info->title ?? [
                    "ko" => 'Untitled'
                ];
            $this->description = (array) $info->description ?? [];

            $this->menuList = (array) $info->menus;
            $this->primaryMenuId = $info->primary_menu ?? '';

            $this->options = (array) $info->options ?? [];
            $this->isExists = true;

            $this->configForm = new FormHandler();
            $this->configForm->createFromArray($this->options);
        }
    }

    public function isExists():bool {
        return $this->isExists;
    }

    public function setCurrent(){
        $this->current = true;
    }
}
