<?php namespace Ruysu\LaravelForm\Inputs;

class Input implements InputInterface
{

    /**
     * Array of html attributes
     *
     * @var array
     */
    protected $attributes;
    /**
     * The label for this field
     *
     * @var string
     */
    protected $label;
    /**
     * The name for this field
     *
     * @var string
     */
    protected $name;
    /**
     * Type of input rendered
     *
     * @var string
     */
    protected $type;

    /**
     * Class constructor
     *
     * @param string $type
     * @param string $name
     * @param string $label
     * @param array  $attributes
     */
    public function __construct($type, $name, $label = null, array $attributes = array())
    {
        $this->attributes = $attributes;
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
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
        return [$this->type, $this->name, $value, $this->attributes];
    }

    /**
     * Get the attributes for this input
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get the label text
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get the method that should be called on the form builder
     *
     * @return string
     */
    public function getMethod()
    {
        return 'input';
    }

    /**
     * Get the name of the input
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the input type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Merge an array of attributes into the current
     *
     * @param  array  $attributes
     *
     * @return Ruysu\LaravelForm\Inputs\InputInterface
     */
    public function mergeAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

}
