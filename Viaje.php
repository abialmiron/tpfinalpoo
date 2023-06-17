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
    public function __construct($codviaje, $des, $cantmaxpasajeros, $pas,$res,$cos,$emp){
        $this->cod_viaje = $codviaje;
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

    /** Este método se encarga de traer los datos de la bd de los pasajeros relacionados con el viaje
     * y setearlos en el atributo pasajeros.
     */

    public function traePasajeros(){
        $pasajero = new Pasajero();
        $condicion = "idViaje =".$this->getCodViaje();
        $colPasajeros = $pasajero->listar($condicion);
        $this->setPasajeros($colPasajeros);
    }

    /** Este método se encarga de mostrar la colección de pasajeros de una forma mas entendible.
     *  @return string $mensaje
     */
    public function mostrarPasajeros(){
        $mensaje = '';
        $colPasajeros = $this->getPasajeros();
        for ($i=0;$i < count($colPasajeros); $i++){
            $mensaje .= '---------------' . "\n" . $colPasajeros[$i] . "\n";
        }
        return $mensaje;
    }

    public function venderPasaje($objPasajero){
        $colPasajeros = $this->getPasajeros();
        $cos = $this->getCosto();
        $sumCosto = $this->getSumCosto();
        
        if($this->hayPasajesDisponible()){
            $colPasajeros[] = $objPasajero;
            $incremento = $objPasajero->darPorcentajeIncremento();
            $costoFinal = $cos + (($cos * $incremento)/100); 
        }
        $sumCosto = $sumCosto + $costoFinal;
        $this->setSumCosto($sumCosto);
        $this->setPasajeros($colPasajeros);
        return $costoFinal;
    }

    public function hayPasajesDisponible(){
        $cantMax = $this->getCantMaximaPasajeros();
        $colPasajeros = $this->getPasajeros();
        $longitud = count($colPasajeros);
        if($longitud < $cantMax){
            $hayPasajes = true;
        } else { 
            $hayPasajes = false;
        }
        return $hayPasajes;
    }

    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($codviaje){
		$base=new BaseDatos();
		$consultaViaje="Select * from viaje where idviaje=".$codviaje;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){
				if($row2=$base->Registro()){
				    $this->setCodViaje($codviaje);
				    $this->setDestino($row2['vdestino']);
					$this->setCantMaximaPasajeros($row2['vcantmaxpasajeros']);
					$this->setCosto($row2['vimporte']);
					$this->setEmail($row2['email']);
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

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO viaje(idviaje, vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado,vimporte)
				VALUES (".$this->getCodViaje().",'".$this->getDestino()."','".$this->getCantMaximaPasajeros()."',". $this->getEmpresa()->getIdEmpresa(). ",'".$this->getResponsable()->getNumEmpleado()."','".$this->getCosto()."')";
		
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
        $mensaje = $this->mostrarPasajeros();
        return "Código de viaje: " . $this->getCodViaje() . "\n".  
        "Destino: " . $this->getDestino() . "\n" .
        "Cantidad máxima de pasajeros: " . $this->getCantMaximaPasajeros() . "\n".
        "Costo: " . $this->getCosto() . "\n" . 
        "Suma de costos: " . $this->getSumCosto() . "\n" . 
        "Empresa: " . $this->getIdEmpresa() . "\n" .
        "Responsable: " . "\n" .$this->getResponsable() . "\n" . 
        $mensaje;
    }   
}
?>