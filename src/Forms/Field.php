<?php

namespace Xiso\InertiaUI\Forms;

use Illuminate\Support\Facades\App;

class Field{
    public string $type = 'text';
    public string $id = '';
    public mixed $value;
    public string $class = '';

    public bool $fixed = false;
    public bool $disabled = false;
    public bool $isArray = false;
    public bool $isGroup = false;

    public string $title = '';
    public string $description = '';
    public string $placeholder = '';

    public array $options = [];

    public array $attributes = [];

    public array $prepends = [];
    public array $appends = [];

    public function __construct($type, $id, $fixed = false)
    {
        $this->fixed = $fixed;
        $this->type = $type;
        $this->id = $id;

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

    public function setTitle($title): static
    {
        $this->title = __($title);
        return $this;
    }

    public function setDescription($description): static
    {
        $this->description = __($description);
        return $this;
    }

    public function setPlaceHolder($placeHolder = false): static
    {
        if($placeHolder){
            $this->placeholder = __($placeHolder);

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

    public function addOption($value, $text = false): static
    {
        if(!isset($this->options[$value]) || !is_array($this->options[$value])) $this->options[$value] = $this->getOptionObject($value);
        $this->options[$value]['text'] = __($text);

        return $this;
    }

    public function addOptionAttr($optionId, $key, $value): static
    {
        $this->options[$optionId][$key] = __($value);

        return $this;
    }

    public function addSpan(Span $span,$type = 'append'): static
    {
        if($type !== 'prepend') $this->appends[] = $span;
        else $this->prepends[] = $span;

        return $this;
    }

    private function getOptionObject($value){
        return [
            'value' => $value,
            'text' => '',
        ];
    }
}
