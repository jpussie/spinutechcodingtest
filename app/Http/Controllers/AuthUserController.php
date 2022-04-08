<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    public function createUser(Request $request){
        // return $request;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return array('status' => 'success',
                    'message' => 'successfully created');
    }
    public function login(Request $request){
        // return $request;
        $users = User::where('email', $request->email)->get();
        return $users;
        if(count($users)){
            $authenticateable = false;
            foreach($users as $user) {
                if(Hash::check($request->password, $user->password)) {
                    $authenticateable = $user;
                    break;
                }else{
                    $authenticateable = false;
                }
            }
            if($authenticateable) {
                    // Login
                // return $request;
                // return Auth::attempt($credentials);
                $credentials = array(
                    'email' => $request->email,
                    'password' => $request->password
                );

                if(!Auth::loginUsingId(($authenticateable->id)))
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Unauthorized User'
                    ], 200);
                // // return 'i am here';
                // return
                $user = $request->user();

                // return $authenticateable;
                if ($user->verified == 0 || $user->verified == "0") {
                    return response()->json([
                            'status' => 'fail',
                            'message' => 'Your email is not verified! We sent you a verification mail, please check your email.'
                        ], 200);
                }
                if ($user->activated == 0 || $user->activated == "0") {
                    return response()->json([
                            'status' => 'fail',
                            'message' => 'Your account is disabled! Please contact your administrator.'
                        ], 200);
                }

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();
                $super_admin = null;
                if (Auth::user()->user_type == 'master_admin' || Auth::user()->user_type == 'super_admin_console') {
                    $url = null;
                    $auth_user_id = null;
                    if(Auth::user()->user_type == 'super_admin_console'){
                        $super_admin = SuperAdmin::select(['user_id','role','permission'])->where('user_id', Auth::user()->id)->first();
                    }
                }else{
                    $loginuser = LoginUser::where('user_id', Auth::user()->id)->first();
                    $url = $loginuser->crm_url;
                    $auth_user_id = $loginuser->auth_user_id;
                }

                return response()->json([
                    'status' => 'success',
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'url'   => $url,
                    'auth_user_id' => $auth_user_id,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                    'id'=>Auth::id(),
                    'user_type'=> Auth::user()->user_type,
                    'email' => Auth::user()->email,
                    'super_admin' => $super_admin
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unauthorized User'
                ], 200);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized User'
            ], 200);
        }
    }
}
