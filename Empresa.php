<?php
class Empresa{
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $colViajes;
    private $mensajeOperacion;

    public function __construct($id,$nombre,$direccion){
        $this->idEmpresa = $id;
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

    public function setColViajes($nuevaCol){
        $this->colViajes = $nuevaCol;
    }
    
    public function getColViajes(){
        return $this->colViajes;
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

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(idempresa, enombre, edireccion)
				VALUES (".$this->getIdEmpresa().",'".$this->getNombre()."','".$this->getDireccion()."')";
		
		if($base->Iniciar()){

			if($base->Ejecutar($consultaInsertar)){

			    $resp=  true;

			}	else {
					$this->setMensajeOperacion($base->getError());
					
			}

		} else {
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp;
	}

}
?>