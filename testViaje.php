<?php

include_once "Viaje.php";
include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "ResponsableV.php";
include_once "Pasajero.php";
include_once "funcionesGenerales.php";

function menu_principal(){

    $mensaje = '---------- MENU PRINCIPAL ----------' . "\n";
    $mensaje .="1) Ingresar un nuevo viaje \n";
    $mensaje .= "2) Listar todos los viajes \n";
    $mensaje .= "3) Modificar algún viaje \n";
    $mensaje .= "4) Eliminar algún viaje \n";
    $mensaje .= "5) Agregar a un pasajero nuevo \n";
    $mensaje .= "6) Modificar algún pasajero \n";
    $mensaje .= "7) Eliminar algún pasajero \n";
    $mensaje .= "8) Modificar algún responsable de viajes \n";
    $mensaje .= "9) Eliminar algún responsable de viajes \n";

    $ingresoUsuario = verificaIngresoNumerico($mensaje, 1,9);

    return $ingresoUsuario;
}

function ingresa_empresa(){
    $mensaje = "Desea trabajar con una empresa ya cargada o quiere ingresar una nueva?";
    $mensaje .= "\n 1) Ya cargada";
    $mensaje .= "\n 2) Ingresar nueva \n";

    $ingresoUsuario = verificaIngresoNumerico($mensaje, 1,2);

    switch($ingresoUsuario){
        case 1:
            $colEmpresas = Empresa::listar("");
            $arregloID = array();
            echo " ******* EMPRESAS YA CARGADAS ********* \n";

            for($i = 0;$i < count($colEmpresas);$i++){
                $arregloID[] = $colEmpresas[$i]->getIdEmpresa();
                echo $colEmpresas[$i];
            }

            $idEmpresa = verificaIngreso("Ingrese el ID de la empresa con la que quiere trabajar: \n ");
            //if($arregloID)
            $empresaSeleccionada = new Empresa();
            $empresaSeleccionada->buscar($idEmpresa);
            break;
        case 2:
            $nombre = verificaIngreso("Ingrese el nombre de la empresa nueva: \n");
            $direccion = verificaIngreso("Ingrese la dirección:\n ");

            $empresaSeleccionada = new Empresa();
            $empresaSeleccionada->setNombre($nombre);
            $empresaSeleccionada->setDireccion($direccion);
            $empresaSeleccionada->insertar();
            break;
    } 
    return $empresaSeleccionada;


}

function cargaViaje(){
    $des = verificaIngreso("Ingrese el destino:\n");
    $cantMaxPasajeros = verificaIngreso("Ingrese la cantidad máxima de pasajeros:\n");
    $costo  = verificaIngreso("Ingrese el costo del viaje: \n");
    $objResponsable = cargaResponsable();

    $objViaje = new Viaje();
    $objViaje->cargar(0,$des,$cantMaxPasajeros,[],$objResponsable,$costo,$empresa);
    
}

function cargaResponsable(){
    $numLic = verificaIngreso("Ingrese el número de licencia del responsable: \n");
    $nombreR = verificaIngreso("Ingrese el nombre del responsable: \n");
    $apellidoR = verificaIngreso("Ingrese el apellido del responsable: \n");
    
    $objResponsable = new ResponsableV();
    $objResponsable->cargar(0,$numLic,$nombreR,$apellidoR);
    $objResponsable->insertar();

    return $objResponsable;
}

/** Esta función se encarga de realizar la carga inicial de un viaje.
 */
function cargaPasajerosInicial(){
    $colPasajeros = array();
    do{
        $dni = verificaDNI($colPasajeros);
        $nombre = verificaIngreso("Ingrese el nombre del nuevo pasajero: \n");
        $apellido = verificaIngreso("Ingrese el apellido: \n");
        $telefono = verificaIngreso("Ingrese el telefono: \n");

        $objPasajero = new Pasajero();
        $objPasajero->cargar($dni,$nombre,$apellido,$idViaje, $telefono);

        $respuesta = pregunta();
    }while($respuesta);
}

/** MENU PRINCIPAL */

$objEmpresa = ingresa_empresa();

$ingresoUsuario = menu_principal();

switch($ingresoUsuario){
    case 1: 
        echo "Bienvenido a la carga de un nuevo viaje \n";
        cargaViaje();
    break;
}

/*$empresa = new Empresa(); 
$empresa->cargar(1,'prueba','av argentina 2', []);
$responsable = new ResponsableV();
$responsable->cargar(1, 2, 'Silvia', 'Ortiz');
$viaje = new Viaje();
$viaje->cargar(1,'paraguay', 32, [] ,$responsable,2292,$empresa);

$respuesta = $viaje->insertar();
echo $respuesta;
if ($respuesta != null) {
    echo "\nOP INSERCION;  El Viaje fue ingresada en la BD";
}else 
echo $viaje->getMensajeOperacion();*/

?>