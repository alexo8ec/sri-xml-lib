<?php
require __DIR__ . '/vendor/autoload.php'; // Cargar dependencias de Composer

use SRI\Conexion;
use SRI\FirmaElectronica;

$conn = new Conexion(
    'factural_F4ctur4lg0',
    'h(wa*c~6X5N,',
    'factural_f4ctvr4l60',
    '192.250.227.131'
);
firmarXMLNotaCredito($conn);
function firmarXMLNotaCredito($conn)
{
    $documentos = $conn->consulta("SELECT * FROM bm_estado_archivos WHERE estado_firma=? AND estado_sri=? AND id_empresa=?", ['F', 'F', 41]);
    if (count($documentos) > 0) {
        foreach ($documentos as $row) {
            $empresa = $conn->consultarUno("SELECT * FROM bm_entidad WHERE id_empresa = ? LIMIT 1", [$row['id_empresa']]);
            $rutaCertificado = ('tokens/' . $empresa['ruta_token']);
            $claveCertificado = $empresa['clave_token'];
            $firmador = new FirmaElectronica($rutaCertificado, $claveCertificado);
            $xmlBaseDatos = $row['archivo_generado'];
            $rutaXmlOrigen = 'temp/' . uniqid('xml_') . '.xml';
            $rutaXmlFirmado = 'temp/' . uniqid('xml_firmado_') . '.xml';
            file_put_contents($rutaXmlOrigen, $xmlBaseDatos);
            $resultadoFirma = $firmador->firmarXml($rutaXmlOrigen, $rutaXmlFirmado);

            echo '<pre>';
            print_r($resultadoFirma);
            exit;
        }
    }
}
