<?php
namespace Core\Http;

class Controller {
    protected $viewDelimiter = ':';
    protected $viewFileExtention = 'php';

    protected function render(string $view, array $data = [])
    {
        $compiler = self::createCompiler();
        return $compiler($this->buildViewPath($view), $data);
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

    private function buildJsonViewPath($viewName)
    {
        return CORE_VIEW_PATH . $viewName . '.' . $this->viewFileExtention;
    }
}