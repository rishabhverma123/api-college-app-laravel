<?php

namespace App\Http\Controllers;

use App\Library\ConstantParams;
use App\Library\ConstantPaths;
use App\Log;
use App\QuestionPaper;
use App\Resume;
use Illuminate\Http\Request;

use App\Http\Requests;

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
        $path = $filePath .$name.".".$extension;
        //die("$path");
        file_put_contents($path, file_get_contents($file));

        $resume = new Resume();
        $resume->name = $name;
        $resume->batch = $request['batch'];
        $resume->branch = $request['branch'];
        $resume->filename = $name.".".$extension;

        $resume->save();
        return redirect()->route('admin.getAddResume');

    }

    //API Code begins for querying and returning JSONs

    public function getQP(Request $request)
    {
        $input = $request->all();

        $log = new Log();
        $log->description = print_r($input, true);
        $log->save();

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
            ->orderBy('id', 'desc')
            ->get();

        $papers = null;
        $i = 0;
        foreach ($results as $result) {
            $papers[$i] = [
                'id' => $result->id,
                'pcode' => $result->code,
                'pname' => ConstantParams::$subjects[$result->code],
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
            ->get();

        $resultArray = null;
        $i = 0;
        foreach ($resumes as $resume) {
            $resultArray[$i] = [
                'id' => $resume->id,
                'name' => $resume->name,
                'batch' => $resume->batch,
                'branch' => ConstantParams::$branchNames[$resume->branch],
                'type' => $resume->type,
                'url' => ConstantPaths::$PUBLIC_PATH.ConstantPaths::$PATH_RESUMES.$resume->filename,
                'time' => strtotime($resume->created_at) . "000"
            ];
            $i++;
        }
        //print_r($papers);
        return response()->json(['result' => 'success', 'resume' => $resultArray]);

    }

    public function uploadResume(Request $request)
    {
        
    }
}
