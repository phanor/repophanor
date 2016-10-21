@extends('layouts.bootstrap')

@section('head')
<title>Página de inicio</title>
<meta name='description' content='Página de inicio'>
<meta name='keywords' content='palabras, clave'>
<meta name='robots' content='All'>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">

@stop

@section('content')

<h1>Página de inicio</h1>

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
<label class="label label-info">Página {{$paginacion->getCurrentPage()}}. {{$paginacion->getTotal()}} resultados</label>
<hr>
<?php foreach($paginacion as $fila): ?>
<hr>
<div class="row">
        <div class='col-md-3'>
            <img src="{{URL::to('/').'/'.$fila->src}}" title="{{$fila->titulo}}" width="150" height="150">
        </div>
        <div class='col-md-9'>
            <h3><a href="{{URL::route('articulo', array('id' => $fila->id))}}">{{$fila->titulo}}</a></h3>
            URL:<a href="{{$fila->href}}" target="_blank">{{$fila->href}}</a>
            <hr>
            {{$fila->descripcion}}
        </div>
</div>
<hr>
<?php endforeach; ?>

<div class="container">
    {{$paginacion->appends(array("buscar" => Input::get("buscar")))->links()}}
</div>

@stop