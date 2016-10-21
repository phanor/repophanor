<?php


Route::get('/', 'HomeController@index', function(){});

Route::get('/contacto', 'HomeController@contacto', function(){});

Route::get('/login', 'HomeController@login', function(){});

Route::get('/privado', 'HomeController@privado', function(){});

Route::get('/salir', 'HomeController@salir', function(){});

Route::get('/register', 'HomeController@register', function(){});

Route::get('/confirmregister/{email}/{codigo_confirmacion}', function($email, $codigo_confirmacion){

 $Usuarios =user::where('email',$email)->get();
        // $Estado="Activo";   

 foreach ($Usuarios as $value) {

    $confirmation_code=$value->codigo_confirmacion;

}   
if($confirmation_code==$codigo_confirmacion){

   $confirmation_code = str_random(30);    

   $conn = DB::connection("mysql");
   $sql = "UPDATE users SET active=1, codigo_confirmacion='$confirmation_code' WHERE email=?";
   $conn->update($sql, array($email));

   $message = "<hr><label class='label label-success'><strong> <font size =4', face='Lucida Sans'>Error!!!...Enhorabuena tu registro se ha llevado a cabo con éxito.</font></strong></label><hr>";

   return Redirect::route("login")->with("message", $message);
}  else  {

 $message = "<hr><label class='label label-danger'><strong> <font size =4', face='Lucida Sans'>Error!!!...La cuenta ya habia sido verificada.</font></strong></label><hr>";

 return Redirect::route("login")->with("message", $message);
}



    //--------------------------------------------------------

    // if (urldecode($email) == Cookie::get("email") && urldecode($key) == Cookie::get("key"))
    // {
    //     $conn = DB::connection("mysql");
    //     $sql = "UPDATE users SET active=1 WHERE email=?";
    //     $conn->update($sql, array($email));
    //     $message = "<hr><label class='label label-success'>Enhorabuena tu registro se ha llevado a cabo con éxito.</label><hr>";
    //     return Redirect::route("login")->with("message", $message);
    // }
    // else
    // {
    //     return Redirect::route("register");
    // }
});

Route::get('/recoverpassword', 'HomeController@recoverpassword', function(){});

Route::get('/resetpassword/{type}/{token}', 'HomeController@resetpassword', function(){});

Route::get('/updatepassword', 'HomeController@updatepassword', function(){});

Route::get('/articulo/{id}', 'HomeController@articulo', function(){});

Route::any('/', array('as' => 'index', 'uses' => 'HomeController@index'));
Route::any('/contacto', array('as' => 'contacto', 'uses' => 'HomeController@contacto'));
Route::any('/login', array('as' => 'login', 'uses' => 'HomeController@login'))->before("guest_user");
Route::any('/privado', array('as' => 'privado', 'uses' => 'HomeController@privado'))->before("auth_user");
Route::any('/salir', array('as' => 'salir', 'uses' => 'HomeController@salir'))->before("auth_user");
Route::any('/register', array('as' => 'register', 'uses' => 'HomeController@register'))->before("guest_user");
Route::any('/confirmregister', array('as' => 'confirmregister', 'uses' => 'HomeController@confirmregister'))->before("guest_user");
Route::any('/recoverpassword', array('as' => 'recoverpassword', 'uses' => 'HomeController@recoverpassword'))->before("guest_user");
Route::any('/resetpassword/{type}/{token}', array('as' => 'resetpassword', 'uses' => 'HomeController@resetpassword'))->before("guest_user");
Route::any('/updatepassword', array('as' => 'updatepassword', 'uses' => 'HomeController@updatepassword'))->before("guest_user");
Route::any('/articulo/{id}', array('as' => 'articulo', 'uses' => 'HomeController@articulo'));


Route::post('/login', array('before' => 'csrf', function(){
    
   $user = array(
    'email' => Input::get('email'),
    'password' => Input::get('password'),
    'active' => 1,
    );
   
   $remember = Input::get("remember");
   $remember == 'On' ? $remember = true : $remember = false;
   
   if (Auth::user()->attempt($user, $remember))
   {
    return Redirect::route("privado");
}
else
{
    return Redirect::route("login");
}
}));

