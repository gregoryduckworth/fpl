<?php

namespace App\Http\Controllers\API;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;

class APIController extends BaseController
{
    private $leagueID = 43009;
    private $minutesPlayed = 1800;
    private $cacheTime = 3600; // 60 minutes

    /**
     * Get all the information about the league
     * @return type
     */
    public function getAPI() {
        if (!Cache::has('api')){
            Cache::put('api', json_decode(file_get_contents('https://draft.premierleague.com/api/league/' . $this->leagueID . '/details'), true), $this->cacheTime);
        }
        return Cache::get('api');
    }

    /**
     * Get all the static information available to the site
     * @return type
     */
    public function getStatic() {
        if (!Cache::has('static')){
            Cache::put('static', json_decode(file_get_contents('https://draft.premierleague.com/api/bootstrap-static'), true), $this->cacheTime);
        }
        return Cache::get('static');
    }

    /**
     * Return information about the static teams (Actual RL teams in the League, eg: Arsenal, Bournemouth etc)
     * @return type
     */
    public function getStaticTeams() {
        $json = $this->getStatic();
        $teams = $json['teams'];
        $team_info = [];
        foreach($teams as $key => $team) {
            $team_info[$key]['id'] = $teams[$key]['id'];
            $team_info[$key]['name'] = $teams[$key]['name'];
        }
        return $team_info;
    }

    /**
     * Get all the teams in the Draft League
     * @return type
     */
    public function getTeams() {
        $json = $this->getAPI();
        return $json['league_entries'];
    }

    public function getTeamNames() {
        $json = $this->getTeams();
        $teamnames = [];
        foreach($json as $team) {
            $teamnames[] = $team['entry_name'];
        }
        return $teamnames;
    }

    /**
     * Return all the matches with basic information
     * @return type
     */
    public function getMatches() {
        $json = $this->getAPI();
        return $json['matches'];
    }

    /**
     * Get information around every single player who has played in the draft (RL players, eg: Sterling, Aguero etc)
     * @return type
     */
    public function getDraft() {
        $json = $this->getStatic();
        return $json['elements'];
    }

    /**
     * Get specific information about a team based on their ID
     * @param type $teamID 
     * @return type
     */
    public function getTeamInfo($teamID) {
        if (!Cache::has($teamID)){
            Cache::put($teamID, json_decode(file_get_contents('https://draft.premierleague.com/api/entry/' . $teamID . '/public'), true), $this->cacheTime);
        }
        return Cache::get($teamID);
    }

    /**
     * Get all the information available about the teams in the league
     * @return type
     */
    public function getAllTeamInfo() {
        $teams = $this->getTeams();
        foreach($teams as $team) {
            $team_info[$team['entry_id']] = $this->getTeamInfo($team['entry_id']);
        }
        return $team_info;
    }

    /**
     * Get the team with the most trades
     * @return type
     */
    public function getTraders() {
        $json = $this->getAllTeamInfo();
        $trader = [];
        foreach($json as $team) {
            if(empty($trader) || $team['entry']['transactions_total'] > $trader['transactions_total']) {
                $trader = $team['entry'];
            }
        }
        return $trader;
    }

    /**
     * Helper function to return the team information that a certain player plays for
     * @return type
     */
    public function getPlayerTeam($player_info) {
        $teams = $this->getStaticTeams();
        foreach($teams as $key => $team) {
            if($player_info['team'] == $teams[$key]['id']) {
                $player_info['team'] = $teams[$key]['name'];
            }
        }
        return $player_info;
    }

    /**
     * Helper function to return all the results with additional infomation
     * around the team name and entry id
     * @return type
     */
    public function getResults($gameweek = null) {
        $matches = $this->getMatches();
        $teams = $this->getTeams();
        foreach($teams as $team) {
            foreach($matches as $key => $match) {
                if($match['finished'] == true) {
                    if($team['id'] == $match['league_entry_1']) {
                        $matches[$key]['league_entry_1_id'] = $team['entry_id'];
                        $matches[$key]['league_entry_1'] = $team['entry_name'];
                    }
                    if($team['id'] == $matches[$key]['league_entry_2']) {
                        $matches[$key]['league_entry_2_id'] = $team['entry_id'];
                        $matches[$key]['league_entry_2'] = $team['entry_name'];
                    }
                }
            }
        }
        if($gameweek != null) {
            $temp = [];
            foreach($matches as $key => $match) {
                if($match['event'] == $gameweek) {
                    $temp[] = $match;
                }
            }
            $matches = $temp;
        }
        return $matches;
    }

