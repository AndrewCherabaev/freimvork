<?php
namespace App\Http\Controllers;

use Core\Http\Controller;
use App\Models\User;

class UsersController extends Controller {
    public function index()
    {
        return $this->render('app:users:index', ['list' => range(1,10)]);
    }

    public function show(User $user, string $key = null) 
    {
        return "User {$user->id} {$key} ";
    }
}