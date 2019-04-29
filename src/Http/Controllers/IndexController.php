<?php
namespace App\Http\Controllers;

use Core\Http\Controller;

class IndexController extends Controller {
    public function index()
    {
        $this->render('app:index:index');
    }
}