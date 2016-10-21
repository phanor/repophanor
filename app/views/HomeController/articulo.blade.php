@extends('layouts.bootstrap')

@section('head')
<title>{{$fila[0]->titulo}}</title>
<meta name='description' content='{{$fila[0]->descripcion}}'>
<meta name='keywords' content='palabras, clave'>
<meta name='robots' content='All'>
@stop

@section('content')

<h1>{{$fila[0]->titulo}}</h1>

<hr>
{{ Form::open(array
            (
            'action' => 'HomeController@index', 
            'method' => 'GET',
            'role' => 'form',
            'class' => 'form-inline'
            )) 
}}
{{ Form::input('text', 'buscar', Input::get('buscar'), array('class' => 'form-control') )}}
{{ Form::input('submit', null, 'Buscar', array('class' => 'btn btn-primary'))}}
{{Form::close()}}
<hr>
<div class="row">
        <div class='col-md-3'>
            <img src="{{URL::to('/').'/'.$fila[0]->src}}" title="{{$fila[0]->titulo}}" width="150" height="150">
        </div>
        <div class='col-md-9'>
            <h3><a href="{{URL::route('articulo', array('id'=>$fila[0]->id))}}">{{$fila[0]->titulo}}</a></h3>
            URL:<a href="{{$fila[0]->href}}" target="_blank">{{$fila[0]->href}}</a>
            <hr>
            {{$fila[0]->descripcion}}
        </div>
</div>
<hr>

@stop

