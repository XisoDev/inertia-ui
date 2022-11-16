<?php
namespace Xiso\InertiaUI\Forms;

use Illuminate\Support\Facades\App;

class Span{
    public string $content = '';
    public string $class = '';

    public function __construct($content = '')
    {
        $this->content = __($content);
    }

    public function setContent($content): static
    {
        $this->content = __($content);

        return $this;
    }

    public function getClass(): array
    {
        return explode(" ",$this->class);
    }

    public function addClass($class): static
    {
        $classes = $this->getClass();
        $addClasses = explode(" ",$class);
        foreach($addClasses as $addClass) $classes[] = $addClass;
        $this->class = join(" ",$classes);

        return $this;
    }
}
