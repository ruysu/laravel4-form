<?php namespace Ruysu\LaravelForm\Inputs;

interface InputInterface
{

    /**
     * Format the arguments that will be passed to the builder
     *
     * @param  mixed  $value
     *
     * @return array
     */
    public function formatArguments($value = null);

    /**
     * Get the attributes for this input
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Get the label text
     *
     * @return string
     */
    public function getLabel();

    /**
     * Get the method that should be called on the form builder
     *
     * @return string
     */
    public function getMethod();

    /**
     * Get the name of the input
     *
     * @return string
     */
    public function getName();

    /**
     * Get the input type
     *
     * @return string
     */
    public function getType();

    /**
     * Merge an array of attributes into the current
     *
     * @param  array  $attributes
     *
     * @return Ruysu\LaravelForm\Inputs\InputInterface
     */
    public function mergeAttributes(array $attributes);

}
