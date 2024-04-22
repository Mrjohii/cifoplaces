<?php
class UserController extends Controller{

      
    public function home(){
        
        Auth::check();      //autorizacion (solo identificados)

        $user = Login::user();      //recupera el usuario
 
        //$anuncio = $user->hasMany('Anuncio' ,'idusers'); // recupera los anuncios
        
        //carga la vista y le pasa el libro
        $this->loadView('user/home',[
            'user'=>Login::user(),
            //'anuncios'=>$anuncio
        ]);
       
    }
      
    //metodo que muestra el formulario de nuevo usuario
    public function create(){
        
        Auth::guest();  //solo para usuarios sin registrar
        
        $this->loadView('user/create');
    }
    
    
    //guarda el nuevo usuario
    public function store(){
        
        if(empty($_POST['enviar']))
           throw new Exception('No se recibio el formulario');
        
       $user = new User();      //crea el nuevo usuario

       //comprobación de que los passwords coinciden
       $user->password = md5($_POST['password']);
       $repeat         = md5($_POST['repeatpassword']);
       
       if($user->password != $repeat)
           throw new Exception("Las claves no coinciden");
       
       $user->displayname       = $_POST['displayname'];
       $user->email             = $this->request->post('email');
       $user->phone             = $this->request->post('phone');
       
       $user->addRole('ROLE_USER');
       
        try{
           $user->save();           //guarda el usuario
           
           if(Upload::arrive('picture')){   //si llega el fichero con la imagen...
               $user->picture = Upload::save(
                   'picture',
                   '../public/'.USER_IMAGE_FOLDER,
                   true,
                   0,
                   'image/*',
                   'user_'
                   );
               $user->update();     //añade la imagen al usuario
        }
       
       Session::Success("Nuevo usuario $user->displayname creado con exito.");
       redirect("/login");
       
        }catch(SQLException $e){
           Session::error("Se produjo un error al guardar el usuario $user->displayname");

           if(DEBUG)
            throw new Exception($e->getMessage());
           else
               redirect("/user/create");
           
        }catch(UploadException $e){
           Session::warning("El usuario se guardo correctamente, pero no se puedo subir el fichero de imagen");
           
           if(DEBUG)
               throw new Exception($e->getMessage());
           else 
               redirect("/");
        }
           
    }


    //muesta el formulario de edición del perfil
    public function edit(){

      $user = Login::User();

            //carga la vista y le pasa el lugar
            $this->loadView('/user/edit',[
                            'user'=>$user]);
    
    }
       
    
    //actualizar perfil
    public function update(){

        $user = Login::User();
        
        if(!$this->request->has('actualizar')) //si no llega el formulario...
            throw new Exception('No se recibieron datos');
        
        $id =intval($this->request->post('id'));      //recupere el id via post
                              
        if(!$user) //si no hay lugar con ese id...
        throw new NotFoundException("No se ha encontrado el usuario $id.");
        
        
       //Recupera el resto de campos
       $user->displayname         = $this->request->post('displayname');
       $user->email               = $this->request->post('email');
       $user->phone               = $this->request->post('phone');
       
   
       try{
           $user->update();
           
           $secondUpdate = false;            //flag para saber si hay que actualizar de nuevo
           $oldCover =$user->picture;         //foto de perfil
           
           if(Upload::arrive('picture')){   //si llega una nueva portada
                $user->picture = Upload::save(
                        'picture',
                        '../public/'.USERS_IMAGE_FOLDER,
                        true,
                        0,
                        'image/*',
                        'user_'
                        );
                $secondUpdate = true;
               
           }
           //si hay que eliminar portad, el libro tenia una anterior y no llega una nueva..
           if(isset($_POST['eliminarportada']) && $oldCover && !Upload::arrive('picture')){
               $user->picture = NULL;
               $secondUpdate = true;
           }
           
           if($secondUpdate){
               $user->update();            //aplicar los cambios en la base de datos (actualizar la portada)
               @unlink('../public/.'.USERS_IMAGE_FOLDER.'/'.$oldCover);
           }
           
           Session::success("Actualización del perfil $user->displayname correcta.");
           redirect("/user/edit/$id");
           
       }catch(SQLException $e){
           Session::error("No se pudo actualizar el perfil $user->displayname.");
           
           //si estamos en modo debug, si que iremos a la pagina de error 
           if(DEBUG)
               throw new Exception($e->getMessage());
           
               //si no, volveremos al formulario de edición
           else 
               redirect("user/edit/$id");
           
       }catch(UploadException $e){
           Session::warning("La fotografia de perfil se actualizó correctamente, pero no se pudo subir el nuveo fichero de image.");
           
           if(DEBUG)
               throw new Exception($e->getMessage());
            else 
                redirect ("/user/edit/$user->id");
       }
       
    
    }

    //muesta el formulario del confirmacion de borrado
    public function delete(int $id =0){
        
        
        $user = Login::User()->id;
        //comprueba que llega el identificador
        if(!$id)
            throw new Exception("No se indicó la fotografia a borrar.");

          //comprueba que el lugar existe
          if(!$user)
              throw new NotFoundException("No existe la fotografia $id.");
          $this->loadView('user/delete',[
                            'user'=>$user]);
          
    }
    
    //elimina el perfil de usuario
    public function destroy(){

        //comprueba que llegue el formulario de confirmacion
        if(!$this->request->has('borrar'))
            throw new Exception('No se recibio la confirmación');
        $id = intval($this->request->post('id'));   //recupera el identificador
        $user = User::find($id);                   //recupera la fotografia
        
        //comprueba que el libro existe
        if(!$user)
            throw new NotFoundException("No existe el usuario $id.");
        
        //si el libro tiene ejemplares, no permitiremos su borrardo
        // if($libro->hasMany('Ejemplar'))
            //throw new Exception("No se puede borrar el libro mientras tengas ejemplares.");
        
            
            try{
                $user->deleteObject();
                
            
                //elimina la foto de usuario
                if($user->picture)
                    @unlink('../public/'.USERS_IMAGE_FOLDER.'/'.$user->picture);
                
                    Session::success("Se ha borrado la foto de perfil $user->displayname.");
                    redirect("/");

            }catch(SQLException $e){
                Session::error("No se pudo borrar la fotografia $user->displayname.");
                
                if(DEBUG)
                    throw new Exception($e->getMessage());
                else
                    redirect ("/user/delete/$id");
                
            }
                            
    }

       //comprueba si un usurario existe
       public function registered(string $email=''){

        $response = new stdClass();//solo para administradores
        if(!Login::isAdmin()){
            $response->status ='NOT AUTHORIZED';
            $response->registered ='UNKNOWN';
        }else{
            try{
                $response->status = 'OK';
                $response->registered = User::checkEmail($email);
            }catch(SQLException $e){
                $response->status = 'ERROR';
                $response->registered = 'UNKNOWN';
            }
        }
        
        header('Content-Type: application/json'); // el content-type des JSON
        echo json_encode($response);    //imprimi el JSON que llega al navegador

    }
}
    