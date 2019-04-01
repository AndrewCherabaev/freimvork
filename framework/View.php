<?php
namespace Core;

class View {
    protected static $viewDelimiter = ':';
    protected static $viewFileExtention = 'php';
    protected $viewPath;
    protected $viewData;


    public function __construct($view, $data = [])
    {
        $this->viewPath = $view;
        $this->viewData = $data;
    }

    public function render()
    {
        ob_start();
        extract($this->viewData);
        include self::buildViewPath($this->viewPath);
        return ob_get_flush();
    }

    private static function buildViewPath($viewName)
    {
        return VIEW_PATH . \implode(DS, \explode(self::$viewDelimiter, $viewName)) . '.' . self::$viewFileExtention;
    }
}