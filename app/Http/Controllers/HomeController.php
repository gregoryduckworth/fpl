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
        ]);
    }

    public function standings()
    {
     	return view('standings')->with([
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