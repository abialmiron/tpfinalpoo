<?php
/** Verifica que el dni ingresado sea de 7 u 8 dígitos.
* @param int $dni
* @return boolean $valido
*/
    function verificaFormatoDNI($dni){
        if ($dni < 99999 || $dni > 99999999){
            $valido = false;
        } else { 
            $valido = true;
        }
        return !$valido;
    }

/** Verifica que se ingrese algo y no quede vacío
*@param string $stringAMostrar
*@return $dato 
*/
    function verificaIngreso($stringAMostrar){
        do{
            echo $stringAMostrar;
            $dato = trim(fgets(STDIN));
        }while(empty($dato));
        return $dato;
    }

/** Verifica que se ingrese un número dentro de un rango especificado por el minimo y maximo (parámetros) 
*@param string $stringAMostrar
*@param int $min
*@param int $max
*@return $respuesta 
*/
function verificaIngresoNumerico($stringAMostrar, $min,$max){
    do{
        echo $stringAMostrar;
        $respuesta = trim(fgets(STDIN));
    }while(($respuesta<$min  || $respuesta>$max) || !is_numeric($respuesta));
    return $respuesta;
}
   
/** Esta función se encarga de preguntarle al usuario si quiere ingresar
*   otro pasajero y para cuando recibe una respuesta valida.
*  @return boolean $ingresoUsuario 
*/
    function pregunta(){
    
        $ciclo = true;
        do{
        echo 'Desea ingresar otro pasajero? ingrese si o no' . "\n";
        $respuesta = trim(fgets(STDIN));
        $respuesta = strtolower($respuesta);
       
        if ($respuesta== 'si' || $respuesta == 'no'){
            $ciclo = false;
            $ingresoUsuario = $respuesta== 'si' ? true : false;
        } else {
            echo 'Ingreso una respuesta incorrecta, intente nuevamente.' . "\n";
        }
        }while($ciclo);
        return $ingresoUsuario;
    }

?>