<?php
namespace App\Http\Controllers;

use Core\Http\{Controller, Request};

class IndexController extends Controller {
    public function index(Request $request)
    {
        $this->render('app:index:index');
    }
}