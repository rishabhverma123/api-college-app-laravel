<?php
/**
 * Created by PhpStorm.
 * User: fragger
 * Date: 10/16/16
 * Time: 10:56 PM
 */

namespace App\Library;


use App\User;

class FCMHandler
{
    public static function updateToken($user, $token) //user is supposed to be an instance of User Model
    {
        $user->fcm_token = $token;
        $user->fcm_token_updated_at = date("Y-m-d H:i:s");
        $user->save();
    }

    public static function getToken($id) //to return token using the row id of a user
    {
        $user = User::findOrFail($id);
        return $user->fcm_token;
    }
}