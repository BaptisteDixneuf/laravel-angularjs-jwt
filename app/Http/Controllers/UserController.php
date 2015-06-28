<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response as HttpResponse;

class UserController extends Controller {

    /*
     * Permet de créer un compte
     */
	public function signup(){
        $credentials = Input::only('email', 'password','name');

        try {
            $user = User::create($credentials);
        } catch (Exception $e) {
            return Response::json(['error' => $credentials], HttpResponse::HTTP_CONFLICT);
        }

        $token = JWTAuth::fromUser($user);

        return Response::json(compact('token'));
    }

    /*
     * Permet de se connecter
     */
    public function signin(){
        $credentials = Input::only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
        }

        return Response::json(compact('token'));
    }



}
