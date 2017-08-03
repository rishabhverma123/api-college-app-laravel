<?php

namespace App\Http\Controllers;

use App\AdminMail;
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

        if(true)//$request['rollno'] == $user->rollno)
        {
            $userDetails = UserDescription::where('rollno',$user->rollno)->first();

            if($request->has('image'))
            {
                $file_name = $user->rollno. ".jpg";
                $path = public_path() . ConstantPaths::$PATH_PROFILE_IMAGES . $file_name;
                file_put_contents($path, base64_decode($request['image']));
            }

            else
            {
                if ($request->has('name'))
                    $userDetails->name = $request['name'];

                if ($request->has('phone'))
                    $userDetails->phone = $request['phone'];

                if ($request->has('email'))
                    $userDetails->email = $request['email'];

                if ($request->has('status'))
                    $userDetails->status = $request['status'];

                if ($request->has('councils'))
                    $userDetails->councils = $request['councils'];

                if ($request->has('skills'))
                    $userDetails->skills = $request['skills'];

                if ($request->has('hobbies'))
                    $userDetails->hobbies = $request['hobbies'];

                if ($request->has('bloodGroup'))
                    $userDetails->bloodGroup = $request['bloodGroup'];

                if ($request->has('homeCity'))
                    $userDetails->homeCity = $request['homeCity'];

                if ($request->has('fbLink'))
                    $userDetails->fbLink = $request['fbLink'];

                if ($request->has('linkedinLink'))
                    $userDetails->linkedinLink = $request['linkedinLink'];

                if ($request->has('githubLink'))
                    $userDetails->githubLink = $request['githubLink'];
            }
            $userDetails->save();

            return response()->json(['result'=>'success']);
        }
//        else
//            return response()->json(['result'=>'fail','error'=>'Unauthorized to modify']);
    }

    public function get_user_details(Request $request) //for details of logged in user
    {
        $user = JWTAuth::toUser($request['token']);
//        $user_details = UserDescription::where('rollno',$user->rollno)->first();
        if($user == null)
            return response()->json(['result' => 'fail','error'=>'invalid token']);

        $rawUserDetails = $user->userDescription;
        
        $userDetails['rollno'] = $rawUserDetails->rollno;
        $userDetails['imageUrl'] = file_exists(public_path().ConstantPaths::$PATH_PROFILE_IMAGES.$rawUserDetails->rollno.".jpg")
            ? ConstantPaths::$PUBLIC_PATH.ConstantPaths::$PATH_PROFILE_IMAGES.$rawUserDetails->rollno.".jpg" : '';
       
        $userDetails['branch'] = $rawUserDetails->branch ;
        $userDetails['batch'] = $rawUserDetails-> batch;
        $userDetails['email'] = $rawUserDetails->email;
        $userDetails['name'] = $rawUserDetails->name;
        $userDetails['phone'] = $rawUserDetails-> phone;
        $userDetails['status'] = $rawUserDetails-> status;
        $userDetails['councils'] = $rawUserDetails-> councils;
        $userDetails['hobbies'] = $rawUserDetails-> hobbies;
        $userDetails['skills'] = $rawUserDetails-> skills;
        $userDetails['bloodGroup'] = $rawUserDetails-> bloodGroup;
        $userDetails['homeCity'] = $rawUserDetails-> homeCity;
        $userDetails['fbLink'] = $rawUserDetails-> fbLink;
        $userDetails['githubLink'] = $rawUserDetails-> githubLink;
        $userDetails['linkedinLink'] = $rawUserDetails-> linkedinLink;

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
        $profDetails['imageUrl'] = file_exists(public_path().ConstantPaths::$PATH_PROFILE_IMAGES.$rawDescription->rollno.".jpg")
            ? ConstantPaths::$PUBLIC_PATH.ConstantPaths::$PATH_PROFILE_IMAGES.$rawDescription->rollno.".jpg"
            : ConstantPaths::$PUBLIC_PATH.ConstantPaths::$PATH_PROFILE_IMAGES."default.jpg";

        $profDetails['branch'] = $rawDescription->branch ;
        $profDetails['batch'] = $rawDescription-> batch;

        $profDetails['name'] = $rawDescription->name;
        $profDetails['email'] = $rawDescription->email;

        $profDetails['phone'] = $rawDescription-> phone;
        $profDetails['status'] = $rawDescription-> status;
        $profDetails['councils'] = $rawDescription-> councils;
        $profDetails['hobbies'] = $rawDescription-> hobbies;
        $profDetails['skills'] = $rawDescription-> skills;
        $profDetails['bloodGroup'] = $rawDescription-> bloodGroup;
        $profDetails['homeCity'] = $rawDescription-> homeCity;
        $profDetails['fbLink'] = $rawDescription-> fbLink;
        $profDetails['githubLink'] = $rawDescription-> githubLink;
        $profDetails['linkedinLink'] = $rawDescription-> linkedinLink;

        return response()->json(['result'=>'success','details'=>$profDetails]);
    } //for details of any registered user

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
            ->select('rollno','name','branch','batch')
            ->whereRaw('lower(name) like \'%'.$searchString.'%\'')
            ->get();

        return response()->json(['result'=>'success','records'=>$results]);
    }

    public function mail_to_admin(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [ //to validate all entries required
            'token' => 'required',
            'content' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $user = JWTAuth::toUser($request['token']);
        
        $mail = new AdminMail();
        $mail->author = $user->rollno;
        $mail->content = $request['content'];

        $mail->save();
        return response()->json(['result'=>'success']);
    }
}
