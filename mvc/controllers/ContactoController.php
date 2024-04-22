<?php
    class ContactoController extends Controller{
    
        public function index(){
            //carga la vista con el formulario de contacto
            $this->loadView('contacto');
        }
        
        //Método qe envia el email al administrador
        public function send(){
            
            if(empty($_POST['enviar']))
                throw new Exception('No se recibio el formulario de contacto');
            
           //toma los datos del formulario  de contacto
           $from        = $_POST['email'];
           $name        = $_POST['nombre'];
           $subject     = $_POST['asunto'];
           $message     = $_POST['mensaje'];
           
           //comprobar el reCaptcha
           $reCaptcha = new Recaptcha('6Lf6SaYpAAAAAHpN7a3x74iagATleMwJ5Vzs1sP_');
           
           $response = $reCaptcha->verifyResponse(
               $_SERVER['REMOTE_ADDR'], $_POST['g-recaptcha-response']);
           
           if(!$response || !$response->success)
               throw new Exception ('ERROR al validar reCaptcha');
           
           //Prepara y envia el mail
           $email = new Email(ADMIN_EMAIL, $from,$name,$subject,$message);
           $email->send();
           
           try{
               $email->send();
               
               Session::success("Mensaje enviado, en breve recibiras una respuesta");
               redirect("/");
               
           }catch(EmailException){
               Session::error("Mensaje no enviado");
               redirect("/");
           
        }
    }
           
            
 

            
        
    
   }
