<?php

namespace Dietcube;

use Pimple\Container;

class Controller
{
    const ACTION_NOT_FOUND = 'notFound';
    const ACTION_METHOD_NOT_ALLOWED = 'methodNotAllowed';
    const ACTION_INTERNAL_ERROR = 'internalError';

    protected $container;

    protected $view_vars;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function setVars($key, $value = null)
    {
        if (is_array($key)) {
            $this->view_vars = array_merge($view_vars, $key);
        } else {
            $this->view_vars[$key] = $value;
        }

        return $this;
    }

    protected function isPost()
    {
        if (stripos($this->container['global.server']->get('REQUEST_METHOD'), 'post') === 0) {
            return true;
        }

        return false;
    }

    protected function get($name)
    {
        return $this->container[$name];
    }

    protected function query($name, $default = null)
    {
        return $this->container['global.get']->get($name, $default);
    }

    protected function body($name = null, $default = null)
    {
        return $this->container['global.post']->get($name, $default);
    }

    protected function findTemplate($name)
    {
        return $name . $this->get('app')->getTemplateExt();
    }

    protected function render($name, array $vars = [])
    {
        $template = $this->findTemplate($name);

        return $this->get('app.renderer')->render($template, array_merge($this->view_vars, $vars));
    }

    protected function redirect($uri, $code = 302)
    {
        $this->getResponse()->setHeader('Location', $uri, $code);

        return null;
    }

    protected function getResponse()
    {
        return $this->get('response');
    }

    protected function json($vars, $charset = 'utf-8')
    {
        $this->getResponse()->setHeader('Content-Type', 'application/json;charset=' . $charset);

        return json_encode($vars);
    }
}
