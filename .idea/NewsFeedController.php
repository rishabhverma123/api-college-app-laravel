<?php

namespace App\Http\Controllers;


use App\Library\ConstantPaths;
use App\Log;
use App\NewsFeed;
use App\User;
use App\UserDescription;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;

class NewsFeedController extends Controller
{
    public function post_news_feed(Request $request)
    {
        $user = JWTAuth::toUser($request['token']);
        $input = $request->all();
        $validator	=	\Validator::make($input,	[ //to validate all entries required
            'content' => 'required',
        ]);

        if	($validator->fails()) {
            return response()->json(['result'=>'fail','error'=>$validator->errors()]);
        }

        $newNewsFeed = new NewsFeed();
        if($request->has('image')) {
            $file_name = time();
            $file_name = $file_name.".jpg";
            $path = public_path().ConstantPaths::$PATH_POST_IMAGES.$file_name;
            file_put_contents($path,base64_decode($input['image']));
            $newNewsFeed->image = $file_name;
        }


        $newNewsFeed -> author = $user->id; //Needs revision perhaps - Shank
        $newNewsFeed -> content = $input['content'];
        $newNewsFeed -> timestamp = date("Y-m-d H:i:s");
        $newNewsFeed ->save();
        return response()->json(['result'=>'success']);
    }


    public function get_news_feeds(Request $request)
    {
        $log= new Log();
        $input = $request->all();
        $log->description = print_r($input,true);
        $log->save();


        $newsFeeds = null;
        $input = $request->all();
        if ($request->has('lastFeedID')) { //if user has asked for a refresh
            $newsFeeds = \DB::table('news_feeds')
                ->where('id', '>', $input['lastFeedID'])
                ->orderBy('id', 'desc')
                ->get();
        }

        else { //if user is loading feeds for the first time or asked for the next page feeds
            //Set the page content limit here
            $limit = 10;


            if (!isset($input['page']))
                $page = 1;
            else
                $page = $input['page'];
            $offset = ($page - 1) * ($limit); //for pagination - confirm nahi hai kaisa chalega

            $newsFeeds = \DB::table('news_feeds')
                ->offset($offset)
                ->limit($limit)
                ->orderBy('id','desc')
                ->get();
        }

        foreach ($newsFeeds as $newsFeed) {
            $newsFeed->timestamp = "" . strtotime($newsFeed->timestamp) . "000";
            $newsFeed->author = User::find((int)$newsFeed->author)->userDescription->name;

            if ($newsFeed->image != "") {
                $newsFeed->image = 'http://192.168.43.32:80/laravel-projects/webapi/public_html' . ConstantPaths::$PATH_POST_IMAGES . $newsFeed->image;
            } else {
                $newsFeed->image = "null";
            }
        }

        return \Response::json(['result' => 'success', 'feed' => $newsFeeds], 200);

    }
}
