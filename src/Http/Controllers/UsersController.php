<?php
namespace App\Http\Controllers;

use Core\Http\{Controller, Request};
use App\Models\User;

class UsersController extends Controller {
    public function index(Request $request)
    {
        return $this->render('users:index', ['list' => range(1,10)]);
    }

    public function show(Request $request, User $user, string $key = null) 
    {
        return "User {$user->id} {$key} ";
    }
}