<?php
class PhotoController extends Controller{
    
    //operación por defecto
    public function index(){
        $this->list();          //redirige al metodo $list
    }
    
    //operación para listar los  lugares
    public function list(int $page =1){
        
        //Comprobar si hay filtors a aplicar /quitar/recuperar
        $filtro = Filter::apply('photos');
        
        //Datos para paginación
        $limit = RESULTS_PER_PAGE;           //Resultados por página
        $total = $filtro ?
                    Photo::filteredResults($filtro):
                    Photo::total();///total de resultados
        
        //crea un objecto paginator
        $paginator = new Paginator('/photo/list', $page, $limit, $total);
        
        $photos = $filtro ?
                    Photo::filter($filtro,$limit,$paginator->getOffset()):
                    Photo::orderBy('name','DESC',$limit, $paginator->getOffset());
        
    

        //recupera la lista de libros y carga la vista
        //en la vista  dispondremos de una variable llamada $libros
        
        $this->loadView('photo/list',[
            'photos' => $photos,
            'paginator'=>$paginator,     //pasamos el objecto Paginator a la vista
            'filtro'=>$filtro           
            
        ]);
        
    }
    
    //metodo que muestra los detalles de un lugar
    public function show(int $id = 0){
        //comprobar que recibimos el id del lugar por parametro
        if(!$id)
            throw new Exception("No se indicó la foto a mostrar");
        
            $photo = Photo::find($id);  //recupera el lugar
            
           if(!$photo)
               throw new NotFoundException("No se encotró la foto indicado.");
           
              // $ejemplares = $libro->hasMany('Ejemplar');       //recupera ejemplares
              // $temas      = $libro->getTemas();                //recupera temas
           
           //carga la vista y le pasa el lugar
               $this->loadView('photo/show',[
                   'photo'=>$photo,
                   //'ejemplares' =>$ejemplares,
                   //'temas'=>$temas
               ]);
    }
    
    //método que muestra el formulario de nueva foto
    public function create(int $id){
        $place = Place::find($id);

        $this->loadView('photo/create',[
                        'place'=>$place
        ]);
    }
    
    //guarda la foto
    public function store(){

        Auth::check();
 
        //comprueba que la petición venga del formulario
        if(!$this->request->has('guardar'))
            throw new Exception('No se recibió el formulario');
        
            $photo = new Photo();   //crea la nueva foto
            
            $photo->name         = $this->request->post('name');
            $photo->description  = $this->request->post('description');
            $photo->date         = $this->request->post('date');
            $photo->time         = $this->request->post('time');

            //recupera el id del idusuario
            $photo->iduser = Login::User()->id;

            //recupera el id del lugar 
            $photo->idplace = $this->request->post('idplace');
          
       //con un try-catch local evitaremos ir directamente a la página de error
       //cuando no se puede guardar la fotografia y no estemos en DEBUG
       
      try{
          //si hay errores, no debemos guardar la fotografia. podemos lanzar excepciones
          //o bien flashear los errores y realizar una redirección
          
          if($errores = $photo->validate()) //retorna array vacío si no hay errores 
                throw new ValidationException(arrayTostring($errores, false,false));
          
          // $ ->saneate();     //sanea las entradas
          $photo->save();       //guardar la foto
          
          if(Upload::arrive('photo')){//si llega el fichero..
              $photo->file = Upload::save(
                  'photo',                             //nombre del input
                  '../public/'.PHOTOS_IMAGE_FOLDER,     //ruta de la carpeta de destino
                  true,                               //generar nombre unico
                  2000000,                             ///tamaño mine
                  'image/*',                          //prefijo del nombre
                  'photo_'
                  );
              
              $photo->update();                       //añade la foto
          }
          
          //flashea un mensaje en sesión (para que no se borre al redireccionar)
          Session::success("Guardado la fotografia $photo->name correcto.");
          redirect("/");      //redirecciona a los detalles
                
        }catch(SQLException $e){
            Session::error("No se puedo guardar la foto $photo->name.");
          
            //si estamos en modo debug,sí que iremos a la pagina de error
            if(DEBUG)
              throw new Exception($e->getMessage());
          
            //si no, volveremos al formulario de creación.
            //pondremos los valores antiguos en el formulario con los helpers old()
            else 
                redirect("/photo/create");
     
      
      
            //si se produce un error en la subida del fichero(seria después de guardar)
        }catch(UploadException $e){
            Session::warning("La foto se guardo correctamente, pero no se pudo subir el fichero de imagen.");
        
            if(DEBUG)
                throw new Exception($e->getMessage());
               
            else 
                redirect("/photo/edit/$photo->id");     //redireccion a la edicion 
    
        }
    }
 
