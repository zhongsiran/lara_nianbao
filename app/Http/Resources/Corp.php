<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Corp extends ResourceCollection
{
    protected $table = 'shiling_nianbao_corp';
    protected $primaryKey = 'RegNum';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timeStamp = false;
    
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
