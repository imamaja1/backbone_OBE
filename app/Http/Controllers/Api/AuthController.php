<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ProgramStudi;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'role' => 'required',
            'key_ref' => 'required',
            'key_ref' => 'required',
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'key_ref' => $request->key_ref,
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['username', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
      // role 2 mahasiswa
        if ($request->user()->role == '2'){
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'response' => User::with('mahasiswa')->findOrFail($request->user()->id),
                // TODO::untuk keperluan mahasiswa sasing lama harus dimatikan dulu.(Pengurusan wisuda)
                // 'kode_nama_kurikulum' => kode_nama_kurikulum($request->user()->key_ref),
            ]);
        }elseif ($request->user()->role == '1'){
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'response' => User::with('pengguna')->findOrFail($request->user()->id),
            ]);
          // role 3 dosen
        }elseif ($request->user()->role == '3'){
            $dosen = User::with('dosen')->findOrFail($request->user()->id)->makeVisible(['password']);
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                'response' => $dosen,
                'prodi' => ProgramStudi::where('kode_program_studi', $dosen->dosen->homebase)->first('nama_program_studi'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $data = User::with('mahasiswa.program_studi','dosen')->findOrFail($request->user()->id);
//        $data = Mahasiswa::with('program_studi')->findOrFail('1210520126')->toArray();
        return response()->json($data);
    }
}
