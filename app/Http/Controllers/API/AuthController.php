<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


  public function register(Request $request)
  {
    $payload = $request->only('name', 'email', 'password', 'confirm_password');
    $rules = [
      'name' => 'required|string|between:1,100',
      'email' => 'required|string|email|unique:users',
      'password' => 'required|string|min:8',
      'confirm_password' => 'required|same:password',
    ];

    $messages = [
      'name.required' => 'Por favor, ingrese su nombre.',
      'email.required' => 'Por favor, ingrese su correo electrónico.',
      'email.email' => 'Por favor, ingrese un correo electrónico válido.',
      'email.unique' => 'El correo electrónico ya está en uso.',
      'password.required' => 'Por favor, ingrese su contraseña.',
      'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
      'confirm_password.required' => 'Por favor, confirme su contraseña.',
      'confirm_password.same' => 'Las contraseñas no coinciden.',
    ];

    $validator = Validator::make($payload, $rules, $messages);

    if ($validator->fails()) {
      return $this->sendErrors('Validation error', $validator->errors());
    }

    $hash_password = bcrypt($payload['password']);

    User::create([
      'name' => $payload['name'],
      'email' => $payload['email'],
      'password' => $hash_password,
    ]);

    return response()->json(['message' => 'User registered successfully']);
  }

  public function login(Request $request)
  {
    $payload = $request->only('email', 'password');
    $rules = [
      'email' => 'required|string|email',
      'password' => 'required|string|min:8',
    ];
    $messages = [
      'email.required' => 'Por favor, ingrese su correo electrónico.',
      'email.email' => 'Por favor, ingrese un correo electrónico válido.',
      'password.required' => 'Por favor, ingrese su contraseña.',
      'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
    ];

    $validator = Validator::make($payload, $rules, $messages);

    if ($validator->fails()) {
      return $this->sendErrors('Validation error', $validator->errors());
    }


    $token = auth('api')->attempt($payload);

    if (!$token) {
      return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = auth('api')->user();

    $user->token = $token;

    return response()->json(['user' => $user]);
  }

  public function logout()
  {
    auth('api')->invalidate(true);

    return response()->json(['message' => 'Successfully logged out'])->withCookie(Cookie::forget('token'));
  }

  public function profile()
  {
    $user = auth('api')->user()->only('id', 'name', 'email');

    return response()->json(['user' => $user]);
  }
}
