<?php
class Pasajero{
    private $nombre;
    private $apellido;
    private $dni;
    private $telefono;
    private $idViaje;
    private $mensajeOperacion;

    /******* CONSTRUCTOR *******/
    public function __construct(){
        $this->dni = 0;
        $this->nombre = '';
        $this->apellido = '';
        $this->idViaje = 0;
        $this->telefono = 0;
    }

    public function cargar($dniCargar, $nombreCargar, $apellidoCargar, $idViajeCargar, $telefonoCargar){
        $this->dni = $dniCargar;
        $this->nombre = $nombreCargar;
        $this->apellido = $apellidoCargar;
        $this->idViaje = $idViajeCargar;
        $this->telefono = $telefonoCargar;

    }
    /******* SETTERS Y GETTERS *******/

    /** Se encarga de setearle un nuevo valor al atributo DNI
     * @param int $dniNuevo
     */
    public function setDni($dniNuevo){
        $this->dni = $dniNuevo;
    }
    /** Se encarga de devolver el valor actual del atributo DNI
     * @return int $dni
     */
    public function getDni(){
        return $this->dni;
    }
    /** Se encarga de setearle un nuevo valor al atributo Nombre
     * @param string $nombreNuevo
     */
    public function setNombre($nombreNuevo){
        $this->nombre = $nombreNuevo;
    }
    /** Se encarga de devolver el valor actual del atributo Nombre
     * @return string $nombre
     */
    public function getNombre(){
        return $this->nombre;
    }
    /** Se encarga de setearle un nuevo valor al atributo Apellido
     * @param string $apellidoNuevo
     */
    public function setApellido($apellidoNuevo){
        $this->apellido = $apellidoNuevo;
    }
    /** Se encarga de devolver el valor actual del atributo Apellido
     * @return string $apellido
     */
    public function getApellido(){
        return $this->apellido;
    }
    public function setTelefono($nuevoTel){
        $this->telefono = $nuevoTel;
    }
    public function getTelefono(){
        return $this->telefono;
    }
    public function setIdViaje($nuevoIdViaje){
        $this->idViaje = $nuevoIdViaje;
    }
    public function getIdViaje(){
        return $this->idViaje;
    }

    public function setMensajeOperacion($nuevoMensaje){
        $this->mensajeOperacion = $nuevoMensaje;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }
    

    /**
	 * Recupera los datos de un pasajero por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function buscar($dni){
		$base=new BaseDatos();
		$consultaPasajero="Select * from pasajero where pdocumento=".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajero)){
				if($row2=$base->Registro()){
				    $this->setDni($dni);
					$this->setNombre($row2['pnombre']);
					$this->setApellido($row2['papellido']);
					$this->setTelefono($row2['ptelefono']);
                    $this->setIdViaje($row2['idviaje']);
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }		
		 return $resp;
	}

    /**
	 * Inserta los datos de un pasajero
	 * @return $resp true en caso de poder insertar los datos, false en caso contrario 
	 */	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje)
				VALUES (".$this->getDni().",'".$this->getNombre()."','".$this->getApellido()."',". $this->getTelefono(). ",'".$this->getIdViaje()."')";
		
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

    /**
	 * Lista a los pasajeros, se le puede pasar una condición para filtrar la lista
     * @param string $condicion
	 * @return $arregloPasajeros
	 */	
    public static function listar($condicion=""){
	    $arregloPasajeros = null;
		$base=new BaseDatos();
		$consultaPasajeros="Select * from pasajero ";
		if ($condicion!=""){
		    $consultaPasajeros=$consultaPasajeros.' where '.$condicion;
		}
		$consultaPasajeros.=" order by papellido ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajeros)){				
				$arregloPasajeros= array();
				while($row2=$base->Registro()){
				    $nrodoc=$row2['pdocumento'];
					$nombre=$row2['pnombre'];
					$apellido=$row2['papellido'];
					$telefono=$row2['ptelefono'];
					$idViaje=$row2['idviaje'];
				
					$pasaj=new Pasajero();
					$pasaj->cargar($nrodoc, $nombre, $apellido, $idViaje, $telefono);
					array_push($arregloPasajeros,$pasaj);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloPasajeros;
	}	

    public function __toString(){
        return 'DNI: ' . $this->getDni() . "\n" . 
        'Nombre: ' . $this->getNombre() . "\n" . 
        'Apellido: ' . $this->getApellido(). "\n" . 
        'Telefono: ' . $this->getTelefono() . "\n" .
        'Id viaje: ' . $this->getIdViaje() . "\n";
    }

}
?>