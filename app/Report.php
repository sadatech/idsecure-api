<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'description',  'lat', 'lon', 'status', 'user_id', 'type'
    ];

    public function attachment()
    {
        return $this->belongsTo('App\Attachment');
    }
}
