<?php

namespace App\Http\Controllers;

use App\Library\ConstantPaths;
use App\Library\Notifications\Firebase;
use App\Library\Notifications\PushNotification;
use App\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;

class NotificationsController extends Controller
{
    public function postIncrementDeliveredCount(Request $request)
    {
        $notif = Notification::findOrFail($request['notifID']);
        $notif->count_delivered += 1;
        $notif->save();
        return \Response::json(['result' => 'success']);
    }

    public function postIncrementOpenedCount(Request $request)
    {
        $notif = Notification::findOrFail($request['notifID']);
        $notif->count_opened += 1;
        $notif->save();
        return \Response::json(['result' => 'success']);
    }

    public function postCreateNotification(Request $request)
    {
        $notif  = new Notification();
        $push = new PushNotification();

        $notif->author = \Auth::guard('admins')->user()->id;
        // optional payload
        $notif->save();

        $payload = array();
        $payload['notifID'] = $notif->id; //bina payload ke nhi handle karega app
        $payload['isTypeWebView'] = $request['isTypeWebView']=='1' ? true : false;
        $payload['onOpenWebViewUrl'] = $request['isTypeWebView']=='1' ? $request['webViewUrl'] : '';
        $payload['content'] = $request['isTypeWebView'] != '1' ? $request['content'] : '';
        $payload['setOngoing'] = $request['setOngoing']=='1' ? true : false;

        $notif->payload = print_r($payload, true);

        // notification title
        $title = $request['title'];
        $notif->title = $title;

        // notification message
        $message = $request['message'];
        $notif->message = $message;

        // push type - single user / topic
        $push_type = $request['push_type'];
        $notif->meta_audience = $push_type;
        // whether to include to image or not


        $image_file_name="";

        $push->setTitle($title);
        $push->setMessage($message);
        if ($request->hasFile('image')) {
            $image_file = $request->file('image');
            $image_file_name = time().".".$image_file->getClientOriginalExtension();
            $image_path = public_path().ConstantPaths::$PATH_NOTIFICATION_IMAGES.$image_file_name;
            file_put_contents($image_path, file_get_contents($image_file));
            $push->setImage(ConstantPaths::$PUBLIC_PATH.ConstantPaths::$PATH_NOTIFICATION_IMAGES.$image_file_name);
            $notif->image = $image_file_name;
        }
        else if($request->has('imageurl'))
        {
            $imageurl = $request['imageurl'];
            $push->setImage($imageurl);
            $notif->image = $imageurl;
        }
        else {
            $push->setImage('');
            $notif->image = '';
        }
        $push->setIsBackground(FALSE);
        $push->setPayload($payload);


        $json = '';
        $response = '';

        if ($push_type == 'global') {
            $json = $push->getPush();
        }

        $notif->sent = false;
        $notif->count_delivered = 0;
        $notif->count_opened = 0;
        $notif->save();


//        print_r($json);
//        print_r($response_json);

        return \Redirect::route('admin.getAddNotification')->with('prepared_json',$json)->with('id',$notif->id);
    }

    public function postConfirmSendNotification(Request $request)
    {
        $prepared_json = json_decode($request['prepared_json'],true);
//        print_r($prepared_json);
//        print_r($request['prepared_json']);
//
        $notif = Notification::findOrFail($request['id']);
        $firebase = new Firebase();
        $response_json = $firebase->sendToTopic('global', $prepared_json);
//        print_r($response_json);

        $notif->sent = true;
        $notif->message_id = json_decode($response_json)->message_id;
        $notif->save();

        return \Redirect::route('admin.getAddNotification')->with('response_json',$response_json);
    }

    public function postGetNotifications(Request $request)
    {
        $limit = 5; //Number of notifications to send

        $rawNotifList = \DB::table('notifications')
            ->orderBy('id', 'desc')
            ->where('sent','=','1')
            ->limit($limit)
            ->get();

        $notifArray = array();
        $i=0;

        foreach ($rawNotifList as $rawNotif)
        {
            $notifArray[$i]=[
                'id' => $rawNotif->id,
                'title' => $rawNotif->title,
                'message' => $rawNotif->message,
                'payload' => $rawNotif->payload,
                'timestamp' => $rawNotif->created_at,
            ];
            $i++;
        }

        return response()->json(['result'=>'success','notifications'=>$notifArray]);
    }

}
