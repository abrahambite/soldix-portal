<?php
// --- CONFIGURACIÓN ---
// Sustituye esta dirección con el correo donde quieres recibir los tickets.
$destinatario = "ihhimedia@gmail.com";
// -------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario de forma segura
    $tipo_solicitud = htmlspecialchars($_POST['tipo_solicitud'] ?? 'No especificado');
    $fuente = htmlspecialchars($_POST['fuente'] ?? 'No especificada');
    $folio = htmlspecialchars($_POST['folio_cotizacion'] ?? 'No especificado');
    $nombre = htmlspecialchars($_POST['nombre'] ?? 'No especificado');
    $empresa = htmlspecialchars($_POST['empresa'] ?? 'No especificada');
    $email = htmlspecialchars($_POST['email'] ?? 'No especificado');
    $telefono = htmlspecialchars($_POST['telefono'] ?? 'No proporcionado');
    $mensaje = htmlspecialchars($_POST['mensaje'] ?? 'Sin mensaje.');

    // Validar que los campos requeridos no estén vacíos
    if (empty($nombre) || empty($empresa) || empty($email)) {
        http_response_code(400);
        echo "Error: Faltan campos requeridos.";
        exit;
    }
    
    // Asunto del correo (El Ticket) - Lo hace más específico
    $asunto_ticket = strtoupper(str_replace(' ', '_', $tipo_solicitud));
    $asunto = "[{$asunto_ticket} - {$folio}] - {$empresa}";

    // Cuerpo del correo en formato HTML para que se vea profesional
    $cuerpo = "
    <html>
    <head>
      <title>{$asunto}</title>
    </head>
    <body style='font-family: Arial, sans-serif; color: #333;'>
      <h2 style='color: #FF4500;'>Nuevo Ticket de Seguimiento: {$tipo_solicitud}</h2>
      <p>Se ha recibido una nueva solicitud desde el portal de la cotización <b>{$folio}</b>.</p>
      <hr>
      <h3>Detalles del Prospecto:</h3>
      <ul>
        <li><strong>Nombre:</strong> {$nombre}</li>
        <li><strong>Empresa:</strong> {$empresa}</li>
        <li><strong>Email:</strong> {$email}</li>
        <li><strong>Teléfono:</strong> {$telefono}</li>
      </ul>
      <h3>Detalles de la Solicitud:</h3>
      <ul>
        <li><strong>Tipo:</strong> {$tipo_solicitud}</li>
        <li><strong>Fuente:</strong> {$fuente}</li>
      </ul>
      <h3>Mensaje del Cliente:</h3>
      <p style='background-color: #f5f5f5; padding: 10px; border-radius: 5px;'>
        " . nl2br($mensaje) . "
      </p>
      <hr>
      <p style='font-size: 12px; color: #888;'>Este correo fue generado automáticamente desde el portal de cotizaciones.</p>
    </body>
    </html>
    ";

    // Cabeceras para enviar el correo en formato HTML
    $cabeceras = "MIME-Version: 1.0" . "\r\n";
    $cabeceras .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // De: La dirección del remitente. Puede ser una que no exista.
    $cabeceras .= 'From: <tickets@soldix-crm.com>' . "\r\n";

    // Enviar el correo
    if (mail($destinatario, $asunto, $cuerpo, $cabeceras)) {
        // Mensaje de éxito para el usuario
        echo "<script>
                alert('¡Gracias! Tu solicitud ha sido enviada. Te contactaremos a la brevedad.');
                window.history.back();
              </script>";
    } else {
        // Mensaje de error si falla
        echo "<script>
                alert('Error. No se pudo enviar tu solicitud. Por favor, contacta directamente al emisor de la cotización.');
                window.history.back();
              </script>";
    }

} else {
    // No permitir acceso directo al script
    http_response_code(403);
    echo "Acceso denegado.";
}
?>