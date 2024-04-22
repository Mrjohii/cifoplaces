<?php
class PlaceController extends Controller{
    
    //operación por defecto
    public function index(){
        $this->list();          //redirige al metodo $list
    }
    
    //operación para listar los  lugares
    public function list(int $page =1){
        
        //Comprobar si hay filtors a aplicar /quitar/recuperar
        $filtro = Filter::apply('places');
        
        //Datos para paginación
        $limit = RESULTS_PER_PAGE;           //Resultados por página
        $total = $filtro ?
                    Place::filteredResults($filtro):
                    Place::total();///total de resultados
        
        //crea un objecto paginator
        $paginator = new Paginator('/place/list', $page, $limit, $total);
        
        $places = $filtro ?
                    Place::filter($filtro,$limit,$paginator->getOffset()):
                    Place::orderBy('name','DESC',$limit, $paginator->getOffset());
        
    

        //recupera la lista de libros y carga la vista
        //en la vista  dispondremos de una variable llamada $libros
        
        $this->loadView('place/list',[
            'places' => $places,
            'paginator'=>$paginator,     //pasamos el objecto Paginator a la vista
            'filtro'=>$filtro           
            
        ]);
        
    }
    
    //metodo que muestra los detalles de un lugar
    public function show(int $id = 0){
        //comprobar que recibimos el id del lugar por parametro
        if(!$id)
            throw new Exception("No se indicó el lugar a mostrar");
        
            $place = Place::find($id);  //recupera el lugar
            
           if(!$place)
               throw new NotFoundException("No se encotró el lugar indicado.");
           
               $photos = $place->hasMany('Photo');       //recupera las fotografias
              
           
           //carga la vista y le pasa el lugar
               $this->loadView('place/show',[
                   'place'=>$place,
                   'photos' =>$photos,
               ]);
    }
    
    //método que muestra el formulario de nuevo libro
    public function create(){

        $this->loadView('place/create');
    }
    
    //guarda el lugar
    public function store(){
        //comprueba que la petición venga del formulario
        if(!$this->request->has('guardar'))
            throw new Exception('No se recibió el formulario');
        
            $place = new Place();   //crea el nuevo lugar
            
            $place->name         = $this->request->post('name');
            $place->type         = $this->request->post('type');
            $place->location     = $this->request->post('location');
            $place->description  = $this->request->post('description');

            //recupera el id del idusuario
            $place->iduser = Login::User()->id;
            
             

       //con un try-catch local evitaremos ir directamente a la página de error
       //cuando no se puede guardar el libro y no estemos en DEBUG
       
      try{
          //si hay errores, no debemos guardar el libro. podemos lanzar excepciones
          //o bien flashear los errores y realizar una redirección
          
          if($errores = $place->validate()) //retorna array vacío si no hay errores 
                throw new ValidationException(arrayTostring($errores, false,false));
          
          
          
          // $ ->saneate();     //sanea las entradas
          $place->save();       //guardar el lugar
          
          if(Upload::arrive('cover')){//si llega el fichero con la portada...
              $place->cover = Upload::save(
                  'cover',                             //nombre del input
                  '../public/'.PLACES_IMAGE_FOLDER,     //ruta de la carpeta de destino
                  true,                               //generar nombre unico
                  2000000,                             ///tamaño mine
                  'image/*',                          //prefijo del nombre
                  'place_'
                  );
              
              $place->update();                       //añade la portada del lugar
          }
          
          //flashea un mensaje en sesión (para que no se borre al redireccionar)
          Session::success("Guardado lugar $place->name correcto.");
          redirect("/place/show/$place->id");      //redirecciona a los detalles
                
        }catch(SQLException $e){
            Session::error("No se puedo guardar el lugar $place->name.");
          
            //si estamos en modo debug,sí que iremos a la pagina de error
            if(DEBUG)
              throw new Exception($e->getMessage());
          
            //si no, volveremos al formulario de creación.
            //pondremos los valores antiguos en el formulario con los helpers old()
            else 
                redirect("/place/create");
     
      
      
            //si se produce un error en la subida del fichero(seria después de guardar)
        }catch(UploadException $e){
            Session::warning("El lugar se guardo correctamente, pero no se pudo subir el fichero de imagen.");
        
            if(DEBUG)
                throw new Exception($e->getMessage());
               
            else 
                redirect("/place/edit/$place->id");     //redireccion a la edicion 
    
        }
    }
 
