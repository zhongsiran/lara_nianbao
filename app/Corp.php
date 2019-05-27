<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corp extends Model
{
    protected $table = '2019_nianbao_corp';
    // protected $table = '2019_nianbao_corp_copy';
    protected $primaryKey = 'RegNum';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timeStamp = false;
}
