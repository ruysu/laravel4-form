<?php namespace Ruysu\LaravelForm\Inputs;

class Select extends Input
{

    /**
     * Array of options for the select
     *
     * @var array
     */
    protected $options;

    /**
     * Class constructor
     *
     * @param string $name
     * @param string $label
     * @param array  $options
     * @param array  $attributes
     */
    public function __construct($name, $label = null, array $options = array(), array $attributes = array())
    {
        $this->options = $options;

        parent::__construct('select', $name, $label, $attributes);
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
        $name = $this->name;

        if (in_array('multiple', array_keys($this->attributes), true) || in_array('multiple', $this->attributes, true)) {
            $name .= '[]';
        }

        return [$name, $this->options, $value, $this->attributes];
    }

    /**
     * Get the method that should be called on the form builder
     *
     * @return string
     */
    public function getMethod()
    {
        return 'select';
    }

}
