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
            if ($empresa['id_tipo_ambiente'] == 2) {
                $wsdl = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl '; //Ambiente producción
            } else {
                $wsdl = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl'; //Ambiente pruebas
            }
            $client = new SoapClient($wsdl, [
                'trace' => 1,
                'exceptions' => true,
            ]);

            echo '<pre>';
            print_r($client);
            exit;

            $xmlFirmado = $row['archivo_firmado'];

            $doc = new DOMDocument();
            $doc->loadXML($xmlFirmado);
            file_put_contents('temp/debug.xml', $doc->saveXML());

            echo '<pre>';
            print_r($doc);
            exit;

            $xml = new DOMDocument();
            $xml->loadXml($xmlFirmado);
            libxml_use_internal_errors(true);
            if ($xml->schemaValidate('xsd/NotaCredito_V1.1.0.xsd')) {
                echo "✅ XML válido contra el XSD.<br/>";
            } else {
                echo "❌ El XML no es válido.\n";
                // Mostrar errores
                foreach (libxml_get_errors() as $error) {
                    echo "[Línea {$error->line}] {$error->message}";
                }

                // Limpiar errores
                libxml_clear_errors();
                exit;
            }

            $params = [
                'xml' => $xmlFirmado
            ];

            $response = $client->__soapCall('validarComprobante', [$params]);
            echo "🛰️ XML Enviado:\n" . $client->__getLastRequest() . "\n";
            echo "📥 XML Respuesta:\n" . $client->__getLastResponse() . "\n";

            echo '<pre>';
            print_r($response);
            exit;

            $sql = "UPDATE bm_estado_archivos SET estado_recibido = ?, fecha_recibido = ? WHERE id_empresa = ?;";
            $parametros = [
                'ENVIADO',
                date('Y-m-d H:i:s'),
                $row['id_empresa']
            ];
            $conn->ejecutar($sql, $parametros);
        }
    }
}
