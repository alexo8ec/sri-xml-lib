<?php

namespace SRI;

use DOMDocument;
use SimpleXMLElement;
use SoapClient;

class ObtenerDocumentos
{
    private $xml;
    public function __construct() {}
    public function obtenerDocumentoSRI($r)
    {
        $clave = $r->clave_acceso;
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            ]
        ]);
        $servicio = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
        $parametros = array();
        $parametros['claveAccesoComprobante'] = $clave;
        $client = new SoapClient($servicio, ['stream_context' => $context]);
        $result = $client->autorizacionComprobante($parametros);
        return $result;
    }
}
