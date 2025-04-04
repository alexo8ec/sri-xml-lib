<?php

namespace SRI;

use SoapClient;
use SoapFault;

class SriClient
{
    public function enviarSRI($xml)
    {
        $wsdl = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";  // URL de prueba o producción
        try {
            // Codificar el XML en Base64
            $xmlBase64 = base64_encode($xml);

            // Crear el cliente SOAP
            $client = new SoapClient($wsdl, [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);

            // Enviar la solicitud
            $response = $client->validarComprobante([
                'xml' => $xmlBase64
            ]);

            return $response;
        } catch (SoapFault $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
