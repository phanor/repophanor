@extends('layouts.bootstrap')

@section('head')

<title>Contacto</title>
<meta name="title" content="pánina de contacto" />
<meta name="description" content="página de contacto" />
<meta name="keywords" content="palabras, clave" />
<meta name="robots" content="All" />
@stop

@section('content')

<h1>CONTACTO</h1>
{{$mensaje}}
{{Form::open(array
(
'action'=>'HomeController@contacto',
'method'=>'POST',
'role' => 'form'
/*'files'=>true*/
))
}}

<div class="form-group">
	{{ Form::label('nombre:')}}
	{{ form::Input('text','name', Input::old('name'),array('class'=>'form-control'))}}
	<div class="bg-danger">{{$errors ->first('name')}}</div>
</div>

<div class="form-group">
	{{Form::label('Email:')}}
	{{form::Input('email','email',null,array('class'=>'form-control'))}}
	<div class="bg-danger">{{$errors ->first('email')}}</div>
</div>

<div class="form-group">
	{{Form::label('Asunto:')}}
	{{form::Input('text','subject',Input::old('subject'),array('class'=>'form-control'))}}
	<div class="bg-danger">{{$errors ->first('subject')}}</div>
</div>

<div class="form-group">
	{{Form::label('Mensaje:')}}
	{{form::textarea('msg', Input::old('msg'),array('class'=>'form-control'))}}
	<div class="bg-danger">{{$errors ->first('msg')}}</div>
</div>

{{Form::captcha()}}
<div class="bg-danger">{{$errors ->first('g-recaptcha-response')}}</div>

<br>
{{ Form::input('hidden','contacto') }}
{{ Form::input('submit', null, 'Enviar', array('class' => 'btn btn-primary'))}}


{{Form::close()}}
@stop