Route::post('/register', array('before' => 'csrf', function(){

    $rules = array
    (
        'user' => 'required|regex:/^[a-záéóóúàèìòùäëïöüñ\s]+$/i|min:3|max:50',
        'email' => 'required|email|unique:users|between:3,80',
        'password' => 'required|regex:/^[a-z0-9]+$/i|min:8|max:16',
        'repetir_password' => 'required|same:password',
        'terminos' => 'required',
        );
    
    $messages = array
    (
        'user.required' => 'El campo nombre es requerido',
        'user.regex' => 'Sólo se aceptan letras',
        'user.min' => 'El mínimo permitido son 3 caracteres',
        'user.max' => 'El máximo permitido son 50 caracteres',
        'email.required' => 'El campo email es requerido',
        'email.email' => 'El formato de email es incorrecto',
        'email.unique' => 'El email ya se encuentra registrado',
        'email.between' => 'El email debe contener entre 3 y 80 caracteres',
        'password.required' => 'El campo password es requerido',
        'password.regex' => 'El campo password sólo acepta letras y números',
        'password.min' => 'El mínimo permitido son 8 caracteres',
        'password.max' => 'El máximo permitido son 16 caracteres',
        'repetir_password.required' => 'El campo repetir password es requerido',
        'repetir_password.same' => 'Los passwords no coinciden',
        'terminos.required' => 'Tienes que aceptar los términos',
        );
    
    $validator = Validator::make(Input::All(), $rules, $messages);
    
    if ($validator->passes())
    {

        //Guardar los datos en la tabla users 
        $user = input::get('user');
        $email = input::get('email');
        $password = Hash::make(input::get('password'));
        $codigo_confirmacion = Str::random(30);


        
        $conn = DB::connection('mysql');
        $sql = "INSERT INTO users(user, email, password,codigo_confirmacion) VALUES (?, ?, ?,?)";
        $conn->insert($sql, array($user, $email, $password,$codigo_confirmacion));
        
        // Crear cookies para luego verificar el link de registro
        // String alfanumérico de 32 caracteres de longitud


        
        
        // Crear la url de confirmación para el mensaje del email
        $msg = "<a href='".URL::to("/confirmregister/$email/$codigo_confirmacion")."'>Confirmar cuenta</a>";
        
        
        //Enviar email para confirmar el registro
        $data = array(
            'user' => $user,
            'msg' => $msg,
            );
        
        $fromEmail = 'mdgproduccionesweb@gmail.com';
        $fromName = 'Administrador';

        Mail::send('emails.register', $data, function($message) use ($fromName, $fromEmail, $user, $email)
        {
           $message->to($email, $user);
           $message->from($fromEmail, $fromName);
           $message->subject('Confirmar registro en Laravel');
       });
        
        $message = '<hr><label class="label label-info">'.$user.' le hemos enviado un email a su cuenta de correo electrónico para que confirme su registro</label><hr>';
        
        return Redirect::route('register')->with("message", $message);
    }
    else
    {
     return Redirect::back()->withInput()->withErrors($validator);  
 }

}));

Route::post('/recoverpassword', array('before' => 'csrf', function(){
    
    $rules = array(
        "email" => "required|email|exists:users",
        );
    
    $messages = array(
        "email.required" => "El campo email es requerido",
        "email.email" => "El formato de email es incorrecto",
        "email.exists" => "El email seleccionado no se encuentra registrado",
        );
    
    $validator = Validator::make(Input::All(), $rules, $messages);
    
    if ($validator->passes())
    {
        Password::user()->remind(Input::only("email"), function($message) {
            $message->subject('Recuperar password en Laravel');
        });
        
        $message = '<hr><label class="label label-info">Le hemos enviado un email a su cuenta de correo electrónico para que pueda recuperar su password</label><hr>';
        return Redirect::route('recoverpassword')->with("message", $message);
    }
    else
    {
        return Redirect::back()->withInput()->withErrors($validator); 
    }
    
}));

