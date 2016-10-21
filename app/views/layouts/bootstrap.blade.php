<!DOCTYPE html>
<html lang="en">
  <head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}bootstrap/css/bootstrap.theme.min.css">
    <script type="text/javascript" src="{{URL::to('/')}}bootstrap/js/jquery.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/tabla/js/jquery.js"></script>
    <script src="bootstrap/tabla/js/bootstrap.min.js"></script>
    @yield('head')
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{URL::route('index')}}">Laravel App</a>
        </div>

        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
              <?php
              $vista = Route::currentRouteName();
              $current = array
                  (
                   'index' => '',
                   'contacto' => '',
                   'login' => '',
                   'register' => '',
                  );
              if ($vista == '' || $vista == 'index')
              {
                  $current['index'] = 'active';
              }
              else if ($vista == 'contacto')
              {
                  $current['contacto'] = 'active';
              }
              else if ($vista == 'login')
              {
                  $current['login'] = 'active';
              }
              else if ($vista == 'register')
              {
                  $current['register'] = 'active';
              }
              ?>
            <li class="{{$current['index']}}"><a href="{{URL::route('index')}}">Inicio</a></li>
            <li class="{{$current['contacto']}}"><a href="{{URL::route('contacto')}}">Contacto</a></li>
            <?php if (Auth::user()->guest()) {?>
            <li class="{{$current['login']}}"><a href="{{URL::route('login')}}">Iniciar sesión</a></li>
            <li class="{{$current['register']}}"><a href="{{URL::route('register')}}">Registrarme</a></li>
            <?php } else { ?>
            <li>
               <div class="navbar-collapse collapse">
               {{Form::open(array(
                  "method" => "POST",
                  "action" => "HomeController@salir",
                  "role" => "form",
                  "class" => "navbar-form",
                ))}}
        <a href="{{URL::route('privado')}}">
                {{Form::label(Auth::user()->get()->user, null, array('class' => 'label label-info'))}}
        </a>
                {{Form::input("submit", "", "Salir", array("class" => "btn btn-success"))}}
                {{Form::close()}}
             </div>
            </li>
            <?php } ?>
          </ul>

        </div><!--/.nav-collapse -->
      </div>
    </div>
    <div class="container" style='margin-top: 80px'>
     @yield('content')
    </div><!-- /.container -->
  </body>
</html>


