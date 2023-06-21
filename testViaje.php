<?php

include_once "Viaje.php";
include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "ResponsableV.php";
include_once "Pasajero.php";
include_once "funcionesGenerales.php";

function eliminarBD(){
    $objPasajero = new Pasajero();
    $objViaje = new Viaje();
    $objEmpresa = new Empresa();
    $objResponsable = new ResponsableV();
    //borro tabla pasajeros
    $tablaPasajeros = $objPasajero->listar("");
    for ($i=0;$i<count($tablaPasajeros);$i++) {
        $tablaPasajeros[$i]->eliminar();
    }

    //borro tabla viaje
    $tablaViajes = $objViaje->listar("");
    for ($i=0;$i<count($tablaViajes);$i++) {
        $tablaViajes[$i]->eliminar();
    }

    //borro tabla responsablev
    $tablaResponsableV = $objResponsable->listar("");
    for ($i=0;$i<count($tablaResponsableV);$i++) {
        $tablaResponsableV[$i]->eliminar();
    }

    //borro tabla empresa
    $tablaEmpresas = $objEmpresa->listar("");
    for ($i=0;$i<count($tablaEmpresas);$i++) {
        $tablaEmpresas[$i]->eliminar();
    }

    
}

 function DatosDePrueba() {
    // Para tener mas variedad, se precargan los siguientes datos: 
    $objEmpresa1 = new Empresa();
    $objEmpresa1->cargar(0, "Empresa prueba", "Av Argentina 200");
    $objEmpresa1->insertar();

    $objEmpresa2 = new Empresa();
    $objEmpresa2->cargar(0, "Otra Empresa", "Roca 1291");
    $objEmpresa2->insertar();

    $objResponsable1 = new ResponsableV();
    $objResponsable1->cargar(0, 8272819, "Silvia", "Ortiz");
    $objResponsable1->insertar();

    $objResponsable2 = new ResponsableV();
    $objResponsable2->cargar(0, 3424324, "Juan", "Almiron");
    $objResponsable2->insertar();

    $objViaje1 = new Viaje();
    $objViaje1->cargar(0, "Paraguay", 25, [],$objResponsable1,4568,$objEmpresa1);
    $objViaje1->insertar();

    $objViaje2 = new Viaje();
    $objViaje2->cargar(0, "Bariloche", 100,[],$objResponsable2,5600,$objEmpresa1);
    $objViaje2->insertar();

    $objPasajero1 = new Pasajero();
    $objPasajero1->cargar(42605438, "Abigail", "Almiron", $objViaje1, 2994555138);
    $objPasajero1->insertar();

    $objPasajero2 = new Pasajero();
    $objPasajero2->cargar(42602323, "Camila", "Almiron", $objViaje1, 299837483);
    $objPasajero2->insertar();

    $objPasajero3 = new Pasajero();
    $objPasajero3->cargar(45234233, "Davor", "Kissner", $objViaje2, 2997387281);
    $objPasajero3->insertar();
}

function menu_principal(){

    $mensaje = '---------- MENU PRINCIPAL ----------' . "\n";
    $mensaje .= "1) Ingresar un nuevo viaje \n";
    $mensaje .= "2) Listar todos los viajes \n";
    $mensaje .= "3) Modificar algún viaje \n";
    $mensaje .= "4) Eliminar algún viaje \n";
    $mensaje .= "5) Agregar a un pasajero nuevo \n";
    $mensaje .= "6) Modificar algún pasajero \n";
    $mensaje .= "7) Eliminar algún pasajero \n";
    $mensaje .= "8) Modificar algún responsable de viajes \n";
    $mensaje .= "9) Eliminar algún responsable de viajes \n";
    $mensaje .= "10) Modificar una empresa \n";
    $mensaje .= "11) Eliminar una empresa \n";
    $mensaje .= "0) Cerrar el programa \n";

    $ingresoUsuario = verificaIngresoNumerico($mensaje, 0,11);

    return $ingresoUsuario;
}

