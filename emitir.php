<?php
require __DIR__ . '/vendor/autoload.php'; // Cargar dependencias de Composer

use SRI\Conexion;

$conn = new Conexion(
    'factural_F4ctur4lg0',
    'h(wa*c~6X5N,',
    'factural_f4ctvr4l60',
    '192.250.227.131'
);
emitirXMLNotaCredito($conn);
function emitirXMLNotaCredito($conn)
{
    $documentos = $conn->consulta("SELECT * FROM bm_estado_archivos WHERE estado_firma=? AND estado_sri=? AND id_empresa=?", ['T', 'F', 41]);
    if (count($documentos) > 0) {
        foreach ($documentos as $row) {
            $empresa = $conn->consultarUno("SELECT * FROM bm_entidad WHERE id_empresa = ? LIMIT 1", [$row['id_empresa']]);
            $xmlFirmado = $row['archivo_firmado'];

            $doc = new DOMDocument();
            $doc->loadXML($xmlFirmado);
            file_put_contents('temp/debug.xml', $doc->saveXML());

            $xml = new DOMDocument();
            $xml->loadXml($xmlFirmado);
            libxml_use_internal_errors(true);
            if ($xml->schemaValidate('xsd/NotaCredito_V1.1.0.xsd')) {
                echo "✅ XML válido contra el XSD.<br/>";
            } else {
                echo "❌ El XML no es válido.\n";
                foreach (libxml_get_errors() as $error) {
                    echo "[Línea {$error->line}] {$error->message}";
                }
                libxml_clear_errors();
                exit;
            }
            if ($empresa['id_tipo_ambiente'] == 2) {
                $wsdl = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl'; //Ambiente producción
            } else {
                $wsdl = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl'; //Ambiente pruebas
            }
            $client = new SoapClient($wsdl, [
                'trace' => 1,
                'exceptions' => true,
            ]);

            $params = [
                'xml' => $xmlFirmado
            ];

            $response = $client->__soapCall('validarComprobante', [$params]);
            echo "🛰️ XML Enviado:\n" . $client->__getLastRequest() . "\n";
            echo "📥 XML Respuesta:\n" . $client->__getLastResponse() . "\n";

            echo '<pre>';
            print_r($response);

            $claveAcceso = $row['claveAcceso']; // Tu clave de acceso exacta
            if ($empresa['id_tipo_ambiente'] == 2) {
                $wsdl = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl'; //Ambiente producción
            } else {
                $wsdl = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl'; //Ambiente pruebas
            }
            $client = new SoapClient($wsdl, [
                'trace' => true,
                'exceptions' => true,
            ]);

            $response = $client->__soapCall('autorizacionComprobante', [
                ['claveAccesoComprobante' => $claveAcceso]
            ]);

            echo "🛰️ Clave Enviada:\n" . $client->__getLastRequest() . "\n";
            echo "📥 Autorizacion Respuesta:\n" . $client->__getLastResponse() . "\n";

            $arrayDocumento = [
                //'xml_autorizado' => $response->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante,
                'xml_autorizado' => $client->__getLastResponse(),
                'estado' => 'AUTORIZADO',
                'numero_autorizacion' => $response->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion,
                'fecha_autorizacion' => date('Y-m-d H:i:s', strtotime($response->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion)),
            ];

            $sql = "UPDATE bm_estado_archivos SET archivo_autorizado = ?, fecha_autorizacion = ?, estado_sri= ?, numeroAutorizacion = ? WHERE id_empresa = ? AND id_estado = ?;";
            $parametros = [
                $client->__getLastResponse(),
                date('Y-m-d H:i:s', strtotime($response->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion)),
                'T',
                $response->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion,
                $row['id_empresa'],
                $row['id_estado'],
            ];
            $conn->ejecutar($sql, $parametros);
        }
    }
}
