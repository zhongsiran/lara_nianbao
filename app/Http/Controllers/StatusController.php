<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Corp;

class StatusController extends Controller
{
    public function status()
    {
    	$corps = DB::table('shiling_nianbao_corp')
    		->select('designated_person', 'type', 'Division')
    		->selectRaw('count(`nian_bao_status`= ? OR NULL) as `not_submitted`', ['未填报'])
    		->selectRaw('count(`nian_bao_status`= ? OR NULL) as `submitted`', ['已公示'])
    		->groupBy('designated_person', 'type', 'Division')
    		->orderBy('designated_person')
    		->get();

    	return view('corp.status', compact('corps'));
    }
}
