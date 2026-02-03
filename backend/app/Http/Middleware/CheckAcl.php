<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Acl;
use App\Models\Rule;
use Illuminate\Support\Facades\Auth;

class CheckAcl
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Ambil role user
        $roleId = $user->role_id;

        // Ambil endpoint saat ini (misal /member)
        $path = '/' . $request->segment(2);

        // Cek ACL berdasarkan path
        $acl = Acl::where('name', $path)->first();

        if (!$acl) {
            return response()->json(['message' => 'ACL Not Registered'], 403);
        }

        // Cek rule untuk role ini + ACL ini
        $rule = Rule::where('role_id', $roleId)
            ->where('acl_id', $acl->id)
            ->first();

        if (!$rule) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Cek permission
        if ($rule->permission == 0 && !$request->isMethod('GET')) {
            return response()->json(['message' => 'Read Only'], 403);
        }

        return $next($request);
    }
}
