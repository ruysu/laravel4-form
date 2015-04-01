<?php namespace Ruysu\LaravelForm\Inputs;

class Url extends Text
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
        parent::__construct($name, $label, $attributes);
        $this->type = 'url';
    }

}
