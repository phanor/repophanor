@extends('layouts.bootstrap')

@section('head')
<title>{{Auth::user()->get()->user}}</title>
<meta name='description' content='{{Auth::user()->get()->user}}'>
<meta name='keywords' content='palabras, clave'>
<meta name='robots' content='noindex,nofollow'>
@stop

@section('content')

<h1>Bienvenido {{Auth::user()->get()->user}}</h1>

<a href="{{URL::route('creararticulo')}}" class="btn btn-primary">CREAR ARTÍCULO</a>
<a href="{{URL::route('verarticulos')}}" class="btn btn-primary">VER MIS ARTÍCULOS</a>

@stop