Route::post('/updatepassword', array('before' => 'csrf', function(){
    
    $credentials = array(
        'email' => Input::get('email'),
        'password' => Input::get('password'),
        'password_confirmation' => Input::get('repetir_password'),
        'token' => Input::get('token'),
        );

    Password::user()->reset($credentials, function($user, $password) {
        $user->password = Hash::make($password);
        $user->save();
    });
    
    $message = '<hr><label class="label label-info">Password cambiado con éxito, ya puedes iniciar sesión</label><hr>';
    return Redirect::to('login')->with('message', $message);
    
}));

/* UserController */

Route::get('/creararticulo', 'UserController@creararticulo', function(){});

Route::any('/creararticulo', array('as' => 'creararticulo', 'uses' => 'UserController@creararticulo'))->before("auth_user");

Route::post('/creararticulo', array('before' => 'csrf', function(){
    
    $rules = array(
        "titulo" => "required|regex:/^[a-z0-9áéóóúàèìòùäëïöüñ\s]+$/i|min:3|max:100",
        "descripcion" => "required|min:10|max:1000",
        "src" => "required|max:10000|mimes:jpg,jpeg,png,gif", //10000 kb
        "href" => "required|min:5|max:250|url",
        );
    
    $messages = array(
        "titulo.required" => "El campo Título es requerido",
        "titulo.regex" => "Tan sólo se aceptan letras y números",
        "titulo.min" => "El mínimo permitido son 3 caracteres",
        "titulo.max" => "El máximo permitido son 100 caracteres",
        "descripcion.required" => "El campo Descripción es requerido",
        "descripcion.min" => "El mínimo permitido son 10 caracteres",
        "descripcion.max" => "El máximo permitido son 1000 caracteres",
        "src.required" => "Es requerido subir una imagen",
        "src.max" => "El tamaño máximo de la imagen son 10000kb",
        "src.mimes" => "El archivo que pretendes subir no es una imagen",
        "href.required" => "Tienes que incluir el sitio web",
        "href.min" => "El mínimo permitido son 5 caracteres",
        "href.max" => "El máximo permitido son 250 caracteres",
        "href.url" => "Introduce una url correcta",
        );
    
    $validator = Validator::make(Input::All(), $rules, $messages);

    if ($validator->passes())
    {
        $id_user = Auth::user()->get()->id;
        
        if(!empty($id_user))
        {
            $titulo = Input::get('titulo');
            $descripcion = htmlspecialchars(Input::get('descripcion'));
            $src = $_FILES['src'];
            $href = Input::get('href');
            
            $ruta_imagen = "directorio/images/";
            $imagen = rand(1000, 9999)."-".$src["name"];
            
            move_uploaded_file($src["tmp_name"], $ruta_imagen.$imagen);
            
            $conn = DB::connection("mysql");
            $sql = "INSERT INTO directorio (id_user, titulo, descripcion, src, href) VALUES (?, ?, ?, ?, ?)";
            $conn->insert($sql, array($id_user, $titulo, $descripcion, $ruta_imagen.$imagen, $href));
            
            $message = "<hr><label class='label label-info'>Enhorabuena artículo creado con éxito</label><hr>";
            return Redirect::back()->with("message", $message);
        }
    }
    else
    {
        return Redirect::back()->withInput()->withErrors($validator);    
    }
    
}));

Route::get('/verarticulos', 'UserController@verarticulos', function(){});

Route::any('/verarticulos', array('as' => 'verarticulos', 'uses' => 'UserController@verarticulos'))->before("auth_user");

Route::get('/editararticulo/{id}', 'UserController@editararticulo', function(){});

Route::any('/editararticulo/{id}', array('as' => 'editararticulo', 'uses' => 'UserController@editararticulo'))->before("auth_user");

