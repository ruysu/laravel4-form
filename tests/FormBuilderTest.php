<?php

use Illuminate\Support\Fluent;
use Orchestra\Testbench\TestCase;
use Ruysu\LaravelForm\Inputs\File;

class FormBuilderTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        parent::setUp();
        $this->builder = app('FormBuilderStub');
    }

    public function testFieldsAdded()
    {
        $field_names = $this->builder->formFor()->getFieldNames();
        $this->assertContains('name', $field_names);
    }

    public function testActionBasedFields()
    {
        $field_names = $this->builder->formFor('create')->getFieldNames();
        $this->assertContains('name', $field_names);
        $this->assertContains('email', $field_names);
    }

    public function testGetInput()
    {
        $input = $this->builder->getInput('create');
        $this->assertEquals(['name' => null, 'email' => null], $input);
    }

    public function testSetMethodAndGetMethod()
    {
        $this->builder->setMethod('get');
        $this->assertEquals('get', $this->builder->getMethod());
    }

    public function testSetAndGetAction()
    {
        $this->builder->setAction(url('store'));
        $this->assertEquals(url('store'), $this->builder->getAction());
    }

    public function testSetAndGetModel()
    {
        $model = new Fluent(['name' => 'Gerardo Gómez', 'email' => 'code@gerardo.im']);
        $this->builder->setModel($model);
        $this->builder->open();
        $this->assertEquals($this->builder->getValueAttribute('name'), $model->name);
        $this->assertEquals($this->builder->getValueAttribute('email'), $model->email);
    }

    public function testResetFields()
    {
        $this->builder->open('create');
        $this->assertContains('name', $this->builder->getFieldNames());
        $this->assertContains('email', $this->builder->getFieldNames());
        $this->builder->resetFields();
        $this->assertThat($this->builder->getFieldNames(), $this->logicalNot(
            $this->contains('email')
        ));
    }

    public function testHasFile()
    {
        $this->assertThat($this->builder->hasFile(), $this->logicalNot(
            $this->isTrue()
        ));
        $this->builder->add(new File('test'));
        $this->assertTrue($this->builder->hasFile());
    }
}
