<?php namespace Ruysu\LaravelForm;

use Illuminate\Html\FormBuilder as Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Ruysu\LaravelForm\Inputs\InputInterface;

class FieldCollection extends Collection
{
    /**
     * Form builder instance
     *
     * @var Illuminate\Html\FormBuilder
     */
    protected $builder;

    /**
     * Error bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Name of the error bag
     *
     * @var string
     */
    protected $namespace;

    /**
     * Set the error bag
     *
     * @param MessageBag $errors
     *
     * @return void
     */
    public function setErrorBag(MessageBag $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Set the form builder
     *
     * @param Builder $builder
     *
     * @return void
     */
    public function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Set the name of the error bag
     *
     * @param string $namespace
     *
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Check if there are errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->errors && $this->errors->count() > 0;
    }

    /**
     * Get the error for a field, wrapped in a label
     *
     * @param  InputInterface $field
     * @param  string         $id
     * @param  array          $attributes
     *
     * @return string|boolean
     */
    public function getErrorFor(InputInterface $field, $id, array $attributes = array())
    {
        $error = $this->errors->first($field->getName());

        if ($error) {
            return $this->builder->label($id, $error, $attributes);
        }

        return false;
    }

    /**
     * Get the label for a field
     *
     * @param  InputInterface $field
     * @param  string         $id
     * @param  array          $attributes
     *
     * @return string|boolean
     */
    public function getLabelFor(InputInterface $field, $id, array $attributes = array())
    {
        $label = $field->getLabel();

        if ($label) {
            return $this->builder->label($id, $label, $attributes);
        }

        return false;
    }

    /**
     * Get the rendered input for a field
     *
     * @param  InputInterface $field
     * @param  mixed          $value
     * @param  array          $attributes
     *
     * @return string
     */
    public function getInputFor(InputInterface $field, $value = null, array $attributes = array())
    {
        $attributes['id'] = $this->getFieldId($field, $attributes);
        $field->mergeAttributes($attributes);
        return call_user_func_array([$this->builder, $field->getMethod()], $field->formatArguments($value));
    }

    /**
     * Get the field id
     *
     * @param  InputInterface $field
     * @param  array          $attributes
     *
     * @return string
     */
    public function getFieldId(InputInterface $field, array $attributes = array())
    {
        $name = $field->getName();
        $field_attributes = $field->getAttributes();
        $id = trim(preg_replace('/[^a-z-]/', '-', "{$this->namespace}-{$name}"), '-');
        return array_get($attributes, 'id', array_get($field_attributes, 'id', $id));
    }

}
