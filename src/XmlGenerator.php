<?php

namespace SRI;

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
        $infoFactura->addChild('fechaEmision', $data['fechaEmision']);
        $infoFactura->addChild('dirEstablecimiento', htmlspecialchars($data['dirEstablecimiento']));
        $infoFactura->addChild('obligadoContabilidad', $data['obligadoContabilidad']);
        $infoFactura->addChild('tipoIdentificacionComprador', $data['tipoIdentificacionComprador']);
        $infoFactura->addChild('razonSocialComprador', htmlspecialchars($data['razonSocialComprador']));
        $infoFactura->addChild('identificacionComprador', $data['identificacionComprador']);
        $infoFactura->addChild('totalSinImpuestos', $data['totalSinImpuestos']);
        $infoFactura->addChild('totalDescuento', $data['totalDescuento']);

        $totalConImpuestos = $infoFactura->addChild('totalConImpuestos');
        foreach ($data['totalConImpuestos'] as $impuesto) {
            $impuestoNode = $totalConImpuestos->addChild('totalImpuesto');
            foreach ($impuesto as $key => $value) {
                $impuestoNode->addChild($key, $value);
            }
        }

        $infoFactura->addChild('propina', $data['propina']);
        $infoFactura->addChild('importeTotal', $data['importeTotal']);
        $infoFactura->addChild('moneda', htmlspecialchars($data['moneda']));

        $pagos = $infoFactura->addChild('pagos');
        foreach ($data['pagos'] as $pago) {
            $pagoNode = $pagos->addChild('pago');
            foreach ($pago as $key => $value) {
                $pagoNode->addChild($key, $value);
            }
        }
    }

    public function addDetalles($detallesArray)
    {
        $detalles = $this->xml->addChild('detalles');
        foreach ($detallesArray as $detalle) {
            $detalleNode = $detalles->addChild('detalle');
            foreach ($detalle as $key => $value) {
                if ($key === 'impuestos') {
                    $impuestosNode = $detalleNode->addChild('impuestos');
                    foreach ($value as $impuesto) {
                        $impuestoNode = $impuestosNode->addChild('impuesto');
                        foreach ($impuesto as $subKey => $subValue) {
                            $impuestoNode->addChild($subKey, $subValue);
                        }
                    }
                } else {
                    $detalleNode->addChild($key, htmlspecialchars($value));
                }
            }
        }
    }

    public function addInfoAdicional($campos)
    {
        $infoAdicional = $this->xml->addChild('infoAdicional');
        foreach ($campos as $nombre => $valor) {
            $campoAdicional = $infoAdicional->addChild('campoAdicional', htmlspecialchars($valor));
            $campoAdicional->addAttribute('nombre', $nombre);
        }
    }

    public function getXml()
    {
        return $this->xml->asXML();
    }
}
