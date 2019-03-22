<?php
namespace Core\Http;

class Controller {
    protected $viewDelimiter = ':';
    protected $viewFileExtention = 'php';

    protected function render(string $view, array $data = [])
    {
        return self::createCompiler()($this->buildViewPath($view), $data);
    }

    private static function createCompiler()
    {
        return function() {
            ob_start();
            extract(func_get_arg(1));
            include func_get_arg(0);
            return ob_get_flush();
        };
    }

    private function buildViewPath($viewName)
    {
        return VIEW_PATH . implode(DS, explode($this->viewDelimiter, $viewName)) . '.' . $this->viewFileExtention;
    }

    public function callAction(string $action, array $arguments)
    {
        echo call_user_func_array([$this, $action], $arguments);
    }
}