<?php
class ResponsableV{
    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;
    private $mensajeOperacion;

    public function __construct(){
        $this->numEmpleado = '';
        $this->numLicencia = '';
        $this->nombre = '';
        $this->apellido = '';
    }

    public function cargar($numEmp, $numLic, $nom, $ape){
        $this->numEmpleado = $numEmp;
        $this->numLicencia = $numLic;
        $this->nombre = $nom;
        $this->apellido = $ape;
    }

    public function setNumEmpleado($numEmpleadoNuevo){
        $this->numEmpleado = $numEmpleadoNuevo;
    }
    public function getNumEmpleado(){
        return $this->numEmpleado;
    }
    public function setNumLicencia($numLicenciaNuevo){
        $this->numLicencia = $numLicenciaNuevo;
    }
    public function getNumLicencia(){
        return $this->numLicencia;
    }
    public function setNombre($nombreNuevo){
        $this->nombre = $nombreNuevo;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setApellido($apellidoNuevo){
        $this->apellido = $apellidoNuevo;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function setMensajeOperacion($nuevoMensaje){
        $this->mensajeOperacion = $nuevoMensaje;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO responsablev(rnumerolicencia, rnombre,rapellido)
				VALUES (".$this->getNumLicencia().",'".$this->getNombre()."','".$this->getApellido()."')";
		
		if($base->Iniciar()){
            $id = $base->devuelveIDInsercion($consultaInsertar);
			if($id != null){
			    $resp=  $id;
                $this->setNumEmpleado($id);
			}	else {
					$this->setMensajeOperacion($base->getError());
					
			}

		} else {
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp;
	}

    public function buscar($numEmpleado){
		$base=new BaseDatos();
		$consultaResp="Select * from responsablev where rnumeroempleado=".$numEmpleado;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResp)){
				if($row2=$base->Registro()){
				    $this->setNumEmpleado($numEmpleado);
				    $this->setNumLicencia($row2['rnumerolicencia']);
					$this->setNombre($row2['rnombre']);
					$this->setApellido($row2['rapellido']);
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

    public function __toString(){
        return 'Número de empleado: '. $this->getNumEmpleado() . "\n". 
        'Número de licencia: ' . $this->getNumLicencia() . "\n". 
        'Nombre: ' . $this->getNombre() . "\n" . 
        'Apellido: ' . $this->getApellido() . "\n";
    }
}
?>