<?php

// As I am a little crunched for time in getting this created. I have made a token that is checked
// instead of full user Authentication. This will replace sactum but only for display purposes 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminToken{
    public function handle(Request $request, Closure $next) { 
        $adminToken = $request->cookie('adminToken');
        if ($adminToken !== 'TotallySecureEncryptedTokem') { 
            return response()->json(['message' => 'Unauthorized'], 401); 
        }
        
        return $next($request);
    }
}