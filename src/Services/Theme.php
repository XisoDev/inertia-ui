<?php
namespace Xiso\InertiaUI\Services;

Class Theme{
    public string $type = 'public'; // or system
    public string $id = '';
    public array $title = [];
    public array $description = [];
    public array $options = [];

    public bool $current = false;
    public bool $isExists = false;

    public function __construct($id, $infoFilePath)
    {
        if(file_exists($infoFilePath)){
            $info = json_decode(file_get_contents($infoFilePath, true));

            $this->type = $info->themeType;
            $this->id = $id;
            $this->title = $info->title ?? ["ko" => 'Untitled'];
            $this->description = $info->description ?? [];

            $this->options = $info->options ?? [];
            $this->isExists = true;
        }
    }

    public function isExists():bool {
        return $this->isExists;
    }

    public function setCurrent(){
        $this->current = true;
    }
}
