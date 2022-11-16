<?php
namespace Xiso\InertiaUI\Forms;

use Illuminate\Support\Facades\App;

class Section{
    private string $currentLocale = '';
    private array $localeList = [];

    public string $id = '';
    public array $title = [];
    public array $description = [];

    public bool $current = false;
    public bool $withButtons = true;

    public array $fields = [];

    public function __construct($id)
    {
        $this->currentLocale = App::currentLocale();
        $this->localeList = app()->config->get('app.locales');

        $titles = [];
        $descriptions = [];
        foreach($this->localeList as $localeId => $localeName){
            $titles[$localeId] = '';
            $descriptions[$localeId] = '';
        }

        $this->id = $id;
        $this->title = $titles;
        $this->description = $descriptions;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setTitle($title, $locale = false): static
    {
        if(!$locale) $locale = $this->currentLocale;
        $this->title[$locale] = $title;

        return $this;
    }

    public function setDescription($description, $locale = false): static
    {
        if(!$locale) $locale = $this->currentLocale;
        $this->description[$locale] = $description;

        return $this;
    }

    public function addField($fieldType, $fieldId, $fixed = false): Field
    {
        $field = new Field($fieldType,$fieldId, $fixed);
        $this->fields[$fieldId] = $field;

        return $field;
    }

    public function disableBtn(): Section
    {
        $this->withButtons = false;
        return $this;
    }
}
