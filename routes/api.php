<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', 'API\APIController@getAPI')->name('api');
Route::get('teams', 'API\APIController@getTeams')->name('teams');
Route::get('matches', 'API\APIController@getMatches')->name('matches');
Route::get('draft', 'API\APIController@getDraft')->name('draft');
Route::get('all-teams', 'API\APIController@getAllTeamInfo')->name('all-teams');
Route::get('trader', 'API\APIController@getTraders')->name('trader');
Route::get('results/{gameweek?}', 'API\APIController@getResults')->name('results');
Route::get('biggest', 'API\APIController@getBiggest')->name('biggest');
Route::get('highest', 'API\APIController@getHighest')->name('highest');
Route::get('lowest', 'API\APIController@getLowest')->name('lowest');
Route::get('getteamgames/{team}', 'API\APIController@getTeamGames')->name('getteamgames');
Route::get('geth2hgames/{team}', 'API\APIController@getHeadToHead')->name('geth2hgames');
Route::get('mvp', 'API\APIController@getMVP')->name('mvp');
Route::get('undervalued', 'API\APIController@getUndervalued')->name('undervalued');
Route::get('standings', 'API\APIController@getStandings')->name('standings');
Route::get('worst', 'API\APIController@getWorst')->name('worst');
Route::get('static-teams', 'API\APIController@getStaticTeams')->name('static-teams');
Route::get('keeper', 'API\APIController@getBestKeeper')->name('keeper');
Route::get('defender', 'API\APIController@getBestDefender')->name('defender');
Route::get('midfielder', 'API\APIController@getBestMidfielder')->name('midfielder');
Route::get('forward', 'API\APIController@getBestForward')->name('forward');