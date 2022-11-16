<?php

namespace Xiso\InertiaUI\Handlers;

use Illuminate\Support\Facades\App;

use Xiso\InertiaUI\Forms\Section;
use Xiso\InertiaUI\Forms\Form;

class FormHandler{
    public array $sections = [];

    public string $currentSectionId = '';
    public Section $currentSection;
    public Form $form;

    public function __construct()
    {
        $this->form = new Form();
    }

    public function createSection($id): Section
    {
        $this->currentSection = new Section($id);
        $this->sections[$id] = $this->currentSection;

        $this->currentSectionId = $id;
        return $this->currentSection;
    }

    public function createFromArray($options){
        foreach($options as $sectionId => $section){
            $createdSection = $this->createSection($sectionId);

            foreach($section->title as $locale => $text) $createdSection->setTitle($text, $locale);
            foreach($section->description as $locale => $text) $createdSection->setDescription($text, $locale);
            foreach($section->items as $fieldId => $field){
                $createdField = $createdSection->addField($field->type, $fieldId);

                if(isset($field->title)) foreach($field->title as $locale => $text) $createdField->setTitle($text, $locale);
                if(isset($field->description)) foreach($field->description as $locale => $text) $createdField->setDescription($text, $locale);
                if(isset($field->placeholder)) foreach($field->placeholder as $locale => $text) $createdField->setPlaceHolder($text, $locale);
                if(isset($field->attrs)) foreach($field->attrs as $key => $val) $createdField->addAttr($key, $val);
            }
        }
    }

    public function getFieldsByType($type): array
    {
        $fields = [];
        foreach($this->sections as $section) {
            foreach ($section->fields as $field) {
                if($field->type == $type){
                    $fields[] = $field;
                }
            }
        }
        return $fields;
    }

    public function setValueByModel($model): void
    {
        foreach($this->sections as $section) {
            foreach ($section->fields as $field) {
                if(isset($model->{$field->id})){
                    $field->setValue($model->{$field->id});
                }
                if($field->fixed) $field->disabled = true;
            }
        }
    }

    public function getForms(): array
    {
        $fields = [];
        foreach($this->sections as $section){
            foreach($section->fields as $field){
                $fields[$field->id] = $field->getValue();

                // arrange field-group
                if($field->isGroup == true){
                    $options = [];
                    foreach($field->options as $optionValue => $option){
                        if(!isset($options[$option['data-group-key']])){
                            $options[$option['data-group-key']] = [];
                            $options[$option['data-group-key']]['title'] = $option['data-group-title'];
                            $options[$option['data-group-key']]['id'] = $option['data-group-key'];
                            $options[$option['data-group-key']]['link_url'] = $option['data-group-link'];
                            $options[$option['data-group-key']]['link_title'] = $option['data-group-link-title'];
                            $options[$option['data-group-key']]['options'] = [];
                        }
                        $options[$option['data-group-key']]['options'][$optionValue] = $option;
                    }
                    $field->groups = $options;
                }

            }
        }
        return [
            'form' => $this->form,
            'fields' => $fields,
            'sections' => $this->sections
        ];
    }

//  굳이 저장안해도 relation 되더라..
//    public function saveSection($section = false){
//        if(!$section) $section = $this->currentSection;
//
//        $this->sections[$section->getId()] = $section;
//    }
}
