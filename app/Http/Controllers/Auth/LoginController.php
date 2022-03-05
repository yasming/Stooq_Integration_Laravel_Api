<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], HttpResponse::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        $user = auth()->user();
        return response()->json(['data' => compact('token', 'user')]);
    }
}
