<?php
session_start();

require_once 'app/helpers/util.php';
require_once 'app/config/configDB.php';
require_once 'app/models/Cliente.php';
require_once 'app/models/AccesoDatos.php';
require_once 'app/controllers/crudclientes.php';
const filapag = 10; // Número de filas por página
const ROOTPATH = __DIR__;

$midb = AccesoDatos::getModelo();
$totalfilas = $midb->numClientes();
if ($totalfilas % filapag == 0) {
    $posfin = $totalfilas - filapag;
} else {
    $posfin = $totalfilas - $totalfilas % filapag;
}

if (!isset($_SESSION['posini'])) {
    $_SESSION['posini'] = 0;
}
$posAux = $_SESSION['posini'];

ob_start(); // La salida se guarda en el bufer
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // Proceso las ordenes de navegación
    if (isset($_GET['nav'])) {
        switch ($_GET['nav']) {
            case "Primero":
                $posAux = 0;
                break;
            case "Siguiente":
                $posAux += filapag;
                if ($posAux > $posfin) $posAux = $posfin;
                break;
            case "Anterior":
                $posAux -= filapag;
                if ($posAux < 0) $posAux = 0;
                break;
            case "Ultimo":
                $posAux = $posfin;
        }
        $_SESSION['posini'] = $posAux;
    }

    // Proceso las ordenes de navegación en detalles
    if (isset($_GET['nav-detalles']) && isset($_GET['id'])) {
        switch ($_GET['nav-detalles']) {
            case "Siguiente":
                crudDetallesSiguiente($_GET['id']);
                break;
            case "Anterior":
                crudDetallesAnterior($_GET['id']);
                break;
            case "Imprimir":
                getPDFCliente(ob_get_clean());
                crudDetalles($_GET['id']);
                break;
        }
    }
    if (isset($_GET['nav-modificar']) && isset($_GET['id'])) {
        switch ($_GET['nav-modificar']) {
            case "Siguiente":
                crudModificarSiguiente($_GET['id']);
                break;
            case "Anterior":
                crudModificarAnterior($_GET['id']);
                break;
        }
    }

    // Proceso de ordenes de CRUD clientes
    if (isset($_GET['orden'])) {
        switch ($_GET['orden']) {
            case "Nuevo":
                crudAlta();
                break;
            case "Borrar":
                crudBorrar($_GET['id']);
                break;
            case "Modificar":
                crudModificar($_GET['id']);
                break;
            case "Detalles":
                crudDetalles($_GET['id']);
                break;
            case "Terminar":
                crudTerminar();
                break;
        }
    }
} // POST Formulario de alta o de modificación
else {
    if (isset($_POST['orden'])) {
        switch ($_POST['orden']) {
            case "Nuevo":
                crudPostAlta();
                break;
            case "Modificar":
                crudPostModificar();
                break;
            case "Detalles":; // No hago nada
        }
    }
}

if (ob_get_length() == 0) {

    $posini = $_SESSION['posini'];

    if (isset($_GET['sort'])) {
        $sort = explode('.', $_GET['sort']);
        $_SESSION['sort'] = $sort;
        $tvalores = $midb->getClientes($_SESSION['posini'], filapag, $sort[0], $sort[1]);
    } else {
        if (isset($_SESSION['sort']) && $_SESSION['sort'][1]) {
            $sort = $_SESSION['sort'];
            $tvalores = $midb->getClientes($_SESSION['posini'], filapag, $sort[0], $sort[1]);
        } else {
            $tvalores = $midb->getClientes($_SESSION['posini'], filapag);
        }
    }

    require_once "app/views/list.php";
}
$contenido = ob_get_clean();

require_once "app/views/principal.php";
