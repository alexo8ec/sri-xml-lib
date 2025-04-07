<?php

namespace SRI;

use SoapClient;
use SoapFault;

class SriClient
{
    public function enviarSRI($xml, $ambiente)
    {
        if ($ambiente == 1) {
            $wsdl = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
        } else {
            $wsdl = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";  // URL de prueba o producción
        }
        try {
            // Codificar el XML en Base64
            //$xmlBase64 = base64_encode($xml);

            // Crear el cliente SOAP
            $client = new SoapClient($wsdl, [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);

            // Enviar la solicitud
            $response = $client->validarComprobante([
                'xml' => $xml
            ]);

            return $response;
        } catch (SoapFault $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public function autorizarDoc($clave, $ambiente)
    {
        $url = "";
        switch ($ambiente) {
            case 1:
                $url = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
                break;
            case 2:
                $url = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
                break;
        }
        $options = [
            'trace' => 1,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'connection_timeout' => 60, // Aumenta el tiempo de espera
            'stream_context' => stream_context_create([
                'http' => ['timeout' => 60] // También en el contexto de la petición
            ])
        ];

        $params = array("claveAccesoComprobante" => $clave);
        $client = new SoapClient($url, $options);
        $result = $client->autorizacionComprobante($params);
        return $result;
    }
}