    /**
     * Do a comparison of matches to get the biggest winning margin
     * @return type
     */
    public function getBiggest() {
        // Find out what the biggest winning margin is
        $matches = $this->getResults();
        $diff = 0;
        $big_match = [];
        foreach($matches as $match) {
            if($diff < ($match['league_entry_1_points'] - $match['league_entry_2_points'])) { 
                $diff = $match['league_entry_1_points'] - $match['league_entry_2_points'];
                $big_match = $match;
                $big_match['diff'] = $diff;
            }
        }
        return $big_match;
    }

    /**
     * Do a comparison of matches to get the highest scoring match
     * @return type
     */
    public function getHighest() {
        // Find out what the biggest winning margin is
        $matches = $this->getResults();
        $diff = 0;
        $highest_match = [];
        foreach($matches as $match) {
            if($diff < ($match['league_entry_1_points'] + $match['league_entry_2_points'])) {
                $diff = $match['league_entry_1_points'] + $match['league_entry_2_points'];
                $highest_match = $match;
                $highest_match['diff'] = $diff;
            }
        }
        return $highest_match;
    }

    /**
     * Do a comparison of matches to get the lowest scoring match
     * @return type
     */
    public function getLowest() {
        // Find out what the biggest winning margin is
        $matches = $this->getResults();
        $diff = 1000;
        $lowest_match = [];
        foreach($matches as $match) {
            if($match['finished'] == true) {
                if($diff > ($match['league_entry_1_points'] + $match['league_entry_2_points'])) {
                    $diff = $match['league_entry_1_points'] + $match['league_entry_2_points'];
                    $lowest_match = $match;
                    $lowest_match['diff'] = $diff;
                }
            }
        }
        return $lowest_match;
    }

    /**
     * Helper function to get the information about the best player
     * based on a parameter available
     * @param type $parameter 
     * @return type
     */
    public function getPlayerBestInfo($parameter) {
        $players = $this->getDraft();
        $player_info = [];
        foreach($players as $key => $player) {
            if($players[$key]['minutes'] > $this->minutesPlayed) {
                if(empty($player_info) || $player_info[$parameter] < $players[$key][$parameter]) {
                    $player_info = $players[$key];
                }
            }
        }
        $player_info = $this->getPlayerTeam($player_info);
        return $player_info;
    }

    /**
     * Helper function to get the information about the worst player
     * based on a parameter available
     * @param type $parameter 
     * @return type
     */
    public function getPlayerWorstInfo($parameter) {
        $players = $this->getDraft();
        $player_info = [];
        foreach($players as $key => $player) {
            if($players[$key]['minutes'] > $this->minutesPlayed) {
                if(empty($player_info) || $player_info[$parameter] > $players[$key][$parameter]) {
                    $player_info = $players[$key];
                }
            }
        }
        $player_info = $this->getPlayerTeam($player_info);
        return $player_info;
    }

    public function getMVP() {
        return $this->getPlayerBestInfo('points_per_game');
    }

    public function getUndervalued() {
        return $this->getPlayerBestInfo('bps');
    }

    public function getWorst() {
        return $this->getPlayerWorstInfo('points_per_game');
    }

    /**
     * Get the current league standings
     * @return type
     */
    public function getStandings() {
        $json = $this->getApi();
        $standings = $json['standings'];
        $teams = $this->getTeams();

        foreach($teams as $team) {
            foreach($standings as $key => $standing) {
                if($standings[$key]['league_entry'] == $team['id']) {
                    $standings[$key]['league_entry'] = $team['entry_name'];
                }
            }
        }
        return $standings;
    }

    /**
     * Get the best player in a position by passing in the positionID
     * 1 = Keeper, 2 = Defender, 3 = Midfielder, 4 = Forward
     * @param type $positionID 
     * @return type
     */
    public function getBestPlayerPosition($positionID) {
        $players = $this->getDraft();
        $player_info = [];
        foreach($players as $key => $player) {
            if($players[$key]['element_type'] == $positionID) {
                if($players[$key]['minutes'] > $this->minutesPlayed) {
                    if(empty($player_info) || $player_info['total_points'] < $players[$key]['total_points']) {
                        $player_info = $players[$key];
                    }
                }
            }
        }
        $player_info = $this->getPlayerTeam($player_info);
        return $player_info;
    }

