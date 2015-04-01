<?php namespace Ruysu\LaravelForm;

use Illuminate\Html\FormBuilder as Builder;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;
use Illuminate\Support\MessageBag;
use InvalidArgumentException;
use Ruysu\LaravelForm\Inputs\InputInterface;
use Ruysu\LaravelForm\Renderers\BootstrapRenderer;
use Ruysu\LaravelForm\Renderers\RendererInterface;

abstract class FormBuilder
{
    /**
     * The request instance.
     *
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * The defualt laravel form builder instance.
     *
     * @var Illuminate\Html\FormBuilder
     */
    protected $builder;

    /**
     * Whether or not to call addFields on especific action forms.
     *
     * @var boolean
     */
    protected $merge = true;

    /**
     * The model used to retrieve pre-fill data from.
     *
     * @var object
     */
    protected $model;

    /**
     * The url the form submits data to.
     *
     * @var string
     */
    protected $action;

    /**
     * The http method of this form.
     *
     * @var string
     */
    protected $method = 'post';

    /**
     * The name of the error bag used to retrieve errors from.
     *
     * @var string
     */
    protected $namespace = 'default';

    /**
     * The renderer instance.
     *
     * @var Ruysu\LaravelForm\Renderers\RendererInterface
     */
    protected $renderer;

    /**
     * Fields that have been added.
     *
     * @var Ruysu\LaravelForm\FieldCollection
     */
    protected $fields;

    /**
     * Class constructor.
     *
     * @param Illuminate\Html\FormBuilder $builder
     * @param Illuminate\Http\Request     $request
     * @param Illuminate\Session\Store    $session
     */
    public function __construct(Builder $builder, Request $request, Session $session)
    {
        $this->builder = $builder;
        $this->request = $request;
        $this->fields = new FieldCollection;
        $this->fields->setNamespace($this->namespace);
        $this->fields->setBuilder($this->builder);
        $this->fields->setErrorBag($session->has('errors') ? $session->get('errors')->getBag($this->namespace) : new MessageBag);
    }

    /**
     * Prepare the form for a given action.
     *
     * @param  string|null  $action
     * @param  boolean|null $merge  Whether to ignore default merge behaviour
     *
     * @return Ruysu\LaravelForm\FormBuilder
     */
    public function formFor($action = null, $merge = null)
    {
        $this->resetFields();

        if ($action) {
            $method = camel_case("add_{$action}_fields");

            if (method_exists($this, $method)) {
                $this->$method();
            }
        }

        if ($merge === null) {
            $merge = $this->merge;
        }

        if ($merge || $action === null) {
            $this->addFields();
        }

        return $this;
    }

    /**
     * Add a single field to the collection.
     *
     * @param  $input Ruysu\LaravelForm\FormBuilder\Inputs\InputInterface
     *
     * @return Ruysu\LaravelForm\FormBuilder
     */
    public function add(InputInterface $input)
    {
        $name = $input->getName();
        $this->fields->put($name, $input);
        return $this;
    }

    /**
     * Reset the field collection.
     *
     * @return Ruysu\LaravelForm\FormBuilder
     */
    public function resetFields()
    {
        foreach ($this->fields as $key => $value) {
            $this->fields->forget($key);
        }
        unset($key, $value);

        return $this;
    }

    /**
     * Get the field collection.
     *
     * @return Ruysu\LaravelForm\FieldCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get the field names.
     *
     * @return array
     */
    public function getFieldNames()
    {
        return $this->fields->keys();
    }

    /**
     * Get the input containing only the fields for an specific action or the default fields if no action is defined.
     *
     * @param  string|null $action
     *
     * @return array
     */
    public function getInput($action = null)
    {
        return $this->request->only($this->formFor($action)->getFieldNames());
    }

    /**
     * Set the form method.
     *
     * @param string $method
     *
     * @return void
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get the form method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the url this form submits data to.
     *
     * @param string $action
     *
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get the url this form submits data to.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the model used to get pre-fill data from.
     *
     * @param object $model
     *
     * @return void
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Get the model used to get pre-fill data from.
     *
     * @return object
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the renderer instance.
     *
     * @return Ruysu\LaravelForm\Renderers\RendererInterface
     */
    protected function getRenderer()
    {
        if (!$this->renderer) {
            $this->setRenderer(new BootstrapRenderer);
        }

        return $this->renderer;
    }

    /**
     * Set the renderer instance.
     *
     * @param  string|Ruysu\LaravelForm\Renderers\RendererInterface $renderer
     *
     * @return void
     */
    public function setRenderer($renderer)
    {
        if (is_string($renderer)) {
            $renderer = app($renderer);
        }

        if (!($renderer instanceof RendererInterface)) {
            throw new InvalidArgumentException('$renderer must implement RendererInterface or resolve to a class that implements RendererInterface');
        }

        $this->renderer = $renderer;
    }

    /**
     * Check wheter the form has a file input
     *
     * @return boolean
     */
    protected function hasFile()
    {
        return $this->fields->filter(function ($field) {
            return $field->getType() == 'file';
        })->count() > 0;
    }

    /**
     * Open the form, and add the fields for an specific action or the default fields if no action is defined.
     *
     * @param  string|null $action
     * @param  array       $attributes
     *
     * @return string
     */
    public function open($action = null, array $attributes = array())
    {
        if (is_array($action)) {
            $attributes = $action;
            $action = null;
        }

        $form = $this->formFor($action);

        $attributes = array_merge($attributes, ['method' => $this->method, 'url' => $this->action, 'files' => $this->hasFile()]);

        if ($this->model) {
            return $this->builder->model($this->model, $attributes);
        } else {
            return $this->builder->open($attributes);
        }
    }

    /**
     * Render a field in the collection, without wrapping it on the renderer.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  array  $attributes
     *
     * @return string
     */
    public function input($name, $value = null, array $attributes = array())
    {
        $field = $this->fields->get($name);
        return $this->fields->getInputFor($field, $value, $attributes);
    }

    /**
     * Render a field in the collection, wrapped on the renderer.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  array  $attributes
     *
     * @return string
     */
    public function field($name, $value = null, array $attributes = array())
    {
        $renderer = $this->getRenderer();
        return $renderer->renderField($this->fields, $name, $value, $attributes);
    }

    /**
     * Render the form for a given action or the default form.
     *
     * @param  string $action
     * @param  array  $attributes
     *
     * @return string
     */
    public function render($action = null, array $attributes = array())
    {
        $renderer = $this->getRenderer();

        $html = $this->open($action, $attributes);

        $html .= $renderer->render($this->getFields());

        $html .= $this->builder->close();

        return $html;
    }

    /**
     * Call a method on the builder instead of this instance if the method is not defined.
     *
     * @param  string $method
     * @param  array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        } else {
            return call_user_func_array([$this->builder, $method], $args);
        }
    }

    /**
     * Add the default fields to this form.
     */
    abstract public function addFields();

}
