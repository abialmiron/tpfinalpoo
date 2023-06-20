<?php
class Empresa{
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $mensajeOperacion;

    public function __construct(){
        $this->idEmpresa = 0;
        $this->nombre = '';
        $this->direccion = '';
    }

    public function cargar($idEmpresa, $nombre, $direccion){
        $this->idEmpresa = $idEmpresa;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
    }

    public function setIdEmpresa($nuevoId){
        $this->idEmpresa = $nuevoId;
    }
    public function getIdEmpresa(){
        return $this->idEmpresa;
    }
    public  function setNombre($nuevoNombre){
        $this->nombre = $nuevoNombre;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setDireccion($nuevaDireccion){
        $this->direccion = $nuevaDireccion;
    }
    public function getDireccion(){
        return $this->direccion;
    }
    public function setMensajeOperacion($nuevoMensaje){
        $this->mensajeOperacion = $nuevoMensaje;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    public function __toString(){
        return 'ID: ' . $this->getIdEmpresa() . "\n" . 
        'Nombre: ' . $this->getNombre() . "\n" . 
        'Direccion: ' . $this->getDireccion() . "\n";
    }

    /**
	 * Recupera los datos de una empresa por id
	 * @param int $idEmpresa
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function buscar($idEmpresa){
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa where idempresa=".$idEmpresa;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){
				if($row2=$base->Registro()){
				    $this->setIdEmpresa($idEmpresa);
					$this->setNombre($row2['enombre']);
					$this->setDireccion($row2['edireccion']);

                    
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());
		 	
		 }		
		 return $resp;
	}

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(enombre, edireccion)
				VALUES ('".$this->getNombre()."','".$this->getDireccion()."')";
		
		if($base->Iniciar()){
            $id = $base->devuelveIDInsercion($consultaInsertar);
			if($id != null){
			    $resp=  $id;
				$this->setIdEmpresa($id);
			}	else {
					$this->setMensajeOperacion($base->getError());
					
			}

		} else {
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp;
	}

    public static function listar($condicion=""){
	    $arregloEmp = null;
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa ";
		if ($condicion!=""){
		    $consultaEmpresa=$consultaEmpresa.' where '.$condicion;
		}
		$consultaEmpresa.=" order by enombre ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){				
				$arregloEmp= array();
				while($row2=$base->Registro()){
				    $idEmpresa=$row2['idempresa'];
					$nombre=$row2['enombre'];
					$direccion=$row2['edireccion'];

                    $objViaje = new Viaje();
					$colViajes = $objViaje->listar("idempresa =" .$idEmpresa);
				
					$emp=new Empresa();
					$emp->cargar($idEmpresa,$nombre,$direccion,[]);
					array_push($arregloEmp,$emp);
	
				}
				
			
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());
		 	
		 }	
		 return $arregloEmp;
	}	

}
?>