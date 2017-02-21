<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminMail extends Model
{
    protected $table = 'admin_mails';
    public $timestamps=false;
    public function authorDescription()
    {
        return $this->belongsTo('App\UserDescription','author','rollno');
    }
}
