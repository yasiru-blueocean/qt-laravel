<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all()->map(function ($user) {
            return [
                'U_id' => $user->U_id,
                'U_Title' => $user->U_Title,
                'U_FName' => $user->U_FName,
                'U_LName' => $user->U_LName,
                'user_name' =>$user->user_name,
                'U_Email' => $user->U_Email,
                'U_Contact' => $user->U_Contact,
                'U_Designation' => $user->U_Designation,
                'U_Type' => $user->U_Type,
                'U_Password' =>$user->U_Password,
                'U_Status' => $user->U_Status,
                'U_Cratedby' => $user->U_Cratedby,
                'U_CratedDate'=> $user->U_CratedDate,
                'u_Image' => $user->u_Image,
                'pw_status' => $user->pw_status
            ];
        });

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $users
        ]);
    }
}

