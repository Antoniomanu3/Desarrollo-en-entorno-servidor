<?php
use Dompdf\Dompdf;


/*
 *  Funciones para limpiar la entrada de posibles inyecciones
 */
function limpiarEntrada(string $entrada):string{
    $salida = trim($entrada); // Elimina espacios antes y después de los datos
    $salida = strip_tags($salida); // Elimina marcas
    return $salida;
}
// Función para limpiar todos elementos de un array
function limpiarArrayEntrada(array &$entrada){
 
    foreach ($entrada as $key => $value ) {
        $entrada[$key] = limpiarEntrada($value);
    }
}

function comprobarTelefono(string $telefono): bool{
    $patron = "/^\d{3}-\d{3}-\d{4}$/";
    return preg_match($patron, $telefono);
}

function comprobarIP(string $ip): bool{
    return (bool)filter_var($ip, FILTER_VALIDATE_IP);
}



function getPDFCliente($html){
    //Use dompdf to generate a pdf file from current view
//import DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("cliente.pdf", array("Attachment" => false));


}

