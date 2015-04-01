<?php namespace Ruysu\LaravelForm\Renderers;

use Ruysu\LaravelForm\FieldCollection;

interface RendererInterface
{
    /**
     * Render a field collection
     *
     * @param  FieldCollection $fields
     *
     * @return string
     */
    public function render(FieldCollection $fields);

    /**
     * Render a field in a collection
     *
     * @param  FieldCollection $fields
     * @param  string          $name
     * @param  mixed           $value
     * @param  array           $attributes
     *
     * @return string
     */
    public function renderField(FieldCollection $fields, $name, $value = null, array $attributes = array());
}
