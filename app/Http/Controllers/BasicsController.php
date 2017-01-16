<?php

namespace App\Http\Controllers;

use App\Library\ConstantPaths;
use App\UserDescription;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Validation\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class BasicsController extends Controller
{

    public function update_profile(Request $request)
    {
//        $validator	=	\Validator::make($request->all(),	[
//            'rollno'=>'required'
//        ]);
//        if	($validator->fails()) {
//            return response()->json(['result'=>'fail','error'=>$validator->errors()]);
//        }
        $user = JWTAuth::toUser($request['token']);

        if($request['rollno'] == $user->rollno)
        {
            $userDetails = UserDescription::where('rollno',$user->rollno)->first();

            if($request->has('name'))
                $userDetails->name = $request['name'];

            if($request->has('phone'))
                $userDetails->phone = $request['phone'];

            if($request->has('email'))
                $userDetails->email = $request['email'];

            if($request->has('image'))
            {
                $file_name = $user->rollno. ".jpg";
                $path = public_path() . ConstantPaths::$PATH_PROFILE_IMAGES . $file_name;
                file_put_contents($path, base64_decode($request['image']));
            }

            $userDetails->save();

            return response()->json(['result'=>'success']);
        }
        //else
            //return response()->json(['result'=>'fail','error'=>'Unauthorized to modify']);
    }

    public function get_user_details(Request $request)
    {
        $user = JWTAuth::toUser($request['token']);
//        $user_details = UserDescription::where('rollno',$user->rollno)->first();
        if($user == null)
            return response()->json(['result' => 'fail','error'=>'invalid token']);

        $rawUserDetails = $user->userDescription;
        
        $userDetails['rollno'] = $rawUserDetails->rollno;
        $userDetails['name'] = $rawUserDetails->name;
        $userDetails['email'] = $rawUserDetails->email;
        $userDetails['imageUrl'] = file_exists(public_path().ConstantPaths::$PATH_PROFILE_IMAGES.$rawUserDetails->rollno.".jpg")
            ? ConstantPaths::$PUBLIC_PATH.ConstantPaths::$PATH_PROFILE_IMAGES.$rawUserDetails->rollno.".jpg" : '';
       
        $userDetails['branch'] = $rawUserDetails->branch ;
        $userDetails['batch'] = $rawUserDetails-> batch;
        return response()->json(['result' => 'success','details'=>$userDetails]);
    }

    public function get_profile(Request $request)
    {
        $validator	=	\Validator::make($request->all(),	[
            'rollno'=>'required'
        ]);
        if	($validator->fails()) {
            return response()->json(['result'=>'fail','error'=>$validator->errors()]);
        }

        $rawDescription = UserDescription::where('rollno',$request['rollno'])->first();

        $profDetails['rollno'] = $rawDescription->rollno;
        $profDetails['name'] = $rawDescription->name;
        $profDetails['email'] = $rawDescription->email;
        $profDetails['imageUrl'] = file_exists(public_path().ConstantPaths::$PATH_PROFILE_IMAGES.$rawDescription->rollno.".jpg")
            ? ConstantPaths::$PUBLIC_PATH.ConstantPaths::$PATH_PROFILE_IMAGES.$rawDescription->rollno.".jpg" : '';

        $profDetails['branch'] = $rawDescription->branch ;
        $profDetails['batch'] = $rawDescription-> batch;

        return response()->json(['result'=>'success','details'=>$profDetails]);
    }

    public function get_search(Request $request)
    {
        $validator	=	\Validator::make($request->all(),	[
            'string'=>'required'
        ]);
        if	($validator->fails()) {
            return response()->json(['result'=>'fail','error'=>$validator->errors()]);
        }

        $searchString = strtolower($request['string']);

        $results = \DB::table('users_desc')
            ->select('rollno','name','branch')
            ->whereRaw('lower(name) like \'%'.$searchString.'%\'')
            ->get();

        return response()->json(['result'=>'success','records'=>$results]);
    }
}
