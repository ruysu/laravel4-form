<?php namespace Ruysu\LaravelForm\Inputs;

class Tel extends Input
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
        parent::__construct('tel', $name, $label, $attributes);
    }

}
