<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class AuthController extends Controller
{
    public function logout(Request $request)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', $request->user_id)
            ->update([
                'revoked' => true
            ]);
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|string',

        ]);
        $user = User::where('user_name', $request->user_name)->with('roles')->first();

        if (!$user) {
            return response()->json([
                'errors' => [
                    'status' => 404,
                    'title' => 'Not Found',
                    'detail' => 'User not found'
                ]
            ], 404);
        }
        $roles = $user->roles()->get();
        if (!Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
            return response()->json('Unauthorized', 401);
        }

        $accessToken = Auth::user()->createToken('authToken');

        if ($request->remember_me) {
            $accessToken->token->expires_at = Carbon::now()->addWeeks(1);
        }
        return response()->json([
            'user' => Auth::user()->makeHidden(['created_at', 'updated_at']),
            'roles' => $roles,
            'token' => $accessToken], 201);
    }

    private function createUser($dataUser, $role)
    {
        $role = Role::findOrFail($role);
        $user = new User([
            'name1' => strtoupper(trim($dataUser['name1'])),
            'name2' => strtoupper(trim($dataUser['name2'])),
            'last_name1' => strtoupper(trim($dataUser['last_name1'])),
            'last_name2' => strtoupper(trim($dataUser['last_name2'])),
            'user_name' => strtoupper(trim($dataUser['user_name'])),
            'phone' => strtoupper(trim($dataUser['phone'])),
            'email' => strtolower(trim($dataUser['email'])),
            'password' => Hash::make(trim($dataUser['password'])),
            'state' => 'ACTIVE'
        ]);
        $user->role()->associate($role);
        $user->save();
        return $user;
    }

    public function updateUser(Request $request)
    {
        $data = $request->json()->all();
        $dataUser = $data['user'];
        $user = User::findOrFail($dataUser['id']);
        $user->update([
            'name1' => strtoupper(trim($dataUser['name1'])),
            'name2' => strtoupper(trim($dataUser['name2'])),
            'last_name1' => strtoupper(trim($dataUser['last_name1'])),
            'last_name2' => strtoupper(trim($dataUser['last_name2'])),
            'user_name' => strtoupper(trim($dataUser['user_name'])),
            'phone' => strtoupper(trim($dataUser['phone'])),
            'email' => strtolower(trim($dataUser['email'])),
            'password' => Hash::make(trim($dataUser['password'])),
        ]);
        return response()->json(['message' => 'Usuario actualizado', 'user' => $user], 201);
    }

    public function changePassword(Request $request)
    {
        $data = $request->json()->all();
        $dataUser = $data['user'];
        $user = User::findOrFail($dataUser['id']);
        $user->update([
            'password' => Hash::make(trim($dataUser['password'])),
        ]);
        return response()->json(['message' => 'Usuario actualizado', 'user' => $user], 201);
    }

    public function uploadAvatarUri(Request $request)
    {
        if ($request->file('file_avatar')) {
            $user = User::findOrFail($request->user_id);
            Storage::delete($user->avatar);
            $pathFile = $request->file('file_avatar')->storeAs('private/avatar',
                $user->id . '.png');
//            $path = storage_path() . '/app/' . $pathFile;
            $user->update(['avatar' => $pathFile]);
            return response()->json(['message' => 'Archivo subido correctamente'], 201);
        } else {
            return response()->json(['errores' => 'Archivo no válido'], 500);
        }

    }
}
