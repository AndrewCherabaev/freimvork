<?php
namespace App\Http\Controllers;

use Core\Http\Controller;
use Core\Http\Request;

class IndexController extends Controller {
    public function index(Request $request) {
        $this->render('index:index', ['name' => "World"]);
    }
}