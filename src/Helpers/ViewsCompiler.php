<?php
namespace Freimvork\Helpers;

use Freimvork\View;

class ViewsCompiler extends AbstractCacheCompiler {
    protected static $FILE = 'views.php';
    protected static $KEY = 'views';

    protected static function compile()
    {
        $result = [];
        
        foreach (ConfigConverter::getViewConfig() as $view => $template) {
            $result[$view] = (new View($template))->render();
        }

        return $result;
    }
}