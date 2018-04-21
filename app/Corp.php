<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corp extends Model
{
    protected $table = 'shiling_nianbao_corp';
    protected $primaryKey = 'RegNum';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timeStamp = false;
}
