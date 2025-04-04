<?php

namespace SRI;

class SriClient
{
    public function enviarSRI($xml)
    {
        $url = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";  // URL de prueba o producción
        $postData = [
            'xml' => $xml
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Si es necesario desactivar la verificación SSL

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
