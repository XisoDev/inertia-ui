<?php
namespace Xiso\InertiaUI\Services;

use Illuminate\Support\Facades\App;

class Translate {
    private string $currentLocale = '';
    private array $localeList = [];
    private array $fields = [];

    private static $instance;

    private function __construct()
    {
        $this->currentLocale = App::currentLocale();
        $this->localeList = app()->config->get('app.locales');

        foreach($this->localeList as $localeId => $localeName){
            $this->fields[$localeId] = '';
        }
    }

    public static function getInstance(): Translate
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set($text, $locale = false)
    {
        if(!$locale) $locale = $this->currentLocale;
        $this->fields[$locale] = $text;

        return $this;
    }

    public function get():array {
        return $this->fields;
    }
}