Route::post('/editararticulo/{id}', array('before' => 'csrf', function(){
    
    $rules = array(
        "titulo" => "required|regex:/^[a-z0-9áéóóúàèìòùäëïöüñ\s]+$/i|min:3|max:100",
        "descripcion" => "required|min:10|max:1000",
        "src" => "max:10000|mimes:jpg,jpeg,png,gif", //10000 kb
        "href" => "required|min:5|max:250|url",
        );
    
    $messages = array(
        "titulo.required" => "El campo Título es requerido",
        "titulo.regex" => "Tan sólo se aceptan letras y números",
        "titulo.min" => "El mínimo permitido son 3 caracteres",
        "titulo.max" => "El máximo permitido son 100 caracteres",
        "descripcion.required" => "El campo Descripción es requerido",
        "descripcion.min" => "El mínimo permitido son 10 caracteres",
        "descripcion.max" => "El máximo permitido son 1000 caracteres",
        "src.max" => "El tamaño máximo de la imagen son 10000kb",
        "src.mimes" => "El archivo que pretendes subir no es una imagen",
        "href.required" => "Tienes que incluir el sitio web",
        "href.min" => "El mínimo permitido son 5 caracteres",
        "href.max" => "El máximo permitido son 250 caracteres",
        "href.url" => "Introduce una url correcta",
        );
    
    $validator = Validator::make(Input::All(), $rules, $messages);

    if ($validator->passes())
    {
        /* Obtenemos el id del usuario autenticado */
        $id_user = Auth::user()->get()->id;
        
        /* Si el usuario está autenticado */
        if(!empty($id_user))
        {
            /* Obtenemos los valores de los campos del formulario */
            $id = Input::get('id');
            $titulo = Input::get('titulo');
            $descripcion = htmlspecialchars(Input::get('descripcion'));
            $src = $_FILES['src'];
            $href = Input::get('href');
            
            /* Establecemos la conexion a la DB */
            $conn = DB::connection("mysql");
            
            /** Si se ha seleccionado una nueva imagen guardamos la nueva imagen 
            y eliminamos la anterior **/
            if ($src["size"] > 0)
            {
                $ruta_imagen = "directorio/images/";
                $imagen = rand(1000, 9999)."-".$src["name"];
                move_uploaded_file($src["tmp_name"], $ruta_imagen.$imagen);
                
                /* Hacemos la consulta para obtener el src de la imagen anterior */
                $sql = "SELECT src FROM directorio WHERE id=? AND id_user=?";
                $anterior_imagen = $conn->select($sql, array($id, $id_user));
                $anterior_imagen = $anterior_imagen[0]->src;
                /* Eliminamos la imagen si existe */
                if (is_file($anterior_imagen))
                {
                    unlink($anterior_imagen);
                }
            }
            
            /* Si se ha seleccionado una nueva imagen, amoldamos la consulta para 
             * guardar también el src de la nueva imagen
             */
            if ($src["size"] > 0)
            {
                $sql = "UPDATE directorio SET titulo='$titulo', descripcion='$descripcion', src='".$ruta_imagen.$imagen."', href='$href' WHERE id=? AND id_user=?";
                $conn->update($sql, array($id, $id_user));
            }
            else /* De lo contrario sólo actualizamos titulo, descripcion y href */
            {
                $sql = "UPDATE directorio SET titulo='$titulo', descripcion='$descripcion', href='$href' WHERE id=? AND id_user=?";
                $conn->update($sql, array($id, $id_user));   
            }
            
            $message = "<hr><label class='label label-info'>Enhorabuena artículo editado con éxito</label><hr>";
            return Redirect::back()->with("message", $message);
            
        }
    }
    else
    {
        return Redirect::back()->withInput()->withErrors($validator);    
    }
    
}));

Route::get('/eliminararticulo/{id}', 'UserController@eliminararticulo', function(){});

Route::any('/eliminararticulo/{id}', array('as' => 'eliminararticulo', 'uses' => 'UserController@eliminararticulo'))->before("auth_user");

Route::post('/eliminararticulo/{id}', array('before' => 'csrf', function(){
    
    $id_user = Auth::user()->get()->id;
    
    if (!empty($id_user))
    {
        $id = Input::get('id');
        
        $conn = DB::connection('mysql');
        $sql = "DELETE FROM directorio WHERE id=? AND id_user=?";
        $conn->delete($sql, array($id, $id_user));
        
        $message = "<hr><label class='label label-info'>Enhorabuena artículo con id $id eliminado con éxito</label><hr>";
        return Redirect::route("verarticulos")->with('message', $message);
    }
    else
    {
        $message = "<hr><label class='label label-danger'>Ha ocurrido un error al intentar eliminar el artículo con id $id</label><hr>";
        return Redirect::route("verarticulos")->with('message', $message);
    }
}));

/* Redireccion a página de error 404 */
App::missing(function($exception)
{
    return Response::view('error.error404', array(), 404);
});