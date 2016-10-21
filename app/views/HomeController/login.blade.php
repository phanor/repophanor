@extends('layouts.bootstrap')

@section('head')
<title>Contacto</title>
<meta name='title' content='Login'>
<meta name='description' content='Login'>
<meta name='keywords' content='palabras, clave'>
<meta name='robots' content='noindex,nofollow'>
@stop

@section('content')
{{Session::get("message")}}
<h1>Login</h1>

{{Form::open(array(
            "method" => "POST",
            "action" => "HomeController@login",
            "role" => "form",
            ))}}
 
            <div class="form-group">
                {{Form::label("Email:")}}
                {{Form::input("text", "email", null, array("class" => "form-control"))}}
            </div> 
            
            <div class="form-group">
                {{Form::label("Password:")}}
                {{Form::input("password", "password", null, array("class" => "form-control"))}}
            </div>
            
            <div class="form-group">
                {{Form::label("Recordar sesión:")}}
                {{Form::input("checkbox", "remember", "On")}}
            </div>
            
            <div class="form-group">
                {{Form::input("hidden", "_token", csrf_token())}}
                {{Form::input("submit", null, "Iniciar sesión", array("class" => "btn btn-primary"))}}
            </div>
            
{{Form::close()}}

<a href="{{URL::route('recoverpassword')}}">Recuperar password</a>

@stop