function ingresa_empresa(){
    $mensaje = "Desea seleccionar una empresa ya cargada o quiere ingresar una nueva?";
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
            do{
            $idEmpresa = verificaIngresoInt("Ingrese el ID de la empresa con la que quiere trabajar: \n ");
            $verificaID = array_search($idEmpresa,$arregloID) === false;
            }while($verificaID);

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

function cargaViaje($objEmpresa){
    $des = verificaIngreso("Ingrese el destino:\n");
    $cantMaxPasajeros = verificaIngresoInt("Ingrese la cantidad máxima de pasajeros:\n");
    $costo  = verificaIngresoInt("Ingrese el costo del viaje: \n");
    $objResponsable = cargaResponsable();

    $objViaje = new Viaje();
    $objViaje->cargar(0,$des,$cantMaxPasajeros,[],$objResponsable,$costo,$objEmpresa);
    $objViaje->insertar();

    $colPasajeros = cargaPasajerosInicial($objViaje);
    $objViaje->setPasajeros($colPasajeros);
    
}

function cargaResponsable(){

    $mensaje = "Desea seleccionar un responsable ya cargado o quiere ingresar uno nuevo? \n";
    $mensaje .= "\n 1) Ya cargado";
    $mensaje .= "\n 2) Ingresar nuevo \n";

    $ingresoUsuario = verificaIngresoNumerico($mensaje, 1,2);

    if($ingresoUsuario == 1){
        $objResponsable = new ResponsableV();
        $colResponsable = $objResponsable->listar("");
        $numEmpleadoArr = array();
        if(!empty($colResponsable)){
            for($i=0;$i<count($colResponsable);$i++){
                $numEmpleadoArr[] = $colResponsable[$i]->getNumEmpleado();
                echo $colResponsable[$i];
            }

            do{
                $numEmpleado = verificaIngresoInt("Ingrese el num empleado del responsable que desea asignar: \n ");
                $verificaID = array_search($numEmpleado,$numEmpleadoArr) === false;
            }while($verificaID);
            
            $objResponsable->buscar($numEmpleado);

        }else{
            echo "No hay responsables cargados";
        }
    } else {
        $numLic = verificaIngresoInt("Ingrese el número de licencia del responsable: \n");
        $nombreR = verificaIngreso("Ingrese el nombre del responsable: \n");
        $apellidoR = verificaIngreso("Ingrese el apellido del responsable: \n");
        
        $objResponsable = new ResponsableV();
        $objResponsable->cargar(0,$numLic,$nombreR,$apellidoR);
        $objResponsable->insertar();
    }
    return $objResponsable;
}

/** Esta función se encarga de realizar la carga inicial de un viaje.
 */
function cargaPasajerosInicial($objViaje){
    $colPasajeros = array();
    $contador = 0;
    do{
        $contador += 1;
        $dni = verificaDNI($colPasajeros);
        $nombre = verificaIngreso("Ingrese el nombre del nuevo pasajero: \n");
        $apellido = verificaIngreso("Ingrese el apellido: \n");
        $telefono = verificaIngresoInt("Ingrese el telefono: \n");

        $objPasajero = new Pasajero();
        $objPasajero->cargar($dni,$nombre,$apellido,$objViaje, $telefono);
        $objPasajero->insertar();

        array_push($colPasajeros,$objPasajero);
        $objViaje->venderPasaje();
        $respuesta = pregunta();
    }while($respuesta && $contador < $objViaje->getCantMaximaPasajeros());
    return $colPasajeros;
}

function cargaPasajerosYaIniciada($objViaje){
    $colPasajeros = $objViaje->traePasajeros();
    if(empty($colPasajeros)){
        $colPasajeros = array();
    }
    $contador = 0;
    do{
        $contador += 1;
        $dni = verificaDNI($colPasajeros);
        $nombre = verificaIngreso("Ingrese el nombre del nuevo pasajero: \n");
        $apellido = verificaIngreso("Ingrese el apellido: \n");
        $telefono = verificaIngresoInt("Ingrese el telefono: \n");

        $objPasajero = new Pasajero();
        $objPasajero->cargar($dni,$nombre,$apellido,$objViaje, $telefono);
        $objPasajero->insertar();

        array_push($colPasajeros,$objPasajero);
        $objViaje->venderPasaje();
        $respuesta = pregunta();
    }while($respuesta && $contador < $objViaje->getCantMaximaPasajeros());
    return $colPasajeros;
}

function listaViajes($objEmpresa){
    $objViaje = new Viaje();
    $colViajes = $objViaje->listar("idempresa = ".$objEmpresa->getIdEmpresa());
    $arrCodViajes = array();
    for($i = 0;$i < count($colViajes);$i++){
        $arrCodViajes[] = $colViajes[$i]->getCodViaje();
        $objViaje = $colViajes[$i];
        $objViaje->traePasajeros();
        echo $objViaje;
    }
    return $arrCodViajes;
}

function listaResponsables($objEmpresa){
    $idEmpresa = $objEmpresa->getIdEmpresa();
    $objResponsable = new ResponsableV();
    $colResponsables = $objResponsable->listar("rnumeroempleado IN (SELECT rnumeroempleado FROM viaje WHERE idempresa = ". $idEmpresa .")");
    $respArray = array();
    for($i = 0;$i < count($colResponsables);$i++){
        $respArray[] = $colResponsables[$i]->getNumEmpleado();
        echo $colResponsables[$i];
    }
    return $respArray;
}

function listaPasajeros($objEmpresa){
    $PasajArray = array();
    $objPasajero = new Pasajero();
    $idEmpresa = $objEmpresa->getIdEmpresa();
    $colPasajeros = $objPasajero->listar("idviaje IN (SELECT idviaje FROM viaje WHERE idempresa = ".$idEmpresa .")");
    for($i = 0;$i < count($colPasajeros);$i++){
        echo $colPasajeros[$i];
    }
    return $colPasajeros;
}

function modificarViaje($objEmpresa){
    $arrCodViajes = listaViajes($objEmpresa);

    do{
        $codViaje = verificaIngresoInt("Ingrese el codViaje del viaje con el que quiere trabajar: \n ");
        $verificaID = array_search($codViaje,$arrCodViajes) === false;
    }while($verificaID);

    $objViaje = new Viaje();
    $objViaje->buscar($codViaje);

    $mensaje = "¿Qué desea modificar del viaje? \n";
    $mensaje .= "1) Destino \n";
    $mensaje .= "2) Cantidad Máxima de pasajeros \n";
    $mensaje .= "3) Asignar otro responsable al viaje \n";
    $mensaje .= "4) Cambiar la empresa \n";
    $mensaje .= "5) Costo \n";

    $modificacion = verificaIngresoNumerico($mensaje, 1,5);

    switch($modificacion){
        case 1: 
            $destinoNuevo = verificaIngreso("Ingrese el nuevo destino: \n");
            $objViaje->setDestino($destinoNuevo);
            $objViaje->modificar();
            break;
        case 2:
            $cantMaxNueva = verificaIngresoInt("Ingrese la cantidad máxima de pasajeros nueva: \n");
            $objViaje->setCantMaximaPasajeros($cantMaxNueva);
            $objViaje->modificar();
            break;
        case 3:
            $respuestaUsuario = verificaIngresoNumerico("¿Desea seleccionar un responsable ya cargado o uno nuevo? \n 1) Ya cargado \n 2) Crear nuevo \n",1,2);
            if($respuestaUsuario == 1){
            echo "Lista de responsables cargados: ";
            $respArray = listaResponsables($objEmpresa);
            do{
                $numEmpleadoNuevo = verificaIngresoInt("Ingrese el Num empleado del responsable que desea asignar al viaje: \n ");
                $verificaID = array_search($numEmpleadoNuevo,$respArray) === false;
            }while($verificaID);
            $objResponsable = new Responsable();
            $objResponsable->buscar($numEmpleadoNuevo);
            $objViaje->setResponsable($objResponsable);
            $objResponsable->modificar();
            } else {
                $objResponsable = cargaResponsable();
                $objViaje->setResponsable($objResponsable);
                $objResponsable->modificar();
            }
            break;
        case 4: 
            $nuevaEmpresa = ingresa_empresa();
            $objViaje->setEmpresa($nuevaEmpresa);
            $objViaje->modificar();
        break;
        case 5:
            $nuevoCosto = verificaIngresoInt("Ingrese el nuevo costo: \n");
            $objViaje->setCosto($nuevoCosto);
            $objViaje->modificar();
        break;
    }

}

function eliminaViaje($codViaje){
    $objViaje = new Viaje();
    $objViaje->buscar($codViaje);
    $objViaje->traerPasajeros();
    $colPasajeros = $objViaje->getPasajeros();

    for($i=0;$i<count($colPasajeros);$i++){
        $objPasajero = $colPasajeros[$i];
        $objPasajero->eliminar();
    }

    $objViaje->eliminar();
}

function modificaPasajeros($dniAModificar, $objEmpresa){
    $objPasajero = new Pasajero();
    $objPasajero->buscar($dniAModificar);
    $mensaje = "¿Qué desea modificar del pasajero? \n";
    $mensaje .= "1) Nombre \n";
    $mensaje .= "2) Apellido \n";
    $mensaje .= "3) Telefono \n";
    $mensaje .= "4) Asignarlo a otro viaje \n";

    $modificacion = verificaIngresoNumerico($mensaje, 1,4);

    switch($modificacion){
        case 1: 
            $nuevoNombre = verificaIngreso("Ingrese el nuevo nombre del pasajero: \n");
            $objPasajero->setNombre($nuevoNombre);
            $objPasajero->modificar();
        break;
        case 2: 
            $nuevoApellido = verificaIngreso("Ingrese el nuevo apellido del pasajero: \n");
            $objPasajero->setApellido($nuevoApellido);
            $objPasajero->modificar();
        break;
        case 3:
            $nuevoTelefono = verificaIngresoInt("Ingrese el nuevo telefono del pasajero: \n");
            $objPasajero->setTelefono($nuevoTelefono);
            $objPasajero->modificar();
        break;
        case 4:
            $arrViajesCod = listarViajes($objEmpresa);
            do{
                $codViaje = verificaIngreso("Ingrese el codViaje del viaje que quiere asignarle al pasajero: \n ");
                $verificaID = array_search($codViaje,$arrCodViajes) === false;
            }while($verificaID);
            $objViaje = new Viaje();
            $objViaje->buscar($codViaje);

            $objPasajero->setObjViaje($objViaje);
            $objPasajero->modificar();
        break;
    }
}

function modificaResponsable($objResponsable){
    $mensaje = "¿Qué desea modificar del responsable? \n";
    $mensaje .= "1) Número de licencia \n";
    $mensaje .= "2) Nombre \n";
    $mensaje .= "3) Apellido \n";

    $modificacion = verificaIngresoNumerico($mensaje, 1,3);

    switch($modificacion){
        case 1: 
            $nuevoNumLic = verificaIngresoInt("Ingrese el nuevo número de licencia: \n");
            $objResponsable->setNumLicencia($nuevoNumLic);
            $objResponsable->modificar();
        break;
        case 2:
            $nuevoNom = verificaIngreso("Ingrese el nuevo nombre del responsable: \n");
            $objResponsable->setNombre($nuevoNom);
            $objResponsable->modificar();
        break;
        case 3:
            $nuevoApellido = verificaIngreso("Ingrese el nuevo apellido del responsable: \n");
            $objResponsable->setApellido($nuevoApellido);
            $objResponsable->modificar();
        break;
    }
}

function eliminaResponsable($objResponsable){
    $numEmpleado = $objResponsable->getNumEmpleado();
    $objViaje = new Viaje();
    $colViajes = $objViaje->listar("rnumeroempleado = " . $numEmpleado);
    if(!empty($colViajes)){
        echo "No se puede eliminar el registro, un/unos viaje/s se encuentra/n utilizandolo \n";
    } else {
        $objResponsable->eliminar();
    }
}

function listarEmpresas(){
    $objEmpresa = new Empresa();
    $colEmpresas = $objEmpresa->listar();
    $idEmpresasArr = array();
    for($i=0;$i<count($colEmpresas);$i++){
        $idEmpresasArr[] = $colEmpresas[$i]->getIdEmpresa();
        echo $colEmpresas[$i];
    }
    return $idEmpresasArr;
}

function modificaEmpresa($idEmpresaMod){
    $objEmpresa = new Empresa();
    $objEmpresa->buscar($idEmpresaMod);

    $mensaje = "¿Qué desea modificar de la empresa? \n";
    $mensaje .= "1) Nombre \n";
    $mensaje .= "2) Direccion \n";

    $modificacion = verificaIngresoNumerico($mensaje, 1,2);

    if($modificacion == 1){
        $nuevoNombre = verificaIngreso("Ingrese el nuevo nombre de la empresa: \n");
        $objEmpresa->setNombre($nuevoNombre);
        $objEmpresa->modificar();
    }else {
        $nuevaDireccion = verificaIngreso("Ingrese la nueva direccion de la empresa: \n");
        $objEmpresa->setDireccion($nuevaDireccion);
        $objEmpresa->modificar();
    }
    return $objEmpresa;
}

function eliminaEmpresa($idEmpresaElim){
    $objViaje = new Viaje();
    $colViajes = $objViaje->listar("idempresa = ".$idEmpresaElim);
    if(!empty($colViajes)){
        echo "No puede eliminar la empresa, un/unos viaje/s se encuentra/n utilizandola";
    } else {
        $objEmpresa = new Empresa();
        $objEmpresa->buscar($idEmpresaElim);
        $objEmpresa->eliminar();
        echo "Se eliminó la empresa correctamente \n";
    }
}

/** MENU PRINCIPAL */

eliminarBD();
DatosDePrueba();

$objEmpresa = ingresa_empresa();
do{

    $ingresoUsuario = menu_principal();

    switch($ingresoUsuario){
        case 1: 
            echo "Bienvenido a la carga de un nuevo viaje \n";
            cargaViaje($objEmpresa);
        break;
        case 2: 
            echo "Se listan todos los viajes creados: \n";
            $arrCodViajes = listaViajes($objEmpresa);
        break;
        case 3:
            echo "Se muestran los viajes almacenados: \n";
            modificarViaje($objEmpresa);
        break;
        case 4:
            echo "Se muestran los viajes almacenados: \n";
            $arrCodViajes = listaViajes($objEmpresa);
            do{
                $codViaje = verificaIngresoInt("Ingrese el codViaje del viaje que desea eliminar: \n ");
                $verificaID = array_search($codViaje,$arrCodViajes) === false;
            }while($verificaID);

            eliminaViaje($codViaje);
        break;
        case 5:
            echo "Se muestran los viajes almacenados: \n";
            $arrCodViajes = listaViajes($objEmpresa);
            do{
                $codViaje = verificaIngresoInt("Ingrese el codViaje del viaje al que desea agregar el pasajero: \n ");
                $verificaID = array_search($codViaje,$arrCodViajes) === false;
            }while($verificaID);

            $objViaje = new Viaje();
            $objViaje->buscar($codViaje);

            $colPasajeros = cargaPasajerosYaIniciada($objViaje);
        break;
        case 6: 
            echo '*** Modificacion de pasajero ***' . "\n";
            $colPasajeros = listaPasajeros($objEmpresa);
            $dniAModificar = verificaDNIYaIngresado($colPasajeros);

            modificaPasajeros($dniAModificar, $objEmpresa);
        break;
        case 7:
            echo '*** Eliminacion de pasajero ***'. "\n";
            $colPasajeros = listaPasajeros($objEmpresa);
            $dniAEliminar = verificaDNIYaIngresado($colPasajeros);
            $objPasajero = new Pasajero();
            $objPasajero->buscar($dniAEliminar);
            $objPasajero->eliminar();
        break;
        case 8:
            $colNumEmpleadoRes = listaResponsables($objEmpresa);
            do{
                $numEmpleado = verificaIngresoInt("Ingrese el Num empleado del responsable al que desea modificar: \n ");
                $verificaID = array_search($numEmpleado,$colNumEmpleadoRes) === false;
            }while($verificaID);

            $objResponsable = new ResponsableV();
            $objResponsable->buscar($numEmpleado);

            modificaResponsable($objResponsable);
        break;
        case 9:
            $colNumEmpleadoRes = listaResponsables($objEmpresa);
            do{
                $numEmpleado = verificaIngresoInt("Ingrese el Num empleado del responsable al que desea eliminar: \n ");
                $verificaID = array_search($numEmpleado,$colNumEmpleadoRes) === false;
            }while($verificaID);

            $objResponsable = new ResponsableV();
            $objResponsable->buscar($numEmpleado);
            eliminaResponsable($objResponsable);
        break;
        case 10:
            $idEmpresasArr = listarEmpresas();
            do{
                $idEmpresaMod = verificaIngresoInt("Ingrese el Id de la empresa que desea modificar: \n ");
                $verificaID = array_search($idEmpresaMod,$idEmpresasArr) === false;
            }while($verificaID);

            if($idEmpresaMod == $objEmpresa->getIdEmpresa()){
                $objEmpresa = modificaEmpresa($idEmpresaMod);
            } else {
                modificaEmpresa($idEmpresaMod);
            }
        break;
        case 11:
            $idEmpresasArr = listarEmpresas();
            do{
                $idEmpresaElim = verificaIngresoInt("Ingrese el Id de la empresa que desea eliminar: \n ");
                $verificaID = array_search($idEmpresaElim,$idEmpresasArr) === false;
            }while($verificaID);

            if($idEmpresaElim == $objEmpresa->getIdEmpresa()){
                echo "No puede eliminar la empresa que está utilizando";
            } else {
                eliminaEmpresa($idEmpresaElim);
            }
        break;
    }

}while($ingresoUsuario <> 0);

?>