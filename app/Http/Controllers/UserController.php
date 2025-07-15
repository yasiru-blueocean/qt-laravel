<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
    $users = User::all();
    return response()->json([
        'message' => 'Users fetched Successfully',
        'count' => count($users),
        'data' => UserResource::collection($users),
    ]);
    }
}
