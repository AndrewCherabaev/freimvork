<?php
namespace App\Http\Controllers;

use Core\Http\Controller;
use Core\Http\Request;
use Core\Helpers\RouterCompiler;

class IndexController extends Controller {
    public function index(Request $request)
    {
        $this->render('app:index:index');
    }
}