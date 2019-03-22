<?php
namespace App\Http\Controllers;

use Core\Http\Controller;
use Core\Http\Request;

class UsersController extends Controller{
    public function index(Request $request)
    {
        $this->render('users:index', ['list' => range(1,10)]);
    }

    public function show(Request $request, int $id, string $key = null) 
    {
        echo "User $id ${key}";
    }
}