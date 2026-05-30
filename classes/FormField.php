<?php

class FormField {
    public $label; //text pentru label
    public $id; // pentru 'for' din label, id input trebuie sa fie acesta, TREBUIE SA FIE EGAL CU NAME, UNDE NAME E CE TRIMITI PRIN POST
    public $type='text'; // fie text/email/password/number
    public $placeholder=''; //placeholder ce se vede pe fundal ca exemplu

    public $required=true; //daca e required field-ul
    public $value=null; //valoarea care deja exista, luata din BD daca vreau sa o modifc
    public $min=null;  //daca type-ul meu e number
    public $max=null;

    private function __construct(
        $label,
        $id
    ) {
        $this->label = $label;
        $this->id = $id;
    }

    public static function create($label, $id) {
        return new self($label, $id);
    }

    public function type($type) {
        $this->type = $type;
        return $this;
    }

    public function placeholder($placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function required($required = true) {
        $this->required = $required;
        return $this;
    }

    public function value($value) {
        $this->value = $value;
        return $this;
    }

    public function limits($min, $max) {
        $this->min = $min;
        $this->max = $max;
        return $this;
    }

}