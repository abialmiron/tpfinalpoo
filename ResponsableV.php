<?php
class ResponsableV{
    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;
    private $mensajeOperacion;

    public function __construct($numEmp, $numLic, $nom, $ape){
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
		$consultaInsertar="INSERT INTO responsablev(rnumeroempleado, rnumerolicencia, rnombre,rapellido)
				VALUES (".$this->getNumEmpleado().",'".$this->getNumLicencia()."','".$this->getNombre()."','".$this->getApellido()."')";
		
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

    public function __toString(){
        return 'Número de empleado: '. $this->getNumEmpleado() . "\n". 
        'Número de licencia: ' . $this->getNumLicencia() . "\n". 
        'Nombre: ' . $this->getNombre() . "\n" . 
        'Apellido: ' . $this->getApellido() . "\n";
    }
}
?>