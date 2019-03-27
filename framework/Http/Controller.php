<?php
namespace Core\Http;

use Core\Helpers\{ConfigConverter, ViewsCompiler};
use Core\View;

class Controller {
    protected $viewDelimiter = ':';
    protected $viewFileExtention = 'php';
    // Create AdminPanel by creating 'admin:layout' view
    protected $layout = 'app:layout';

    protected function render(string $view, array $data = [])
    {
        $content = ViewsCompiler::getCompiled();
        $content['content'] = (new View($view, $data));
        $layout = new View($this->layout,  $content);
        $layout->render();
    }

    public function callAction(string $action, array $arguments)
    {
        print call_user_func_array([$this, $action], $arguments);
    }
}