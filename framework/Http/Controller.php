<?php
namespace Core\Http;

use Core\Helpers\ConfigConverter;

class Controller {
    protected $viewDelimiter = ':';
    protected $viewFileExtention = 'php';
    // Create AdminPanel by creating 'admin:layout' view
    protected $layout = 'app:layout';

    protected function render(string $view, array $data = [])
    {
        $viewConfig = ConfigConverter::getViewConfig();
        $content = array_merge(
            $viewConfig,
            ['content' => new \Core\View($view, $data)]
        );
        $layout = new \Core\View($this->layout,  $content);
        $layout->render();
    }

    public function callAction(string $action, array $arguments)
    {
        print call_user_func_array([$this, $action], $arguments);
    }
}