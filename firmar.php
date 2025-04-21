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

            if ($resultadoFirma['success']) {
                $xmlFirmado = file_get_contents($resultadoFirma['data']['xmlOrigen']);
                $response = '';
                $file = fopen($resultadoFirma['data']['xmlOrigen'], "r");
                $response = fread($file, filesize($resultadoFirma['data']['xmlOrigen']));
                fclose($file);

                $response = trim($response);
                $pos = strpos($response, '<?xml');
                if ($pos !== false) {
                    $response = substr($response, $pos);
                } else {
                    $pos = strpos($response, '<html');
                    $response = substr($response, $pos);
                    if ($response == "")
                        $response = $firmador->obtenerRespuestaXml(false, "", array("No se obtuvo respuesta de firmado"));
                    $response = $response = $firmador->obtenerRespuestaXml(false, "", array($response));
                }
                $comprobanteFirmado = $firmador->procesarRespuestaXml($xmlFirmado);

                if ($comprobanteFirmado['isValido']) {
                    $arrayDocumentoElectronico = [
                        'xml_firmado' => $comprobanteFirmado['comprobante'],
                        'estado' => 'FIRMADO',
                        'fecha_firma' => date('Y-m-d H:i:s')
                    ];
                    $sql = "UPDATE bm_estado_archivos SET archivo_firmado = ?, estado_firma = ?, fecha_firmado = ? WHERE id_empresa = ?;";
                    $parametros = [
                        $comprobanteFirmado['comprobante'],
                        'T',
                        date('Y-m-d H:i:s'),
                        $row['id_empresa']
                    ];
                    $conn->ejecutar($sql, $parametros);
                    unlink($resultadoFirma['data']['xmlOrigen']);
                    echo  json_encode([
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'XML validado y firmado correctamente',
                        'xml_firmado' => base64_encode($comprobanteFirmado['comprobante']) // o directamente el contenido
                    ]);
                } else {
                    unlink($resultadoFirma['data']['xmlOrigen']);
                    echo  json_encode([
                        'status' => 'error',
                        'code' => 500,
                        'message' => $comprobanteFirmado['mensajes'][0],
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al firmar el XML',
                    'detalle' => $resultadoFirma['message']
                ], 500);
            }
        }
    }
}
