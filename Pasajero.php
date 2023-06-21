<?php
class Pasajero{
    private $nombre;
    private $apellido;
    private $dni;
    private $telefono;
    private $objViaje;
    private $mensajeOperacion;

    /******* CONSTRUCTOR *******/
    public function __construct(){
        $this->dni = 0;
        $this->nombre = '';
        $this->apellido = '';
        $this->objViaje = 0;
        $this->telefono = 0;
    }

    public function cargar($dniCargar, $nombreCargar, $apellidoCargar, $objViaje, $telefonoCargar){
        $this->dni = $dniCargar;
        $this->nombre = $nombreCargar;
        $this->apellido = $apellidoCargar;
        $this->objViaje = $objViaje;
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
    public function setObjViaje($nuevoObjViaje){
        $this->objViaje = $nuevoObjViaje;
    }
    public function getObjViaje(){
        return $this->objViaje;
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
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
					$this->cargar($dni,$row2['pnombre'],$row2['papellido'],$objViaje,$row2['ptelefono']);
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

    /**
	 * Inserta los datos de un pasajero
	 * @return $resp true en caso de poder insertar los datos, false en caso contrario 
	 */	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje)
				VALUES (".$this->getDni().",'".$this->getNombre()."','".$this->getApellido()."',". $this->getTelefono(). ",'".$this->getObjViaje()->getCodViaje()."')";
		
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
    public function listar($condicion=""){
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
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
				
					$pasaj=new Pasajero();
					$pasaj->cargar($nrodoc, $nombre, $apellido, $objViaje, $telefono);
					array_push($arregloPasajeros,$pasaj);
	
				}
				
			
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());
		 	
		 }	
		 return $arregloPasajeros;
	}	

    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE pasajero SET pnombre='".$this->getNombre()."',papellido='".$this->getApellido()."'
                           ,ptelefono=".$this->getTelefono().",idviaje=". $this->getObjViaje()->getCodViaje()." WHERE pdocumento =".$this->getDni();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setMensajeOperacion($base->getError());
				
			}
		}else{
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp;
	}
	
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM pasajero WHERE pdocumento=".$this->getDni();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setMensajeOperacion($base->getError());
					
				}
		}else{
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp; 
	}


    public function __toString(){
        return 'DNI: ' . $this->getDni() . "\n" . 
        'Nombre: ' . $this->getNombre() . "\n" . 
        'Apellido: ' . $this->getApellido(). "\n" . 
        'Telefono: ' . $this->getTelefono() . "\n" .
    	"Viaje: \n" . $this->getObjViaje()->getCodViaje() . "\n";
    }

}
?>