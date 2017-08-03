<?php

namespace App;


use phpDocumentor\Reflection\DocBlock\Tags\Author;

class Notification extends \Eloquent
{
    protected $table = 'notifications';
    public $timestamps = false;

    public function authorInstance()
    {
        return $this->belongsTo('App\Admin', 'author', 'id');
    }

}
