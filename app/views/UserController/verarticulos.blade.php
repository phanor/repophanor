@extends('layouts.bootstrap')

@section('head')
<title>Ver mis artículos</title>
<meta name='description' content='Ver mis artículos'>
<meta name='keywords' content='palabras, clave'>
<meta name='robots' content='All'>
@stop

@section('content')

<h1>Bienvenido {{Auth::user()->get()->user}}</h1>

{{Session::get("message")}}

<h3>Ver mis artículos</h3>

<hr>
{{ Form::open(array
            (
            'action' => 'UserController@verarticulos', 
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
            <a href="{{URL::route('editararticulo', array('id' => $fila->id))}}" class="btn btn-primary">Editar</a>
            <a href="{{URL::route('eliminararticulo', array('id' => $fila->id))}}" class="btn btn-primary">Eliminar</a>
            <h3><a href="{{URL::route('articulo', array('id'=>$fila->id))}}">{{$fila->titulo}}</a></h3>
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