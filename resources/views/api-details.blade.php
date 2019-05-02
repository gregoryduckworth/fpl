@extends('layout')

@section('title', 'API')

@section('content')
    @foreach(['matches', 'teams', 'draft', 'results', 'biggest', 'highest', 'lowest', 'trader', 'all-teams', 'mvp', 'undervalued', 'standings', 'worst', 'static-teams', 'keeper', 'defender', 'midfielder', 'forward'] as $link)
        <li><a href='/api/{{ $link }}'>{{ ucfirst($link) }}</a></li>
    @endforeach
@endsection
