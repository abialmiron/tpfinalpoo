<?php
class Viaje{
    //Guarda los datos de un viaje 
    //los atributos son: int $cod_viaje, string $destino, int $cantMaximaPasajeros, array $pasajeros
    private $cod_viaje;
    private $destino;
    private $cantMaximaPasajeros;
    private $pasajeros;
    private $responsable;
    private $costo;
    private $sumCosto;
    private $empresa;
    private $mensajeOperacion;

    /************* Metodo constructor *************/
    public function __construct(){
        $this->cod_viaje = 0;
        $this->destino = '';
        $this->cantMaximaPasajeros = 0;
        $this->pasajeros = 0;
        $this->responsable = 0;
        $this->costo = 0;
        $this->empresa = 0;
    }

    public function cargar($codViaje,$des, $cantmaxpasajeros, $pas,$res,$cos,$emp){
        $this->cod_viaje = $codViaje;
        $this->destino = $des;
        $this->cantMaximaPasajeros = $cantmaxpasajeros;
        $this->pasajeros = $pas;
        $this->responsable = $res;
        $this->costo = $cos;
        $this->empresa = $emp;

    }

    /*************** SETTERS Y GETTERS ********************/
    /** Coloca el valor pasado por parámetro en el atributo cod_viaje
    *@param int $codviaje */
    public function setCodViaje($codviaje){
        $this->cod_viaje = $codviaje;
    }
    /** Devuelve el valor actual almacenado en el atributo cod_viaje
    * @return int */
    public function getCodViaje(){
        return $this->cod_viaje;
    }
    /** Coloca el valor pasado por parámetro en el atributo destino
    * @param string $des */
    public function setDestino($des){
        $this->destino = $des;
    }
    /*Devuelve el valor actual almacenado en el atributo destino
    @return string $destino*/
    public function getDestino(){
        return $this->destino;
    }
    /** Coloca el valor pasado por parámetro en el atributo cantMaximaPasajeros
    * @param int $cantMaxPasajeros 
    */
    public function setCantMaximaPasajeros($cantMaxPasajeros){
        $this->cantMaximaPasajeros = $cantMaxPasajeros;
    }
    /** Devuelve el valor actual almacenado en el atributo cantMaximaPasajeros
    * @return int $cantMaximaPasajeros
    */
    public function getCantMaximaPasajeros(){
        return $this->cantMaximaPasajeros;
    }
    /** Coloca el valor pasado por parámetro en el atributo pasajeros 
    *@param array $pasajeros 
    */
    public function setPasajeros($pasajeros){
        $this->pasajeros = $pasajeros;
    }
    /** Devuelve el valor actual almacenado en el atributo pasajeros
    * @return array $pasajeros
    */
    public function getPasajeros(){
        return $this->pasajeros;
    }
     /** Coloca el valor pasado por parámetro en el atributo responsable 
    *@param Responsable $res 
    */
    public function setResponsable($res){
        $this->responsable = $res;
    }
    /** Devuelve el valor actual almacenado en el atributo responsable
    * @return Responsable $responsable
    */
    public function getResponsable(){
        return $this->responsable;
    }
    /** Coloca el valor pasado por parámetro en el atributo costo 
    *@param float $costo 
    */
    public function setCosto($costo){
        $this->costo = $costo;
    }
    /** Devuelve el valor actual almacenado en el atributo costo
    * @return $costo
    */
    public function getCosto(){
        return $this->costo;
    }

    public function setSumCosto($sumCosto){
        $this->sumCosto = $sumCosto;
    }
    public function getSumCosto(){
        return $this->sumCosto;
    }

    public function setEmpresa($nuevoEmp){
        $this->empresa = $nuevoEmp;
    }
    public function getEmpresa(){
        return $this->empresa;
    }

    public function setMensajeOperacion($nuevoMensaje){
        $this->mensajeOperacion = $nuevoMensaje;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    /************ METODOS PROPIOS DE LA CLASE ************/

     /** Este método se encarga de mostrar la colección de pasajeros de una forma mas entendible.
     *  @return string $mensaje
     */
    public function mostrarPasajeros(){
        $mensaje = '';
        $colPasajeros = $this->getPasajeros();
        if(count($colPasajeros) > 0){
            for ($i=0;$i < count($colPasajeros); $i++){
                $mensaje .= '---------------' . "\n" . $colPasajeros[$i]."\n";
            }
        }
        return $mensaje;
    }
    
    /** Este método se encarga de traer los datos de la bd de los pasajeros relacionados con el viaje
     * y setearlos en el atributo pasajeros.
     */

    public function traePasajeros(){
        $pasajero = new Pasajero();
        $condicion = "idviaje =".$this->getCodViaje();
        $colPasajeros = $pasajero->listar($condicion);
        $this->setPasajeros($colPasajeros);
    }

    public function venderPasaje(){
        $cos = $this->getCosto();
        $sumCosto = $this->getSumCosto();

        $sumCosto = $sumCosto + $cos;
        $this->setSumCosto($sumCosto);
    }

    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function buscar($codviaje){
		$base=new BaseDatos();
		$consultaViaje="Select * from viaje where idviaje=".$codviaje;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){
				if($row2=$base->Registro()){
                    $objEmpresa = new Empresa();
                    $objEmpresa->buscar($row2['idempresa']);
                    $objResponsable = new ResponsableV();
                    $objResponsable->buscar($row2['rnumeroempleado']);
                    $this->cargar($codviaje,$row2['vdestino'],$row2['vcantmaxpasajeros'],[],$objResponsable,$row2['vimporte'],$objEmpresa);
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
		$resp= null;
		$consultaInsertar="INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado,vimporte)
				VALUES ('".$this->getDestino()."',".$this->getCantMaximaPasajeros().",". $this->getEmpresa()->getIdEmpresa(). ",".$this->getResponsable()->getNumEmpleado().",".$this->getCosto().");";
		
		if($base->Iniciar()){
            $id = $base->devuelveIDInsercion($consultaInsertar);
			if($id != null){
			    $resp=  true;
                $this->setCodViaje($id);
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
	    $arregloViajes = null;
		$base=new BaseDatos();
		$consultaViajes="Select * from viaje";
		if ($condicion!=""){
		    $consultaViajes=$consultaViajes.' where '.$condicion;
		}
		$consultaViajes.=" order by idviaje ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViajes)){				
				$arregloViajes= array();
				while($row2=$base->Registro()){
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
                    array_push($arregloViajes,$objViaje);
				}
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());
		 	
		 }	
		 return $arregloViajes;
	}	

    
	public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getDestino()."',vcantmaxpasajeros=".$this->getCantMaximaPasajeros()."
                           ,idempresa=".$this->getEmpresa()->getIdEmpresa().",rnumeroempleado=". $this->getResponsable()->getNumEmpleado().",vimporte=" .$this->getCosto() ." WHERE idviaje =".$this->getCodViaje();
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
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getCodViaje();
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
        $mensaje = $this->mostrarPasajeros();
        return "Código de viaje: " . $this->getCodViaje() . "\n".  
        "Destino: " . $this->getDestino() . "\n" .
        "Cantidad máxima de pasajeros: " . $this->getCantMaximaPasajeros() . "\n".
        "Costo: " . $this->getCosto() . "\n" . 
        "Suma de costos: " . $this->getSumCosto() . "\n" . 
        "Empresa: " . $this->getEmpresa() . "\n" .
        "Responsable: " . "\n" .$this->getResponsable() . "\n" . 
        $mensaje;
    }   
}
?>