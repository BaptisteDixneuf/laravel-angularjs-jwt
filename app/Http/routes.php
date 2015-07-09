<?php

use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response as HttpResponse;

/**
 * Displays Angular SPA application
 */
Route::get('/', function () {
    return view('spa');
});

/**
 * Registers a new user and returns a auth token
 */
Route::post('/signup','UserController@signup');

/**
 * Signs in a user using JWT
 */
Route::post('/signin', 'UserController@signin');

/**
 * Fetches a restricted resource from the same domain used for user authentication
 */
Route::get('/restricted', [
    'before' => 'jwt-auth',
    function () {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        return Response::json([
            'data' => [
            'email' => $user->email,
            'registered_at' => $user->created_at->toDateTimeString(),
            'role' => $user->role
            ]
            ]);
    }
    ]);

/**
 * Fetches a restricted resource from API subdomain using CORS
 */
Route::group(['domain' =>'192.168.59.103', 'prefix' => 'api/v1','middleware' => 'jwt.auth'], function () {

   Route::get('/restricted', function () {
        try {
            JWTAuth::parseToken()->toUser();
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
        }

        return ['data' => 'This has come from a dedicated API subdomain with restricted access.'];
    }); 

    Route::resource('/posts','PostController');
    

});