    //muesta el formulario de edición del lugar
    public function edit(int $id = 0){
        
        Auth::check();
        
        $place = Place::findOrFail($id);
    
        if(Login::User()->id != $place->iduser) {
            Session::error('Este lugar no lo creastes tú');
            redirect("/place");
            return;
        }

        //comprueba que llega el id
        if(!$id)
            throw new Exception ('No se indicó el id');
        
       $place = Place::find($id); //recupera el lugar con ese id
       
       if(!$place) //comprueba que el lugar existe
            throw new NotFoundException('No existe el lugar indicado');
       
            
            //$ejemplares = $libro->hasMany('Ejemplar');       //recupera ejemplares
            //$temas = $libro->getTemas();                     //recupera los temas
            
            //$listaTemas = Tema::orderBy('tema','ASC');      //todos los temas odenados por el nombre ASC
            //$listaTemas = array_diff($listaTemas,$temas);   //temas que no tiene el libro
            
            //carga la vista y le pasa el lugar
            $this->loadView('place/edit',[
                'place'=>$place,                         //le paso los datos del lugar
                //'ejemplares' =>$ejemplares,             //los ejemplares del libro
                //'temas'      =>$temas,                  //los temas del libro
                //'listaTemas' =>$listaTemas              //todos los temas ordenados por nombre ASC
                  
            ]);
       
 
    }
    
    
    //actulizar los datos del libro
    public function update(){
        
        Auth::check();
        
        if(!$this->request->has('actualizar')) //si no llega el formulario...
            throw new Exception('No se recibieron datos');
        
        $id =intval($this->request->post('id'));      //recupere el id via post
        $place = Place::find($id);                    //recupera el lugar desde la BDD
        
        if(!$place) //si no hay lugar con ese id...
        throw new NotFoundException("No se ha encontrado el lugar $id.");
        
        
       //Recupera el resto de campos
       $place->name             = $this->request->post('name');
       $place->type             = $this->request->post('type');
       $place->location         = $this->request->post('location');
       $place->description      = $this->request->post('description');
   
       try{
           $place->update();
           
           $secondUpdate = false;            //flag para saber si hay que actualizar de nuevo
           $oldCover =$place->cover;         //portada antigua
           
           if(Upload::arrive('cover')){   //si llega una nueva portada
                $place->cover = Upload::save(
                        'cover',
                        '../public/'.PLACES_IMAGE_FOLDER,
                        true,
                        0,
                        'image/*',
                        'place_'
                        );
                $secondUpdate = true;
               
           }
           //si hay que eliminar portad, el libro tenia una anterior y no llega una nueva..
           if(isset($_POST['eliminarportada']) && $oldCover && !Upload::arrive('cover')){
               $place->cover = NULL;
               $secondUpdate = true;
           }
           
           if($secondUpdate){
               $place->update();            //aplicar los cambios en la base de datos (actualizar la portada)
               @unlink('../public/.'.PLACES_IMAGE_FOLDER.'/'.$oldCover);
           }
           
           Session::success("Actualización del lugar $place->name correcta.");
           redirect("/place/edit/$id");
           
       }catch(SQLException $e){
           Session::error("No se pudo actualizar el lugar $place->name.");
           
           //si estamos en modo debug, si que iremos a la pagina de error 
           if(DEBUG)
               throw new Exception($e->getMessage());
           
               //si no, volveremos al formulario de edición
           else 
               redirect("place/edit/$id");
           
       }catch(UploadException $e){
           Session::warning("El lugar se actualizó correctamente, pero no se pudo subir el nuveo fichero de image.");
           
           if(DEBUG)
               throw new Exception($e->getMessage());
            else 
                redirect ("/place/edit/$place->id");
       }
       
    
    }
    
    
    //muesta el formulario del confirmacion de borrado
    public function delete(int $id =0){
        
        //comprueba que llega el identificador
        if(!$id)
            throw new Exception("No se indicó el lugar a borrar.");
        
          //recupera el lugar con dicho idenrificador
          $place = Place::find($id);
          
          //comprueba que el lugar existe
          if(!$place)
              throw new NotFoundException("No existe el place $id.");
          $this->loadView('place/delete',[
                          'place'=>$place]);
          
    }
    
