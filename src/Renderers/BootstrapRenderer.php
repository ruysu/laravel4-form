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
        $field = $fields->get($name);

        if (!isset($attributes['class']) && !array_key_exists('class', $field->getAttributes())) {
            $attributes['class'] = '';
        }

        $type = $field->getType();
        $id = $fields->getFieldId($field, $attributes);

        if (!in_array($type, ['file', 'checkbox', 'radio', 'button', 'submit', 'image'])) {
            $attributes['class'] = trim('form-control ' . $attributes['class']);
        }

        $input = $fields->getInputFor($field, $value, $attributes);

        if (in_array($type, ['checkbox', 'radio'])) {
            $checkable_label = e($field->getLabel());
            $input = "<div class=\"{$type}\"><label>{$input} {$checkable_label}</label></div>";
            $label = '';
        } else {
            $label = $fields->getLabelFor($field, $id, ['class' => 'control-label']);
        }

        $error = $fields->getErrorFor($field, $id, ['class' => 'help-block']);
        $has_error = ($error ? ' has-error' : '');

        return "<div class=\"form-group{$has_error}\" id=\"{$id}-group\">{$label}{$input}{$error}</div>";
    }

}
