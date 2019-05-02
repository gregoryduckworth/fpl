@extends('layout')

@section('title', 'Awards')

@section('content')
    <div>
        <h2>League Stats</h2>
        <table>
            @foreach(['trader' => 'Most Prolific Trader'] as $key => $value)
                <tr>
                    <td><strong>{{ $value }}</strong></td>
                    <td><strong>{{ $$key['name'] }}</strong> ({{ $$key['transactions_total'] }})</td>
                </tr>
            @endforeach
        </table>
        <h2>Match Stats</h2>
        <table>
            @foreach(['biggest' => 'Biggest Winning Margin', 'highest' => 'Highest Scoring Match', 'lowest' => 'Lowest Scoring Match'] as $key => $value)
                <tr>
                    <td><strong>{{ $value}} - {{ $$key['diff'] }} Points</strong></td>
                    <td><strong><a href="https://draft.premierleague.com/entry/{{ $$key['league_entry_1_id'] }}/event/{{ $$key['event'] }}">{{ $$key['league_entry_1'] }}</a> {{ $$key['league_entry_1_points'] }}</strong> v <strong>{{ $$key['league_entry_2_points'] }} <a href="https://draft.premierleague.com/entry/{{ $$key['league_entry_2_id'] }}/event/{{ $$key['event'] }}">{{ $$key['league_entry_2'] }}</a></strong> (Week: {{ $$key['event'] }})</td>
                </tr>
            @endforeach
        </table>
        <h2>Player Stats</h2>
        <table>
        @foreach(['mvp' => 'Most Valuable Player', 'undervalued' => 'Most Undervalued Player', 'worst' => 'Worst Player', 'keeper' => 'Best Keeper', 'defender' => 'Best Defender', 'midfielder' => 'Best Midfielder', 'forward' => 'Best Forward'] as $key => $value)
            <tr>
                <td><strong>{{ $value }}</strong></td>
                <td>{{ $$key['first_name'] }} {{ $$key['second_name'] }} ({{ $$key['team'] }})</td>
                <td>Total Points: {{ $$key['total_points'] }} Points per game: {{ $$key['points_per_game'] }} (Draft rank: {{ $$key['draft_rank'] }})</td>
            </tr>
        @endforeach
        </table>
        <strong>All players have played more than 1800 minutes (20 games)</strong>
    </div>
@endsection
