<?php

namespace App\Http\Controllers;

use App\Library\ConstantParams;
use App\Library\FCMHandler;
use App\Log;
use App\UserDescription;
use Illuminate\Http\Request;
use App\User;
use Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class APIController extends Controller
{

    public function register(Request $request)
    {
        $log = new Log();
        $input = $request->all();
        $log->description = print_r($input, true);
        $log->save();

        //separate array banani hai safe rahegi


        $validator = \Validator::make($request->all(), [ //to validate all entries required
            'rollno' => 'required',
            'password' => 'required',
            'name' => 'required',
            'email' => 'required',
            'branch' => 'required',
            'batch' => 'required',
        ]);
        $input = $request->all();
        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        //check for existing user
        $existingUser = \DB::table('users')
            ->where('rollno', '=', $input['rollno'])
            ->count();
        //die($existingUser);
        if ($existingUser > 0)
            return response()->json(['result' => 'fail', 'error' => 'User exists']);

        //Sign Up procedure starts
        $input['password'] = Hash::make($input['password']);
        $user_description = new UserDescription();

        $user_description->rollno = $input['rollno'];

        $user_description->name = ucwords($input['name']);  //converting first letters to uppercase

        $user_description->email = $input['email'];

        $user_description->batch = $input['batch'];

        if(is_numeric($input['branch']))
            $user_description->branch = ConstantParams::$branchNames[$input['branch']];
        else
            $user_description->branch = $input['branch'];


        if (isset($input['phone'])) $user_description->phone = $input['phone'];

        $user_description->save();

        User::create($input);

        return response()->json(['result' => 'success']);
    }

    public function login(Request $request)
    {
        $log = new Log();
        $input = $request->all();
        $log->description = print_r($input, true);
        $log->save();
        $validator = \Validator::make($request->all(), [ //to validate all entries required
            'rollno' => 'required',
            'password'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $credentials = [
            'rollno' => $input['rollno'],
            'password' => $input['password'],
            ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['result' => 'fail', 'error' => 'Wrong id or password']);
        }

        if (isset($input['fcmToken'])) {
            User::where('rollno', $input['rollno'])->update(['remember_token' => $token, 'fcm_token' => $input['fcmToken'],
                'fcm_token_updated_at' => date("Y-m-d H:i:s")]);
        }
        else {
            User::where('rollno', $input['rollno'])->update(['remember_token' => $token]);
        }

        return response()->json(['result' => 'success', 'token' => $token]);
    }

    public function updateFCMToken(Request $request)
    {
        $validator = \Validator::make($request->all(), [ //to validate all entries required
            'fcmToken' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        FCMHandler::updateToken(JWTAuth::toUser($request['token']), $request['fcmToken']);
        return response()->json(['result' => 'success']);
    }

}