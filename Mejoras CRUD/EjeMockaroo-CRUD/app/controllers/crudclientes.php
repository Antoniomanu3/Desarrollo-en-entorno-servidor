<?php

function crudBorrar($id)
{
    $db = AccesoDatos::getModelo();
    $tuser = $db->borrarCliente($id);
}

function crudTerminar()
{
    AccesoDatos::closeModelo();
    session_destroy();
}

function crudAlta()
{
    $cli = new Cliente();
    $orden = "Nuevo";
    include_once "app/views/formulario.php";
}

function crudDetalles($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    include_once "app/views/detalles.php";
}

function crudDetallesSiguiente($id)
{
    $db = AccesoDatos::getModelo();
    if ($cli = $db->getClienteSiguiente($id)) {
        include_once "app/views/detalles.php";
    } else {
        crudDetalles($id);
    }

    $cli = $db->getClienteSiguiente($id);
    include_once "app/views/detalles.php";
}

function crudDetallesAnterior($id)
{

    $db = AccesoDatos::getModelo();
    if ($cli = $db->getClienteAnterior($id)) {
        include_once "app/views/detalles.php";
    } else {
        crudDetalles($id);
    }
    $cli = $db->getClienteAnterior($id);
    include_once "app/views/detalles.php";
}

function crudModificarSiguiente($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteSiguiente($id);
    include_once "app/views/formulario.php";
}

function crudModificarAnterior($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteAnterior($id);
    include_once "app/views/formulario.php";
}

function crudModificar($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    $orden = "Modificar";
    include_once "app/views/formulario.php";
}

function crudPostAlta()
{
    $db = AccesoDatos::getModelo();

    limpiarArrayEntrada($_POST); //Evito la posible inyección de código


    $cli = new Cliente();
    $cli->id = $_POST['id'];
    $cli->first_name = $_POST['first_name'];
    $cli->last_name = $_POST['last_name'];
    $cli->email = $_POST['email'];
    $cli->gender = $_POST['gender'];
    $cli->ip_address = $_POST['ip_address'];
    $cli->telefono = $_POST['telefono'];

    $emailExiste = $db->existeEmail($_POST['email']);
    $ipOK = comprobarIP($_POST['ip_address']);
    $telefonoOK = comprobarTelefono($_POST['telefono']);


    if ($emailExiste || !$ipOK || !$telefonoOK) {
        $orden = 'Nuevo';
        $error = 'Error en el formulario. ';
        if ($emailExiste) {
            $error .= "El email ya existe. ";
        }
        if (!$ipOK) {
            $error .= "La IP no es correcta. ";
        }
        if (!$telefonoOK) {
            $error .= "El telefono no es correcto. ";
        };
        include_once "app/views/formulario.php";
    } else {
        $db->addCliente($cli);

    }

}

function crudPostModificar()
{
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código
    $cli = new Cliente();

    $cli->id = $_POST['id'];
    $cli->first_name = $_POST['first_name'];
    $cli->last_name = $_POST['last_name'];
    $cli->email = $_POST['email'];
    $cli->gender = $_POST['gender'];
    $cli->ip_address = $_POST['ip_address'];
    $cli->telefono = $_POST['telefono'];
    $db = AccesoDatos::getModelo();
    $db->modCliente($cli);

}
