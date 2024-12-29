<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller{

    public function getAdminName(){
        $admin = User::where('role', 'admin') ->
        where('email', 'james@securescreeningservices.com') ->
        select('name')->
        first();

        return response()->json($admin);
    }

    public function getSupportUserAll(){
        $support = User::where('role', 'support')->get();
        return response()->json($support);
    }

    public function getSupportUserById($id){
        $support = User::where('role', 'support')->where('id', $id)->get();
        return response()->json($support);
    }
}