    public function getBestKeeper() {
        return $this->getBestPlayerPosition(1);
    }

    public function getBestDefender() {
        return $this->getBestPlayerPosition(2);
    }

    public function getBestMidfielder() {
        return $this->getBestPlayerPosition(3);
    }

    public function getBestForward() {
        return $this->getBestPlayerPosition(4);
    }

    /**
     * Get the all the matches of a certain team
     * @param type $teamID 
     * @return type
     */
    public function getTeamGames($teamID) {
        $matches = $this->getResults();
        $team_matches = [];
        $count = 0;
        foreach($matches as $match) {
            if($match['league_entry_1'] == $teamID || $match['league_entry_2'] == $teamID) {
                $team_matches[$count] = $match;
                $count++;
            }
        }
        return $team_matches;
    }

    /**
     * Return the comparison of two teams
     * @param type $team1 
     * @param type $team2 
     * @return type
     */
    public function getHeadToHead($team1) {
        $matches = $this->getResults();
        $teams = $this->getTeams();
        $own_team_score = [];
        $opponents_team_score = [];
        foreach($teams as $team) {
            if($team['entry_name'] != $team1) {
                $own_team_score[$team['entry_name']] = 0;
                $opponents_team_score[$team['entry_name']] = 0;
            }
        }
        foreach($matches as $match) {
            if($match['league_entry_1'] == $team1) {
                $own_team_score[$match['league_entry_2']] += $match['league_entry_1_points'];
                $opponents_team_score[$match['league_entry_2']] += $match['league_entry_2_points'];
            } else if($match['league_entry_2'] == $team1) {
                $own_team_score[$match['league_entry_1']] += $match['league_entry_2_points'];
                $opponents_team_score[$match['league_entry_1']] += $match['league_entry_1_points'];
            }
        }
        $scores = [];
        foreach($own_team_score as $own_key => $own_score) {
            foreach($opponents_team_score as $op_key => $opponents_score) {
                if($own_key == $op_key) {
                    $scores[$own_key]['for'] = $own_score;
                    $scores[$own_key]['against'] = $opponents_score;
                }
            }
        }
        return $scores;
    }

    public function addKeyToField(&$array, $field) {
        foreach($array as $key => &$value) {
            $value[$field] = ($key + 1);
        }
    }

    /**
     * Return the weekly positions of the teams in the league
     * @param type $week is the gameweek to run up to
     * @return an array
     */
    public function getPos($week = null) {
        $results = $this->getResults();
        $teams = $this->getTeams();
        $pos = [];
        foreach($teams as $key => $team) {
            $pos[$key]['entry_name'] = $team['entry_name'];
            $pos[$key]['score'] = 0;
            $pos[$key]['points'] = 0;
            $pos[$key]['position'] = 0;
        }
        foreach($results as $result) {
            if($result['event'] <= $week) {
                foreach($pos as &$team) {
                    if($team['entry_name'] == $result['league_entry_1']) {
                        if($result['league_entry_1_points'] > $result['league_entry_2_points']) {
                            $team['score'] += 3;                            
                        } else if ($result['league_entry_1_points'] == $result['league_entry_2_points']) {
                            $team['score'] += 1;
                        }
                        $team['points'] += $result['league_entry_1_points'];
                    } else if ($team['entry_name'] == $result['league_entry_2']) {
                        if($result['league_entry_1_points'] < $result['league_entry_2_points']) {
                            $team['score'] += 3;
                        } else if ($result['league_entry_1_points'] == $result['league_entry_2_points']) {
                            $team['score'] += 1;
                        }
                        $team['points'] += $result['league_entry_2_points'];
                    }
                }
            }
            array_multisort(array_column($pos, 'score'), SORT_DESC, array_column($pos, 'points'), SORT_DESC, $pos);
        }
        $this->addKeyToField($pos, 'position');
        return $pos;
    }

    public function getPositions($team) {
        $position = [];
        for($i = 1; $i <= 38; $i++) {
            $position[$i] = $this->getPos($i);
        }
        $league_history = [];
        foreach($position as $key => $pos) {
            foreach($pos as $p) {
                if($p['entry_name'] == $team) {
                    $league_history[] = $p['position'];
                }
            }
        }
        return $league_history;
    }

    
}