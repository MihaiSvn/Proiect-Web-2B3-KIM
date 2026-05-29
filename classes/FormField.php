<?php

class FormField {
    public $label; //text pentru label
    public $id; // pentru 'for' din label, id input trebuie sa fie acesta, TREBUIE SA FIE EGAL CU NAME, UNDE NAME E CE TRIMITI PRIN POST
    public $type; // fie text/email/password/number
    public $placeholder; //placeholder ce se vede pe fundal ca exemplu

    public $required; //daca e required field-ul
    public $value; //valoarea care deja exista, luata din BD daca vreau sa o modifc
    public $min;  //daca type-ul meu e number
    public $max;

    public function __construct(
        $label,
        $id,
        $type,
        $required,
        $placeholder,
        $value = null,
        $min = null,
        $max = null
    ) {
        $this->label = $label;
        $this->id = $id;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->min = $min;
        $this->max = $max;
        $this->required = $required;
    }
}