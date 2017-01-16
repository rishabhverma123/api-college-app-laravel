<?php

namespace App;


class AdminMessage extends \Eloquent
{
    protected $table = 'admin_chats';
    public $timestamps=false;

    public function authorDescription()
    {
        return $this->belongsTo('App\Admin','author','id');
    }
}
