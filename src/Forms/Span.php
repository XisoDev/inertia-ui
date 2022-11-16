<?php
namespace Xiso\InertiaUI\Forms;

use Illuminate\Support\Facades\App;

class Span{
    private string $currentLocale = '';
    private array $localeList = [];

    public array $content = [];
    public string $class = '';

    public function __construct($content = '', $locale = false)
    {
        $this->currentLocale = App::currentLocale();
        $this->localeList = app()->config->get('app.locales');

        //set translators
        $contents = [];
        foreach($this->localeList as $localeId => $localeName){
            $contents[$localeId] = '';
        }

        $this->content = $contents;
        if($content != ''){
            if(!$locale) $locale = $this->currentLocale;
            $this->content[$locale] = $content;
        }
    }

    public function setContent($content, $locale = false): static
    {
        if(!$locale) $locale = $this->currentLocale;
        $this->content[$locale] = $content;

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
}
