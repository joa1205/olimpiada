<?php
require 'vendor/autoload.php'; // TCPDF y PHPMailer
include 'conexion.php'; // conexión a la base de datos

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ID de compra
$id_compra = 12; // Cambiar por el ID real

// 1. Obtener datos del cliente y la compra
$consultaCompra = "
SELECT c.id, c.fecha, cl.nombre_y_apellido, cl.gmail
FROM compras c
JOIN clientes cl ON c.id_usuario = cl.id_usuario
WHERE c.id = $id_compra
";
$result = mysqli_query($conexion, $consultaCompra);
$compra = mysqli_fetch_assoc($result);

if (!$compra) {
    die("❌ Compra no encontrada.");
}

// 2. Obtener detalle de productos
$detalle = mysqli_query($conexion, "
SELECT p.nombre, dc.cantidad, p.precio
FROM detalle_compra dc
JOIN productos p ON dc.id_producto = p.id
WHERE dc.id_compra = $id_compra
");

// 3. Generar PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$html = "<h1>Factura de Compra</h1>";
$html .= "<p><strong>Cliente:</strong> {$compra['nombre_y_apellido']}</p>";
$html .= "<p><strong>Email:</strong> {$compra['gmail']}</p>";
$html .= "<p><strong>ID Compra:</strong> {$compra['id']}</p>";
$html .= "<p><strong>Fecha:</strong> {$compra['fecha']}</p><br>";

$html .= "<table border='1' cellpadding='5'>
<tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>";

$total = 0;
while ($item = mysqli_fetch_assoc($detalle)) {
    $sub = $item['cantidad'] * $item['precio'];
    $total += $sub;
    $html .= "<tr>
        <td>{$item['nombre']}</td>
        <td>{$item['cantidad']}</td>
        <td>$ {$item['precio']}</td>
        <td>$ {$sub}</td>
    </tr>";
}
$html .= "<tr><td colspan='3' align='right'><strong>Total:</strong></td><td><strong>$ {$total}</strong></td></tr>";
$html .= "</table>";

$pdf->writeHTML($html, true, false, true, false, '');
$rutaPDF = __DIR__ . "/factura_{$id_compra}.pdf";
$pdf->Output($rutaPDF, 'F');

// 4. Enviar el PDF por email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tusvuelos878@gmail.com';   // CAMBIAR
    $mail->Password = 'svnbehyfevkskqsg';         // CAMBIAR
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('tuemail@gmail.com', 'Nombre de tu empresa');
    $mail->addAddress($compra['gmail'], $compra['nombre_y_apellido']);
    $mail->Subject = 'Factura de tu compra';
    $mail->isHTML(true);
    $mail->Body = "Hola {$compra['nombre_y_apellido']},<br>Gracias por tu compra. Adjuntamos tu factura en PDF.";

    $mail->addAttachment($rutaPDF);
    $mail->send();

    echo "✅ Factura enviada correctamente al correo de {$compra['nombre_y_apellido']}.";
} catch (Exception $e) {
    echo "❌ Error al enviar el email: {$mail->ErrorInfo}";
}
?>