    //muesta el formulario de edición del lugar
    public function edit(int $id = 0){
        
        Auth::check();
        
        $photo = Photo::findOrFail($id);
    
        if(Login::User()->id != $photo->iduser) {
            Session::error('Está fotografia no es tuya');
            redirect("/place");
            return;
        }

        //comprueba que llega el id
        if(!$id)
            throw new Exception ('No se indicó el id');
        
       $photo = Photo::find($id); //recupera el lugar con ese id
       
       if(!$photo) //comprueba que el lugar existe
            throw new NotFoundException('No existe la fotografia indicado');
       
            
            //$ejemplares = $libro->hasMany('Ejemplar');       //recupera ejemplares
            //$temas = $libro->getTemas();                     //recupera los temas
            
            //$listaTemas = Tema::orderBy('tema','ASC');      //todos los temas odenados por el nombre ASC
            //$listaTemas = array_diff($listaTemas,$temas);   //temas que no tiene el libro
            
            //carga la vista y le pasa el lugar
            $this->loadView('photo/edit',[
                'photo'=>$photo,                         //le paso los datos del lugar
                //'ejemplares' =>$ejemplares,             //los ejemplares del libro
                //'temas'      =>$temas,                  //los temas del libro
                //'listaTemas' =>$listaTemas              //todos los temas ordenados por nombre ASC
                  
            ]);
       
 
    }
    
    
    //actulizar los datos de la foto
    public function update(){
        
        Auth::check();
        
        if(!$this->request->has('actualizar')) //si no llega el formulario...
            throw new Exception('No se recibieron datos');
        
        $id =intval($this->request->post('id'));      //recupere el id via post
        $photo = Photo::find($id);                    //recupera el lugar desde la BDD
        
        if(!$photo) //si no hay lugar con ese id...
        throw new NotFoundException("No se ha encontrado el lugar $id.");
        
        
       //Recupera el resto de campos
       $photo->name         = $this->request->post('name');
       $photo->description  = $this->request->post('description');
       $photo->date         = $this->request->post('date');
       $photo->time         = $this->request->post('time');
   
       try{
           $photo->update();
           
           $secondUpdate = false;            //flag para saber si hay que actualizar de nuevo
           $oldCover =$photo->file;         //portada antigua
           
           if(Upload::arrive('file')){   //si llega una nueva portada
                $photo->file = Upload::save(
                        'photo',
                        '../public/'.PHOTOS_IMAGE_FOLDER,
                        true,
                        0,
                        'image/*',
                        'photo_'
                        );
                $secondUpdate = true;
               
           }
           //si hay que eliminar portad, el libro tenia una anterior y no llega una nueva..
           if(isset($_POST['eliminarportada']) && $oldCover && !Upload::arrive('file')){
               $photo->file = NULL;
               $secondUpdate = true;
           }
           
           if($secondUpdate){
               $photo->update();            //aplicar los cambios en la base de datos (actualizar la portada)
               @unlink('../public/.'.PHOTOS_IMAGE_FOLDER.'/'.$oldCover);
           }
           
           Session::success("Actualización del lugar $photo->name correcta.");
           redirect("/photo/edit/$id");
           
       }catch(SQLException $e){
           Session::error("No se pudo actualizar el lugar $photo->name.");
           
           //si estamos en modo debug, si que iremos a la pagina de error 
           if(DEBUG)
               throw new Exception($e->getMessage());
           
               //si no, volveremos al formulario de edición
           else 
               redirect("photo/edit/$id");
           
       }catch(UploadException $e){
           Session::warning("La fotografia se actualizó correctamente, pero no se pudo subir el nuveo fichero de image.");
           
           if(DEBUG)
               throw new Exception($e->getMessage());
            else 
                redirect ("/photo/edit/$photo->id");
       }
       
    
    }
    
    
    //muesta el formulario del confirmacion de borrado
    public function delete(int $id =0){
        
        //comprueba que llega el identificador
        if(!$id)
            throw new Exception("No se indicó la fotografia a borrar.");
        
          //recupera el lugar con dicho idenrificador
          $photo = Photo::find($id);
          
          //comprueba que el lugar existe
          if(!$photo)
              throw new NotFoundException("No existe la fotografia $id.");
          $this->loadView('photo/delete',[
                          'photo'=>$photo]);
          
    }
    
    //elimina el lugar
    public function destroy(){

        //comprueba que llegue el formulario de confirmacion
        if(!$this->request->has('borrar'))
            throw new Exception('No se recibio la confirmación');
        $id = intval($this->request->post('id'));   //recupera el identificador
        $photo = Photo::find($id);                  //recupera la fotografia
        
        //comprueba que el libro existe
        if(!$photo)
            throw new NotFoundException("No existe la fotografia $id.");
        
            
         //si el libro tiene ejemplares, no permitiremos su borrardo
       // if($libro->hasMany('Ejemplar'))
            //throw new Exception("No se puede borrar el libro mientras tengas ejemplares.");
        
            
            try{
                $photo->deleteObject();
           
                
                //elimina la portada
                if($photo->file)
                    @unlink('../public/'.PHOTOS_IMAGE_FOLDER.'/'.$photo->file);
                
                    Session::success("Se ha borrado la fotografia $photo->name.");
                    redirect("/");

            }catch(SQLException $e){
                Session::error("No se pudo borrar la fotografia $photo->name.");
                
                if(DEBUG)
                    throw new Exception($e->getMessage());
                else
                    redirect ("/photo/delete/$id");
                
            }
                            
    }
    
    
    
}



 