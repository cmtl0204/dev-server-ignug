<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\State;
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

    public function indexUser(Request $request)
    {
        $state = State::where('code', '1')->first();
        $users = $state->users()->with('ethnicOrigin')
            ->with('location')
            ->with('identificationType')
            ->with('sex')
            ->with('gender')
            ->get();
        return response()->json([
            'data' => [
                'users' => $users
            ]
        ], 200);

    }

    public function showUser($id)
    {
        $user = User::with('ethnicOrigin')
            ->with('location')
            ->with('identificationType')
            ->with('sex')
            ->with('gender')
            ->findOrFail($id);
        return response()->json([
            'data' => [
                'user' => $user
            ]
        ], 200);

    }

    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|string',

        ]);
        $user = User::where('user_name', $request->user_name)->with('state')->first();

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
            $accessToken->token->expires_at = Carbon::now()->addMonth(1);
        }
        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'token' => $accessToken], 201);
    }

    public function createUser(Request $request)
    {
        $data = $request->json()->all();
        $dataUser = $data['user'];
        $user = new User();

        $user->identification = strtoupper(trim($dataUser['identification']));
        $user->user_name = trim($dataUser['user_name']);
        $user->first_name = strtoupper(trim($dataUser['first_name']));
        $user->first_lastname = strtoupper(trim($dataUser['first_lastname']));
        $user->birthdate = trim($dataUser['birthdate']);
        $user->email = strtolower(trim($dataUser['email']));
        $user->password = Hash::make(trim($dataUser['password']));

        $ethnicOrigin = Catalogue::findOrFail($dataUser['ethnic_origin']['id']);
        $location = Catalogue::findOrFail($dataUser['location']['id']);
        $identificationType = Catalogue::findOrFail($dataUser['identification_type']['id']);
        $sex = Catalogue::findOrFail($dataUser['sex']['id']);
        $gender = Catalogue::findOrFail($dataUser['gender']['id']);
        $state = Catalogue::where('code', '1')->first();
        $user->ethnicOrigin()->associate($ethnicOrigin);
        $user->location()->associate($location);
        $user->identificationType()->associate($identificationType);
        $user->sex()->associate($sex);
        $user->gender()->associate($gender);
        $user->state()->associate($state);
        $user->save();
        return response()->json(['message' => 'Usuario creado', 'user' => $user], 201);
    }

    public function updateUser(Request $request)
    {
        $data = $request->json()->all();
        $dataUser = $data['user'];
        $user = User::findOrFail($dataUser['id']);
        $user->identification = $dataUser['identification'];
        $user->user_name = strtoupper(trim($dataUser['user_name']));
        $user->first_name = strtoupper(trim($dataUser['first_name']));
        $user->first_lastname = strtoupper(trim($dataUser['first_lastname']));
        $user->birthdate = trim($dataUser['birthdate']);
        $user->email = strtolower(trim($dataUser['email']));

        $ethnicOrigin = Catalogue::findOrFail($dataUser['ethnic_origin']['id']);
        $location = Catalogue::findOrFail($dataUser['location']['id']);
        $identificationType = Catalogue::findOrFail($dataUser['identification_type']['id']);
        $sex = Catalogue::findOrFail($dataUser['sex']['id']);
        $gender = Catalogue::findOrFail($dataUser['gender']['id']);
        $state = Catalogue::where('code', '1')->first();
        $user->ethnicOrigin()->associate($ethnicOrigin);
        $user->location()->associate($location);
        $user->identificationType()->associate($identificationType);
        $user->sex()->associate($sex);
        $user->gender()->associate($gender);
        $user->state()->associate($state);
        $user->save();
        return response()->json(['message' => 'Usuario actualizado', 'user' => $user], 201);
    }

    public function destroyUser($id)
    {
        $state = Catalogue::where('code', '3')->first();
        $user = User::findOrFail($id);
        $user->state()->associate($state);
        $user->save();
        return response()->json(['message' => 'Usuario eliminado', 'user' => $user], 201);
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
