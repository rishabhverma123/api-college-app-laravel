<?php

namespace App\Http\Controllers;

use App\Library\ConstantParams;
use App\Library\ConstantPaths;
use App\Log;
use App\QuestionPaper;
use App\Resume;
use App\UserDescription;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;

class DocumentsController extends Controller
{
    //Admin Section Views - Adding and managing question papers
    public function getAddQP()
    {
        return view('allwebviews.admin.documents.questionPapers.add');
    }

    public function postAddQP(Request $request)
    {
        $file = $request->file('file');
        $name = $request->file('file')->getClientOriginalName();
        $filePath = public_path() . ConstantPaths::$PATH_QUESTION_PAPERS;
        $path = $filePath . "$name";
        //die("$path");
        file_put_contents($path, file_get_contents($file));

        $qp = new QuestionPaper();
        $qp->code = $request['code'];
        $qp->semester = $request['semester'];
        $qp->branch = $request['branch'];
        $qp->contributor = $request['contributor'];
        $qp->filename = $name;

        $qp->save();

    }

    public function getAddResume()
    {
        return view('allwebviews.admin.documents.resumes.add');
    }

    public function postAddResume(Request $request)
    {
        $file = $request->file('file');
        $name = $request['name'];
        $extension = $request->file('file')->getClientOriginalExtension();
        $filePath = public_path() . ConstantPaths::$PATH_RESUMES;
        $path = $filePath . $name . "." . $extension;
        //die("$path");
        file_put_contents($path, file_get_contents($file));

        $resume = new Resume();
        $resume->name = $name;
        $resume->batch = $request['batch'];
        $resume->branch = $request['branch'];
        $resume->filename = $name . "." . $extension;

        $resume->save();
        return redirect()->route('admin.getAddResume');

    }

    //API Code begins for querying and returning JSONs

    public function getQP(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [ //to validate all entries required
            'semester' => 'required',
            'branch' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $results = \DB::table('question_papers')
            ->where('semester', '=', $input['semester'])
            ->where('branch', '=', $input['branch'])
            //->where('isValid', '=', true)
            ->orderBy('subject', 'desc')
            ->get();

        $papers = null;
        $i = 0;
        foreach ($results as $result) {
            $papers[$i] = [
                'id' => $result->id,
                'subject' => $result->subject,
                'url' => ConstantPaths::$PUBLIC_PATH . ConstantPaths::$PATH_QUESTION_PAPERS . $result->filename,
                'type' => $result->type,
                'contributor' => $result->contributor,
                'time' => strtotime($result->created_at) . "000",
            ];
            $i++;
        }
        //print_r($papers);
        return response()->json(['result' => 'success', 'papers' => $papers]);
    }

    public function getResume(Request $request)
    {
        $limit = 10;
        if (!isset($request['page']))
            $page = 1;
        else
            $page = $request['page'];

        $offset = ($page - 1) * ($limit); //for pagination - confirm nahi hai kaisa chalega

        $resumes = \DB::table('resume')
            ->offset($offset)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->where('isVerified', true)
            ->get();

        $resultArray = null;
        $i = 0;
        foreach ($resumes as $resume) {
            $resultArray[$i] = [
                'id' => $resume->id,
                'name' => $resume->name,
                'batch' => $resume->batch,
                'branch' => $resume->branch,
                'type' => $resume->type,
                'url' => ConstantPaths::$PUBLIC_PATH . ConstantPaths::$PATH_RESUMES . $resume->filename,
                'time' => strtotime($resume->created_at) . "000"
            ];
            $i++;
        }
        //print_r($papers);
        return response()->json(['result' => 'success', 'resume' => $resultArray]);

    }

    public function uploadResume(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [ //to validate all entries required
            'pdf' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $user = JWTAuth::toUser($request['token']);
        $userDetails = UserDescription::where('rollno', $user->rollno)->first();
        $rollno = $userDetails->rollno;

        //getting the file extension
        $extension = 'pdf';

        $upload_url = public_path() . ConstantPaths::$PATH_RESUMES;

        //file name to store in the database
        $filename = $rollno . '.' . $extension;

        //file path to upload in the server
        $file_path = $upload_url . $filename;

        //trying to save the file in the directory
        try {
            //saving the file
            $file = $request->file('pdf');
            file_put_contents($file_path, file_get_contents($file));

            $resume = Resume::where('rollno', $rollno)->first();

            if ($resume == null) {
                $resume = new Resume();

                $resume->rollno = $rollno;
                $resume->name = $userDetails->name;
                $resume->branch = $userDetails->branch;
                $resume->batch = $userDetails->batch;
            }

            $resume->filename = $filename;
            $resume->isVerified = false;
            $resume->save();

            $response['result'] = 'success';

            //if some error occurred
        } catch (Exception $e) {
            $response['result'] = 'fail';
        }

        return response()->json($response);
    }

    public function uploadQP(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [ //to validate all entries required
            'token' => 'required',
            'pdf' => 'required',
            'semester' => 'required',
            'branch' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $user = JWTAuth::toUser($request['token']);
        $userDetails = UserDescription::where('rollno', $user->rollno)->first();

        //getting the file extension
        $extension = 'pdf';

        $upload_url = public_path() . ConstantPaths::$PATH_QUESTION_PAPERS;

        //file name to store in the database
        $filename = time() . '.' . $extension;

        //file path to upload in the server
        $file_path = $upload_url . $filename;

        //trying to save the file in the directory
        try {
            //saving the file
            $file = $request->file('pdf');
            file_put_contents($file_path, file_get_contents($file));

            $qp = new QuestionPaper();

            $qp->contributor = $userDetails->name;
            $qp->filename = $filename;
            $qp->subject = $request['subject'];
            $qp->branch = $request['branch'];
            $qp->semester = $request['semester'];


            $qp->isVerified = false;
            $qp->save();

            $response['result'] = 'success';

            //if some error occurred
        } catch (Exception $e) {
            $response['result'] = 'fail';
        }

        return response()->json($response);
    }
}
