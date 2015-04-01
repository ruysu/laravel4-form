<?php

use Ruysu\LaravelForm\FormBuilder;
use Ruysu\LaravelForm\Inputs\Email;
use Ruysu\LaravelForm\Inputs\Text;

class FormBuilderStub extends FormBuilder
{
    public function addFields()
    {
        $this->add(new Text('name', 'Name'));
    }

    public function addCreateFields()
    {
        $this->add(new Email('email', 'E-mail'));
    }
}
