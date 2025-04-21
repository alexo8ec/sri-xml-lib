<?php

namespace SRI;

use DOMDocument;

class FirmaElectronica
{
    protected $rutaJar;
    protected $rutaCertificado;
    protected $claveCertificado;

    public function __construct(string $rutaCertificado, string $claveCertificado)
    {
        $this->rutaJar = 'src/CompelJar/dist/CompelJar.jar';
        //$this->rutaJar = __DIR__ . '/../CompelJar/dist/CompelJar.jar';
        //$this->rutaJar = realpath(__DIR__ . '/../CompelJar/dist/CompelJar.jar');

        $this->rutaCertificado = $rutaCertificado;
        $this->claveCertificado = $claveCertificado;
    }

    public function firmarXml(string $xmlPathOrigen, string $xmlPathDestino): array
    {
        $xmlFirmado = '';
        if (!file_exists($xmlPathOrigen)) {
            return ['success' => false, 'mensaje' => 'XML de origen no existe.'];
        }

        $comando = sprintf(
            'java -jar %s %s %s %s %s',
            escapeshellarg($this->rutaJar),
            escapeshellarg($this->rutaCertificado),
            escapeshellarg($this->claveCertificado),
            escapeshellarg($xmlPathOrigen),
            escapeshellarg(1) // Aquí siempre 1
        );
        $comando .= ' 2>&1';

        exec($comando, $output, $returnVar);

        // 3. Leer el XML firmado
        if ($returnVar === 0) {
            $xmlFirmado = file_get_contents($xmlPathOrigen);

            if (strpos($xmlFirmado, '<ds:Signature>') !== false) {
                // ¡XML firmado exitosamente!
            }
        }

        if (!file_exists($xmlPathOrigen)) {
            return ['success' => false, 'mensaje' => 'No se generó XML firmado.'];
        }
        return [
            'success' => true,
            'code' => 200,
            'message' => 'XML firmado exitosamente.',
            'data' => [
                'xmlFirmado' => $xmlFirmado,
                'xmlOrigen' => $xmlPathOrigen
            ]
        ];
    }
    public function obtenerRespuestaXml($firmado = false, $comprobante = "", $mensajes = null)
    {
        $xml = new DOMDocument("1.0", "UTF-8");
        $root = $xml->appendChild($xml->createElement('facturalgo'));
        $root->appendChild($xml->createElement('firmado', $firmado ? "VALIDO" : "INVALIDO"));
        if ($comprobante) {
            $nodoComprobante = $root->appendChild($xml->createElement('comprobante'));
            $nodoComprobante->appendChild($xml->createCDATASection($comprobante));
        } else {
            $root->appendChild($xml->createElement('comprobante', $comprobante));
        }
        if ($mensajes) {
            $nodoMensajes = $xml->createElement('mensajes');
            foreach ($mensajes as $mensaje) {
                $nodoItem = $nodoMensajes->appendChild($xml->createElement('mensaje'));
                $nodoItem->appendChild($xml->createCDATASection($mensaje));
            }
            $root->appendChild($nodoMensajes);
        } else {
            $root->appendChild($xml->createElement('mensajes', $comprobante));
        }
        $xml->xmlStandalone = false;
        $xml->formatOutput = true;
        $response = $xml->saveXML();
        return $response;
    }
    public function procesarRespuestaXml($xmlGenerado)
    {
        $compelXml = simplexml_load_string($xmlGenerado);
        $isValido = false;
        $comprobante = "";
        $mensajes = array();
        $isValido = isset($compelXml->firmado) ? ($compelXml->firmado == "VALIDO" ? true : false) : false;
        if ($isValido) {
            $comprobanteRespuesta = (string)$compelXml->comprobante;
            $lineas = explode("\n", $comprobanteRespuesta);
            foreach ($lineas as $linea) {
                if (strlen(trim($linea)) > 0) $comprobante .= $linea . "\n";
            }
        }
        if (isset($compelXml->mensajes)) {
            if ($compelXml->mensajes) {
                if (isset($compelXml->mensajes->mensaje)) {
                    foreach ($compelXml->mensajes->mensaje as $mensaje) {
                        $mensaje = $this->htmlspecial($mensaje);
                        array_push($mensajes, "<br>- " . $mensaje);
                    }
                }
            }
        }
        $result = array();
        $result["isValido"] = $isValido;
        $result["mensajes"] = $mensajes;
        $result["comprobante"] = $comprobante;
        return $result;
    }
    private function htmlspecial($valor)
    {
        $result = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
        return $result;
    }
}
