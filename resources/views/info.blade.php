@extends('layout')

@section('title', 'Info')

@section('content')
    <h2>Head to Head</h2>
    <table id='head-to-head' class='stripe display compact'>
        <thead>
            <th>Name</th>
            <th>For</th>
            <th>Against</th>
            <th>+/-</th>
        </thead>
        <tbody>
        @foreach($scores as $key => $score)
            <tr> 
                <td>{{ $key }}</td><td>{{ $score['for'] }}</td><td>{{ $score['against'] }}</td><td>{{ $score['for'] - $score['against'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <h2>League Results</h2>
    <table>
        @foreach($versus as $v) 
            <tr>
                <td>{{ $v['league_entry_1'] }}</td>
                <td>{{ $v['league_entry_1_points'] }}</td>
                <td>v</td>
                <td>{{ $v['league_entry_2_points'] }}</td>
                <td>{{ $v['league_entry_2'] }}</td>
            </tr>
        @endforeach
    </table>


@endsection


@section('javascript')
<script>
    $(document).ready( function () {
        $('#head-to-head').DataTable({
            order: [],
            bInfo: false,
            bPaginate: false,
            searching: false,
        });
    });
</script>
@endsection