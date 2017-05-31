<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'description',  'lat', 'lon', 'status'
    ];

    public function attachment()
    {
        return $this->belongsTo('App\Attachment');
    }
}
