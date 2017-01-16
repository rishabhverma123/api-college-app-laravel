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
        $validator = \Validator::make($input, [ //to validate all entries required
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'fail', 'error' => $validator->errors()]);
        }

        $newNewsFeed = new NewsFeed();
        if ($request->has('image')) {
            $file_name = time();
            $file_name = $file_name . ".jpg";
            $path = public_path() . ConstantPaths::$PATH_POST_IMAGES . $file_name;
            file_put_contents($path, base64_decode($input['image']));
            $newNewsFeed->image = $file_name;
        }


        $newNewsFeed->author = $user->id; //Needs revision perhaps - Shank
        $newNewsFeed->content = $input['content'];
        $newNewsFeed->save(['timestamps' => false]);


        //creating folder for storing resources related to this news feed
        mkdir(public_path() . ConstantPaths::$PATH_COMMENTS_IMAGES .$newNewsFeed->id, 0755, true);

        return response()->json(['result' => 'success']);
    }


    public function get_news_feeds(Request $request)
    {
        $log = new Log();
        $input = $request->all();
        $log->description = print_r($input, true);
        $log->save();


        $newsFeeds = null;
        $input = $request->all();
        if ($request->has('newestFeedID')) { //if user has asked for a refresh
            $newsFeeds = \DB::table('news_feeds')
                ->where('id', '>', $input['newestFeedID'])
                ->orderBy('id', 'desc')
                ->get();
        } else { //if user is loading feeds for the first time or asked for the next page feeds
            //Set the page content limit here
            $limit = 10;


            if (isset($input['lastFeedID'])) {
                $lastFeedID = $input['lastFeedID'];

                $newsFeeds = \DB::table('news_feeds')
                    ->where('id', '<', $lastFeedID)
                    ->orderBy('id','desc')
                    ->limit($limit)
                    ->get();

            } else {
                $newsFeeds = \DB::table('news_feeds')
                    ->orderBy('id', 'desc')
                    ->limit($limit)
                    ->get();
            }
        }

        $processedFeeds = array();
        $i=0;
        foreach ($newsFeeds as $newsFeed) {

            $authorDetails = User::find((int)$newsFeed->author)->userDescription;
            $authorImageLink = ConstantPaths::$PATH_PROFILE_IMAGES.
                $authorDetails->rollno.".jpg";

            $processedFeeds[$i] =
                [
                    'id' => $newsFeed->id,
                    'author' => $authorDetails->name,
                    'authorRollno' => $authorDetails->rollno,
                    'content' => $newsFeed->content,
                    'url' => $newsFeed->url,
                    'timestamp' => "" . strtotime($newsFeed->timestamp) . "000",
                    'authorImage' => file_exists(public_path().$authorImageLink) ?
                        ConstantPaths::$PUBLIC_PATH.$authorImageLink : '',

                    'image' => $newsFeed->image != ""? ConstantPaths::$PUBLIC_PATH
                        . ConstantPaths::$PATH_POST_IMAGES.($newsFeed->image) : "null",

                    'commentCount' => $newsFeed->commentCount,
                ];
            $i++;
        }

        return \Response::json(['result' => 'success', 'feed' => $processedFeeds], 200);

    }
}
