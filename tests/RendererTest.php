<?php

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Orchestra\Testbench\TestCase;

class RendererTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        parent::setUp();
        $errors = new ViewErrorBag;
        $errors->default = new MessageBag(['name' => 'Field is required']);
        $this->app['session']->set('errors', $errors);
        $this->builder = app('FormBuilderStub');
    }

    public function testRenderField()
    {
        $rendered_form = $this->builder->render('create');
        $this->assertContains('<div class="form-group has-error" id="default-name-group">' .
            '<label for="default-name" class="control-label">Name</label>' .
            '<input class="form-control" id="default-name" name="name" type="text">' .
            '<label for="default-name" class="help-block">Field is required</label>' .
            '</div>', $rendered_form);
        $this->assertContains('<div class="form-group" id="default-email-group">' .
            '<label for="default-email" class="control-label">E-mail</label>' .
            '<input class="form-control" id="default-email" name="email" type="email">' .
            '</div>', $rendered_form);
    }
}
