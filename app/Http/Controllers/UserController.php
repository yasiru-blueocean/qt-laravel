<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index()
   {
    $users = User::all();
    return response()->json([
        'message' =>'Users fetched successfully',
        'count' => count($users),
        'data' => UserResource::collection($users)
    ]);
   }

}