    //elimina el lugar
    public function destroy(){

        //comprueba que llegue el formulario de confirmacion
        if(!$this->request->has('borrar'))
            throw new Exception('No se recibio la confirmación');
        $id = intval($this->request->post('id'));   //recupera el identificador
        $place = Place::find($id);                  //recupera el libro
        
        //comprueba que el libro existe
        if(!$place)
            throw new NotFoundException("No existe el lugar $id.");
        
            
         //si el libro tiene ejemplares, no permitiremos su borrardo
       // if($libro->hasMany('Ejemplar'))
            //throw new Exception("No se puede borrar el libro mientras tengas ejemplares.");
        
            
            try{
                $place->deleteObject();
           
                
                //elimina la portada
                if($place->cover)
                    @unlink('../public/'.PLACES_IMAGE_FOLDER.'/'.$place->cover);
                
                    Session::success("Se ha borrado el libro $place->name.");
                    redirect("/place/list");

            }catch(SQLException $e){
                Session::error("No se pudo borrar el lugar $place->name.");
                
                if(DEBUG)
                    throw new Exception($e->getMessage());
                else
                    redirect ("/place/delete/$id");
                
            }
                            
    }
    
    
    //añade un tema a un libro
    public function addTema(){
        
        if(empty($_POST{'add'}))
            throw new Exception("No se recibió el formulario");
        
        $idlibro     =  intval($_POST['idlibro']);
        $idtema      =  intval($_POST['idtema']);
            
        $libro             = Libro::find($idlibro);
        $tema              = Tema::find($idtema);
        
        if(!$libro || !$tema)
            throw new Exception("Se deben indicar tema y libro");
        
        try{
            $libro->addTema($tema);
            Session::flash('success',"Se ha añadir el tema $tema->tema al libro $libro->titulo.");
            redirect("/Libro/edit/$idlibro");
       
        }catch(SQLException $e){
            Session::flash('error',"No se puedo añadir el tema $tema->tema al libro $libro->titulo");
            
            if(DEBUG)
                throw new Exception($e->getMessage());
            else 
                redirect("/Libro/edit/$idlibro");
       }
            
    }
    
    
    //elimina un tema de un libro
    public function removeTema(){
        
        if(empty($_POST['remove']))
            throw new Exception("No se recibió el formulario");
        
       $idlibro  = intval($_POST['idlibro']);       //recupera id libro
       $idtema   = intval($_POST['idtema']);        //reupera el id tema
       $libro    = Libro::find($idlibro);           //recupera el libro
       $tema     = Tema::find($idtema);             //recupera el tema
       
       if(!$libro  ||  !$tema)
           throw new Exception("Se deben indicar tema y libro");
 
    
      try{
          $libro->removeTema($tema);
          Session::flash('success',"Se ha eliminado el tema $tema->tema del libro $libro->titulo");
            redirect("/Libro/edit/$idlibro");
          
      }catch(SQLException $e){
          Session::flash('error',"No se pudo elimiar el tema $tema->tema del libro $libro->titulo.");
              
     
      if(DEBUG)
         throw new Exception($e->getMessage());
      else 
         redirect("/Libro/edit/$idlibro");
    }
  }

   
    
}



 