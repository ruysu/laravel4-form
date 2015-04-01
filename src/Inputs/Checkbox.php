<?php namespace Ruysu\LaravelForm\Inputs;

class Checkbox extends Input
{

    /**
     * The checkbox value
     *
     * @var string
     */
    protected $value;

    /**
     * Class constructor
     *
     * @param string $name
     * @param string $label
     * @param string $value
     * @param array  $attributes
     */
    public function __construct($name, $label = null, $value = '1', array $attributes = array())
    {
        $this->value = $value;
        parent::__construct('checkbox', $name, $label, $attributes);
    }

    /**
     * Format the arguments that will be passed to the builder
     *
     * @param  mixed  $value
     *
     * @return array
     */
    public function formatArguments($checked = null)
    {
        return [$this->name, $this->value, $checked, $this->attributes];
    }

    /**
     * Get the method that should be called on the form builder
     *
     * @return string
     */
    public function getMethod()
    {
        return 'checkbox';
    }

}
