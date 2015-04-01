<?php

use Orchestra\Testbench\TestCase;
use Ruysu\LaravelForm\Inputs\Checkbox;
use Ruysu\LaravelForm\Inputs\Email;
use Ruysu\LaravelForm\Inputs\File;
use Ruysu\LaravelForm\Inputs\Input;
use Ruysu\LaravelForm\Inputs\Number;
use Ruysu\LaravelForm\Inputs\Password;
use Ruysu\LaravelForm\Inputs\Radio;
use Ruysu\LaravelForm\Inputs\Select;
use Ruysu\LaravelForm\Inputs\Tel;
use Ruysu\LaravelForm\Inputs\Text;
use Ruysu\LaravelForm\Inputs\Url;

class InputsTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        parent::setUp();
        $this->builder = app('FormBuilderStub');
    }

    public function testTextField()
    {
        $this->builder->add(new Text('name', 'Name'));
        $this->assertContains('type="text"', $this->builder->input('name'));
    }

    public function testEmailField()
    {
        $this->builder->add(new Email('email', 'E-mail'));
        $this->assertContains('type="email"', $this->builder->input('email'));
    }

    public function testSelectField()
    {
        $this->builder->add(new Select('role', 'Role', ['9' => 'Admin', '1' => 'User']));
        $this->assertContains('<select', $this->builder->input('role'));
        $this->assertContains('<option value="9">', $this->builder->input('role'));
        $this->assertContains('<option value="9" selected', $this->builder->input('role', '9'));
    }

    public function testPhoneField()
    {
        $this->builder->add(new Tel('phone', 'Phone'));
        $this->assertContains('type="tel"', $this->builder->input('phone'));
        $this->assertContains('value="5555-5555"', $this->builder->input('phone', '5555-5555'));
    }

    public function testUrlField()
    {
        $this->builder->add(new Url('website', 'Website'));
        $this->assertContains('type="url"', $this->builder->input('website'));
    }

    public function testNumberField()
    {
        $this->builder->add(new Number('age', 'Age'));
        $this->assertContains('type="number"', $this->builder->input('age'));
    }

    public function testInputField()
    {
        $this->builder->add(new Input('number', 'age', 'Age'));
        $this->assertContains('type="number"', $this->builder->input('age'));
    }

    public function testRadioField()
    {
        $this->builder->add(new Radio('accept', 'Accept Terms', 'yes'));
        $this->assertContains('type="radio"', $this->builder->input('accept'));
        $this->assertContains('checked', $this->builder->input('accept', true));
    }

    public function testCheckboxField()
    {
        $this->builder->add(new Checkbox('terms', 'Accept Terms', '1'));
        $this->assertContains('type="checkbox"', $this->builder->input('terms'));
        $this->assertContains('checked', $this->builder->input('terms', true));
    }

    public function testPasswordField()
    {
        $this->builder->add(new Password('password', 'Password'));
        $this->assertContains('type="password"', $this->builder->input('password'));
        $this->assertThat($this->builder->input('password', 'test'), $this->logicalNot(
            $this->stringContains('value="test"')
        ));
    }

    public function testFileField()
    {
        $this->builder->add(new File('picture', 'Profile Picture', ['data-foo' => 'bar']));
        $this->assertContains('type="file"', $this->builder->input('picture'));
        $this->assertContains('data-foo="bar"', $this->builder->input('picture'));
        $this->assertThat($this->builder->input('picture', 'test'), $this->logicalNot(
            $this->stringContains('value="test"')
        ));
    }
}
