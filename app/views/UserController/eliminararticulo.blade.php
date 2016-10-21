@extends('layouts.bootstrap')

@section('head')
<title>Eliminar artículo {{$fila[0]->titulo}}</title>
<meta name='description' content='Eliminar artículo'>
<meta name='keywords' content='palabras, clave'>
<meta name='robots' content='noindex,nofollow'>
@stop

@section('content')

<h1>Bienvenido {{Auth::user()->get()->user}}</h1>



<h3>Eliminar artículo: {{$fila[0]->titulo}}</h3>

{{Form::open(array(
            "method" => "POST",
            "action" => "UserController@eliminararticulo",
            "role" => "form",
            ))}}
            
            <div class="form-group">
                {{Form::label("¿Estás seguro de que quieres eliminar el artículo?")}}
                {{Form::input("hidden", "_token", csrf_token())}}
                {{Form::input("hidden", "id", $fila[0]->id)}}
                {{Form::input("submit", null, "Eliminar artículo", array("class" => "btn btn-danger"))}}
            </div>
            
{{Form::close()}}

@stop

