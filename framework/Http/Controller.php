<?php
namespace Core\Http;

class Controller {
    protected $viewDelimiter = ':';
    protected $viewFileExtention = 'php';
    protected $layout = 'app:layout';

    protected function render(string $view, array $data = [])
    {
        $viewConfig = file_exists(CONFIG_PATH . 'view.php') ? include (CONFIG_PATH . 'view.php') : [];
        $layout = new \Core\View($this->layout,  $viewConfig + ['content' => new \Core\View($view, $data)]);
        $layout->render();
        
    }

    public function callAction(string $action, array $arguments)
    {
        print call_user_func_array([$this, $action], $arguments);
    }
}