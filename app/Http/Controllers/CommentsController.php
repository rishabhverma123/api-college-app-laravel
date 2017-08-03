<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Library\ConstantPaths;
use App\NewsFeed;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentsController extends Controller
{
    //
    public function edit_comment(Request $request)
    {

        $input = $request->all();
        $validator = \Validator::make($input, [ //to validate all entries required
            'commentID' => 'required',
            'newContent' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $commentID = $request['commentID'];
        $user = JWTAuth::toUser($request['token']);

        $comment = Comment::where('id', '=', $commentID)->first();

        if($comment == null)
            return response()->json(['result' => 'fail', 'error' => 'No such comment']);

        if($comment->author != $user->id)
            return response()->json(['result' => 'fail', 'error' => 'Unauthorized']);

        $comment -> content = $request['newContent'];

        $comment->save();

        return response()->json(['result' => 'success']);
    }

    public function delete_comment(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [ //to validate all entries required
            'commentID' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $commentID = $request['commentID'];
        $user = JWTAuth::toUser($request['token']);

        $comment = Comment::where('id','=',$commentID)->first();

        if($comment == null)
            return response()->json(['result' => 'fail', 'error' => 'No such comment']);
        if($comment->author == $user->id)
            $comment->delete();
        else
            return response()->json(['result' => 'fail', 'error' => 'Unauthorized']);

        $assocFeed = NewsFeed::findOrFail($comment->feedId);

        $assocFeed->commentCount--;
        $assocFeed->save();

        return response()->json(['result' => 'success']);
    }

    public function post_comment(Request $request)
    {
        $user = JWTAuth::toUser($request['token']);
        $input = $request->all();
        $validator = \Validator::make($input, [ //to validate all entries required
            'content' => 'required',
            'feedID' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $comment = new Comment();

        $comment -> author = $user->id;
        $comment -> feedId = $request['feedID'];
        $comment -> content = $request['content'];
        if ($request->has('image')) {
            $file_name = time();
            $file_name = $file_name . ".jpg";
            $path = public_path() . ConstantPaths::$PATH_COMMENTS_IMAGES .$request['feedID'].'/' .$file_name;
            file_put_contents($path, base64_decode($input['image']));
            $comment->image = $file_name;
        }
        $comment->save();



        $assocFeed = NewsFeed::findOrFail($request['feedID']);

        $assocFeed->commentCount++;
        $assocFeed->save();

        return \Response::json(['result' => 'success'], 200);
    }
    
    public function get_comments(Request $request)
    {
        $input = $request->all();
        //return \Response::json($input, 200);

        $user = JWTAuth::toUser($request['token']);

        $validator = \Validator::make($input, [ //to validate all entries required
            'feedID' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $feedID = $request['feedID'];

        $rawComments = \DB::table('comments')
            ->select('id','content','author','image','created_at')
            ->whereRaw('feedId = '.$feedID)
            ->orderBy('id','desc')
            ->get();

        $comments = array();
        $i=0;
        foreach ($rawComments as $comment)
        {
            $authorDetails = User::find((int)$comment->author)->userDescription;
            $authorImageLink = ConstantPaths::$PATH_PROFILE_IMAGES.
                $authorDetails->rollno.".jpg";

            $comments[$i] = [
                'id' => $comment->id,
                'content' => $comment->content,
                'isEditable' => $comment->author == $user->id ? true:false,
                'imageUrl' => $comment->image != ""? ConstantPaths::$PUBLIC_PATH
                    .ConstantPaths::$PATH_COMMENTS_IMAGES.$feedID
                    . '/' . $comment->image : "null",
                'authorName' => $authorDetails->name,
                'authorRollno' => $authorDetails->rollno,
                'authorImageLink' => file_exists(public_path().$authorImageLink) ?
                    ConstantPaths::$PUBLIC_PATH.$authorImageLink : '',
                'timestamp' => "" . strtotime($comment->created_at) . "000",
            ];
            $i++;
        }

        return \Response::json(['result' => 'success', 'comments' => $comments], 200);
    }
}
