<?php
namespace Xiso\InertiaUI\Forms;

use Illuminate\Support\Facades\App;

class Section{
    public string $id = '';
    public string $title = '';
    public string $description = '';

    public bool $current = false;
    public bool $withButtons = true;

    public array $fields = [];

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
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
