<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HomeController extends BaseController
{
    public function index()
    {
     	return view('home')->with([
        	'biggest' => json_decode(file_get_contents(route('biggest')), true),
            'highest' => json_decode(file_get_contents(route('highest')), true),
            'lowest' => json_decode(file_get_contents(route('lowest')), true),
            'trader' => json_decode(file_get_contents(route('trader')), true),
        	'mvp' => json_decode(file_get_contents(route('mvp')), true),
        	'worst' => json_decode(file_get_contents(route('worst')), true),
        	'undervalued' => json_decode(file_get_contents(route('undervalued')), true),
        	'keeper' => json_decode(file_get_contents(route('keeper')), true),
        	'defender' => json_decode(file_get_contents(route('defender')), true),
        	'midfielder' => json_decode(file_get_contents(route('midfielder')), true),
        	'forward' => json_decode(file_get_contents(route('forward')), true),
            'longestWin' => json_decode(file_get_contents(route('longestwinstreak')), true),
            'longestLoss' => json_decode(file_get_contents(route('longestloststreak')), true),
        ]);
    }

    public function standings()
    {   
        $teams = json_decode(file_get_contents(route('teamnames')), true);
        $positions = [];
        foreach($teams as $key => $team) {
            $positions[$key]['name'] = $team;
            $positions[$key]['data'] = json_decode(file_get_contents(route('positions', [$team])), true);
        }
     	return view('standings')->with([
        	'positions' => $positions,
            'standings' => json_decode(file_get_contents(route('standings')), true),
        ]);
    }

    public function apiDetails() {
        return view('api-details');
    }

    public function info($teamID) {
        return view('info')->with([
            'versus' => json_decode(file_get_contents(route('getteamgames', ['teamID' => $teamID])), true),
            'scores' => json_decode(file_get_contents(route('geth2hgames', ['teamID' => $teamID])), true),
        ]);
    }
}