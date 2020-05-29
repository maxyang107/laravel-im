<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfflineMsg extends Model
{
    //
    use SoftDeletes;
    protected $table      = 'chart_offline_msg';
    public    $timestamps = false;
    protected $dates = ['deleted_at'];
    protected $fillable = ['push_type', 'push_id','message','create_at'];
}
