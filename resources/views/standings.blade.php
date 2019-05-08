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

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>

<div id="container"></div>
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

    Highcharts.stockChart('container', {

        rangeSelector: {
            selected: 4
        },

        yAxis: {
            labels: {
                formatter: function () {
                    return (this.value > 0 ? ' + ' : '') + this.value + '%';
                }
            },
            plotLines: [{
                value: 0,
                width: 2,
                color: 'silver'
            }]
        },

        plotOptions: {
            series: {
                compare: 'percent',
                showInNavigator: true
            }
        },

        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
            valueDecimals: 2,
            split: true
        },

        //series: seriesOptions
    });


    $.getJSON('/api/positions/38', function (data) {

        console.log(data);
        seriesOptions[i] = {
            name: name,
            data: data
        };

        // As we're loading the data asynchronously, we don't know what order it will arrive. So
        // we keep a counter and create the chart when all the data is loaded.
        seriesCounter += 1;

        if (seriesCounter === names.length) {
            createChart();
        }
    });
</script>
@endsection
