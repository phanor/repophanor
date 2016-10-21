<?php

class HomeController extends BaseController {

	public function index() 
	{	
		$conn = DB::connection("mysql");

		if(isset($_GET["buscar"]))
		{
			$buscar = htmlspecialchars(Input::get("buscar"));
			$paginacion = $conn
                     ->table("directorio")
                     ->where("titulo", "LIKE", '%'.$buscar.'%')
                     ->orwhere("descripcion", "LIKE", '%'.$buscar.'%')
                     ->orderby("id", "desc")
                     ->paginate(5);
		}
		else
		{
		$paginacion = $conn->table("directorio")->orderby("id", "desc")->paginate(5);
		}
		return View::make('HomeController.index', array("paginacion"=>$paginacion));
	}

	public function contacto() {
		$mensaje = null;
		if(isset($_POST['contacto']))
		{
			$rules = array
			(
				'name' => 'required|regex:/^[a-záéíóúñ\s]+$/i |min:3 |max:80',
				'email'=> 'required | email |between:3,80',
				'subject'=>'required |regex:/^[a-z0-9záéíóúñ\s]+$/i |min:3 |max:80',
				'msg'=> 'required |between:3,500',
				'g-recaptcha-response' => 'required|recaptcha',
			);

			$messages = array
                    (
                    'name.required' => 'El campo es requerido',
                    'name.regex' => 'Sólo se aceptan letras',
                    'name.min' => 'Mínimo 3 caracteres',
                    'name.max' => 'Máximo 80 caracteres',
                    'email.required' => 'El campo es requerido',
                    'email.email' => 'El formato de email es incorrecto',
                    'email.between' => 'Entre 3 y 80 caracteres',
                    'subject.required' => 'El campo es requerido',
                    'subject.regex' => 'Sólo se aceptan letras y números',
                    'subject.min' => 'Mínimo 3 caracteres',
                    'subject.max' => 'Máximo 80 caracteres',
                    'msg.required' => 'El campo es requerido',
                    'msg.between' => 'Entre 3 y 500 caracteres',
                    'g-recaptcha-response.required'=>'El campo captcha es requerido',
                    'g-recaptcha-response.recaptcha'=>'Captcha incorrecto',
                   
                    );

			$validator = Validator::make(Input::All(),$rules, $messages);


			if($validator->passes())
			{
			$data = array(
				'name'=>Input::get('name'),
				'email' =>Input::get('email'),
				'subject' =>Input::get('subject'),
				'msg' =>Input::get('msg')
				);

			$fromEmail = 'phanorcito@gmail.com';
			$fromName = 'Administrador';
			Mail::send('emails.contacto',$data,function($message)use($fromName,$fromEmail)
			{
				$message->to($fromEmail, $fromName);
				$message->from($fromEmail, $fromName);
				$message->subject('nuevo email de contacto');
			});
			$mensaje='<div class="text-info">Mensaje enviado con éxito</div>';
		}
		else
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
	}
		return View::make('HomeController.contacto',array('mensaje'=>$mensaje));
	}

	public function login()
	{
		return View::make('HomeController.login');
	}

	public function privado()
	{
		return View::make('HomeController.privado');
	}

	public function salir()
	{
		Auth::user()->logout();
		return Redirect::to('login');
	}

	 public function register()
        {
            return View::make('HomeController.register');
        }
        
        public function confirmregister()
        {
            
        }

          public function recoverpassword()
        {
            return View::make('HomeController.recoverpassword');
        }

        public function resetpassword($type, $token)
        {
            return View::make('HomeController.resetpassword')->with('token', $token);
        }
        
        public function updatepassword()
        {
            
        }

        public function articulo($id)
        {
        	$conn = DB::connection("mysql");
        	$sql = "SELECT * FROM directorio WHERE id=?";
        	$fila = $conn->select($sql, array($id));
        	return View::make('HomeController.articulo', array('fila'=>$fila));
        }

}

