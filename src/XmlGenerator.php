<?php

namespace SRI;

use Exception;
use SimpleXMLElement;

class XmlGenerator
{
    private $xml;

    public function __construct()
    {
        $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><factura id="comprobante" version="1.1.0"></factura>');
    }

    public function setInfoTributaria($data)
    {
        $infoTributaria = $this->xml->addChild('infoTributaria');
        $infoTributaria->addChild('ambiente', $data['ambiente']);
        $infoTributaria->addChild('tipoEmision', $data['tipoEmision']);
        $infoTributaria->addChild('razonSocial', htmlspecialchars($data['razonSocial']));
        $infoTributaria->addChild('nombreComercial', htmlspecialchars($data['nombreComercial']));
        $infoTributaria->addChild('ruc', $data['ruc']);
        $infoTributaria->addChild('claveAcceso', $data['claveAcceso']);
        $infoTributaria->addChild('codDoc', $data['codDoc']);
        $infoTributaria->addChild('estab', $data['estab']);
        $infoTributaria->addChild('ptoEmi', $data['ptoEmi']);
        $infoTributaria->addChild('secuencial', $data['secuencial']);
        $infoTributaria->addChild('dirMatriz', htmlspecialchars($data['dirMatriz']));
    }

    public function setInfoFactura($data)
    {
        $infoFactura = $this->xml->addChild('infoFactura');
        foreach ($data as $key => $value) {
            $infoFactura->addChild($key, htmlspecialchars($value));
        }
    }

    public function addDetalle($detalle)
    {
        $detalles = $this->xml->addChild('detalles');
        $detalleNode = $detalles->addChild('detalle');
        foreach ($detalle as $key => $value) {
            if (is_array($value)) {
                $subNode = $detalleNode->addChild($key);
                foreach ($value as $subKey => $subValue) {
                    $subNode->addChild($subKey, htmlspecialchars($subValue));
                }
            } else {
                $detalleNode->addChild($key, htmlspecialchars($value));
            }
        }
    }

    public function addCampoAdicional($nombre, $valor)
    {
        $infoAdicional = $this->xml->addChild('infoAdicional');
        $campoAdicional = $infoAdicional->addChild('campoAdicional', htmlspecialchars($valor));
        $campoAdicional->addAttribute('nombre', $nombre);
    }

    public function getXml()
    {
        return $this->xml->asXML();
    }
}
