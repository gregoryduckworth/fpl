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
    });

    var seriesOptions = [],
    seriesCounter = 0;

    /**
     * Create the chart when all data is loaded
     * @returns {undefined}
     */
    function createChart() {

        Highcharts.stockChart('container', {

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
                    showInNavigator: true
                }
            },

            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
                valueDecimals: 0,
                split: true
            },

            series: seriesOptions
        });
    }

    $.getJSON('{{route('positions')}}/38',    function (data) {
        Object.keys(data).forEach(function(key) {

            seriesOptions[key] = {
                name: key,
                data: data[key].data,
            };
        });
        console.log(seriesOptions);
        createChart();
    });
</script>
@endsection
