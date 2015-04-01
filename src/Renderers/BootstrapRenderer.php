<?php namespace Ruysu\LaravelForm\Renderers;

use Ruysu\LaravelForm\FieldCollection;

class BootstrapRenderer implements RendererInterface
{

    /**
     * Render a field collection
     *
     * @param  FieldCollection $fields
     *
     * @return string
     */
    public function render(FieldCollection $fields)
    {
        $html = '';

        foreach ($fields as $name => $field) {
            $html .= $this->renderField($fields, $name);
        }

        return $html;
    }

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
    public function renderField(FieldCollection $fields, $name, $value = null, array $attributes = array())
    {
        if (!isset($attributes['class'])) {
            $attributes['class'] = '';
        }

        $attributes['class'] = trim('form-control ' . $attributes['class']);

        $field = $fields->get($name);
        $id = $fields->getFieldId($field, $attributes);
        $label = $fields->getLabelFor($field, $id, ['class' => 'control-label']);
        $error = $fields->getErrorFor($field, $id, ['class' => 'help-block']);
        $input = $fields->getInputFor($field, $value, $attributes);
        $has_error = ($error ? ' has-error' : '');

        return "<div class=\"form-group{$has_error}\" id=\"{$id}-group\">{$label}{$input}{$error}</div>";
    }

}
