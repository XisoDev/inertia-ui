<?php

namespace Xiso\InertiaUI\Forms;

use Illuminate\Support\Facades\App;

class Field{
    private string $currentLocale = '';
    private array $localeList = [];

    public string $type = 'text';
    public string $id = '';
    public mixed $value;
    public string $class = '';

    public bool $fixed = false;
    public bool $disabled = false;
    public bool $isArray = false;
    public bool $isGroup = false;

    public array $title = [];
    public array $description = [];
    public array $placeholder = [];
    public array $options = [];

    public array $attributes = [];

    public array $prepends = [];
    public array $appends = [];

    public function __construct($type, $id, $fixed = false)
    {
        $this->currentLocale = App::currentLocale();
        $this->localeList = app()->config->get('app.locales');

        //set translators
        $titles = [];
        $descriptions = [];
        $placeholders = [];
        foreach($this->localeList as $localeId => $localeName){
            $titles[$localeId] = '';
            $descriptions[$localeId] = '';
            $placeholders[$localeId] = '';
        }

        $this->fixed = $fixed;
        $this->type = $type;
        $this->id = $id;
        $this->title = $titles;
        $this->description = $descriptions;
        $this->placeholder = $placeholders;

        if($type == 'select') $this->options['_default'] = $this->getOptionObject('');
        if(in_array($type,['list-group','radio-group'])){
            $this->isGroup = true;
        }

        // set value type
        if(in_array($type,['image','file'])) $this->value = new \stdClass();
        else $this->value = '';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setValue($value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(){
        return $this->value;
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

    public function setPlaceHolder($placeHolder = false, $locale = false): static
    {
        if($placeHolder){
            if(!$locale) $locale = $this->currentLocale;
            $this->placeholder[$locale] = $placeHolder;

            if($this->type == 'select'){
                $this->options['_default'] = $this->placeholder;
            }
        }else{
            $this->placeholder = $this->description;
        }

        return $this;
    }

    public function addAttr($key, $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function getClass(){
        return explode(" ",$this->class);
    }

    public function addClass($class){
        $classes = $this->getClass();
        $addClasses = explode(" ",$class);
        foreach($addClasses as $addClass) $classes[] = $addClass;
        $this->class = join(" ",$classes);

        return $this;
    }

    public function addOption($value, $text = false, $locale = false): static
    {
        if(!isset($this->options[$value]) || !is_array($this->options[$value])) $this->options[$value] = $this->getOptionObject($value);

        if(!$locale) $locale = $this->currentLocale;
        $this->options[$value]['text'][$locale] = $text;

        return $this;
    }

    public function addOptionAttr($optionId, $key, $value, $withTranslate = false, $locale = false): static
    {
        if($withTranslate){
            if(!$locale) $locale = $this->currentLocale;
            $this->options[$optionId][$key][$locale] = $value;
        }else{
            $this->options[$optionId][$key] = $value;
        }

        return $this;
    }

    public function addSpan(Span $span,$type = 'append'): static
    {
        if($type !== 'prepend') $this->appends[] = $span;
        else $this->prepends[] = $span;

        return $this;
    }

    private function getOptionObject($value){
        $texts = [];
        foreach($this->localeList as $localeId => $localeName)
            $texts[$localeId] = '';

        return [
            'value' => $value,
            'text' => $texts,
        ];
    }
}
