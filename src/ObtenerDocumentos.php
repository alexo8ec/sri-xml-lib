<?php

namespace SRI;

use SoapClient;
class ObtenerDocumentos
{
    public function __construct() {}
    public function obtenerDocumentoSRI($r)
    {
        $arrayRespuesta = [];
        try {
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
            $arrayRespuesta = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Documento extraido correctamente',
                'result' => $result
            ];
        } catch (\Throwable $e) {
            $arrayRespuesta = [
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
        }
        return $arrayRespuesta;
    }
}
