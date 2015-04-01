<?php namespace Ruysu\LaravelForm\Inputs;

class Text extends Input
{

    /**
     * Class constructor
     *
     * @param string $name
     * @param string $label
     * @param array  $attributes
     */
    public function __construct($name, $label = null, array $attributes = array())
    {
        parent::__construct('text', $name, $label, $attributes);
    }

    /**
     * Format the arguments that will be passed to the builder
     *
     * @param  mixed  $value
     *
     * @return array
     */
    public function formatArguments($value = null)
    {
        return [$this->name, $value, $this->attributes];
    }

    /**
     * Get the method that should be called on the form builder
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->type;
    }

}
