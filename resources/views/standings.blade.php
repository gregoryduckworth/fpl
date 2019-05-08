@extends('layout')

@section('title', 'Standings')

@section('content')
    <table id='standings'>
        <thead>
            <th>Name</th>
            <th>Won</th>
            <th>Drawn</th>
            <th>Lost</th>
            <th>PF</th>
            <th>PA</th>
            <th>PD +/-</th>
            <th>Points</th>
        </thead>
        <tbody>
        @foreach($standings as $standing)
            <tr>
                <td><a href="/info/{{ $standing['league_entry'] }}">{{ $standing['league_entry'] }}</a></td>
                <td>{{ $standing['matches_won'] }}</td>
                <td>{{ $standing['matches_drawn'] }}</td>
                <td>{{ $standing['matches_lost'] }}</td>
                <td>{{ number_format($standing['points_for'],0) }}</td>
                <td>{{ number_format($standing['points_against'],0) }}</td>
                <td>{{ number_format($standing['points_for'] - $standing['points_against'],0) }}</td>
                <td>{{ $standing['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('javascript')
<script>
    $(document).ready( function () {
        $('#standings').DataTable({
            order: [],
            bInfo: false,
            bPaginate: false,
            searching: false,
        });
    } );
</script>
@endsection
