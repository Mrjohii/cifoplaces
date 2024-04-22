<?php 
    class Photo extends Model{
        
          //para la validacion de los datos de lugares
          public function validate():array{
            $errores =[]; //lista de errores 
            
            if(strlen($this->name)<1 || strlen($this->name)>64)
                $errores[]= "Error en la longitud del titulo";
            
            
            return $errores;    //retorna la lista de errores
        }

    }