<?php

namespace App\Http\Controllers;

use App\AdminMessage;
use App\Library\ConstantPaths;
use App\NewsFeed;
use App\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


use App\Http\Requests;

class AdminsController extends Controller
{
    public function getHistoryNotifications()
    {
        $notificationsRaw = Notification::orderBy('id','desc')
            ->take(10)
            ->get();
        $notifications=array();
        $i=0;
        foreach ($notificationsRaw as $notificationRaw)
        {
            $notifications[$i]=[
                'id'=>$notificationRaw->id,
                'sent'=>$notificationRaw->sent,
                'title'=>$notificationRaw->title,
                'message'=>$notificationRaw->message,
                'image'=>$notificationRaw->image,
                'message_id'=>$notificationRaw->message_id,
                'payload'=>$notificationRaw->payload,
                'count_delivered'=>$notificationRaw->count_delivered,
                'count_opened'=>$notificationRaw->count_opened,
                'author'=>($notificationRaw->authorInstance->name),
                'audience'=>$notificationRaw->meta_audience,
                'timestamp'=>$notificationRaw->created_at,

            ];
            $i++;
        }

        return view('allwebviews.admin.notif.history',compact('notifications'));
    }

    public function getCreateNotification()
    {
        return view('allwebviews.admin.notif.add');
    }

    public function getHome()
    {
        $adminMsgsRaw = AdminMessage::orderBy('created_at','desc')->take(6)->get();
        $i = 0;
        $adminMessages = null;
        foreach ($adminMsgsRaw as $adminMsg) {
            $adminMessages[$i] = [
                'author' => $adminMsg->authorDescription->name,
                'message' => $adminMsg->content,
                'timeString' => Carbon::parse($adminMsg->created_at)->diffForHumans(),
            ];
            $i++;
        }

        $newsFeedsRaw = NewsFeed::orderBy('id','desc')->take(6)->get();
        $newsFeeds=array();
        $i=0;
        foreach ($newsFeedsRaw as $newsFeed) {
            $newsFeeds[$i] = [
                'author' => User::find((int)$newsFeed->author)->userDescription->name,
                'content'=>$newsFeed->content,
                'timeString' => Carbon::parse($newsFeed->timestamp)->diffForHumans(),
                'image' => $newsFeed->image != ""?ConstantPaths::$PUBLIC_PATH.
                    ConstantPaths::$PATH_POST_IMAGES . $newsFeed->image:"null",
                ];
            $i++;
        }

//        print_r($adminMessages);
        return view('allwebviews.admin.home', compact('adminMessages','newsFeeds'));
    }

    public function getAdminLogin()
    {
        return view('allwebviews.admin.login');
    }

    public function postAdminLogin(Request $request)
    {
        $credentials = [
            'username' => $request['username'],
            'password' => $request['password']
        ];

        if (\Auth::guard('admins')->attempt($credentials)) {
            return redirect()->route('admin.getHome');
        } else
            return redirect()->route('admin.getLogin', 'err=incorrect');
    }

    public function getLogout()
    {
        \Auth::guard('admins')->logout();
        return redirect()->route('admin.getLogin');
    }

    public function postNewAdminMessage(Request $request)
    {
        $adminMsg = new AdminMessage();
        $adminMsg->author = \Auth::guard('admins')->user()->id;
        $adminMsg->content = $request['message'];
        $adminMsg->created_at = date("Y-m-d H:i:s");
        $adminMsg->save();
        return redirect()->route('admin.getHome');
    }

}
