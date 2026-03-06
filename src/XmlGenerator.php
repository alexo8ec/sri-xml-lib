<?php

namespace SRI;

use DOMDocument;
use SimpleXMLElement;

class XmlGenerator
{
    private $xml;
    public function __construct() {}
    public function generarRetencionXml($datos)
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<comprobanteRetencion xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
                      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xsi:noNamespaceSchemaLocation="file:/C:/xsd/comprobanteRetencion_2.0.0.xsd"
                      id="comprobante" version="2.0.0">
</comprobanteRetencion>
XML;
        $retencion = new \SimpleXMLElement($xml);
        // infoTributaria
        $infoTributaria = $retencion->addChild('infoTributaria');
        $infoTributaria->addChild('ambiente', $datos['infoTributaria']['ambiente']);
        $infoTributaria->addChild('tipoEmision', $datos['infoTributaria']['tipoEmision']);
        $infoTributaria->addChild('razonSocial', $datos['infoTributaria']['razonSocial']);
        $infoTributaria->addChild('nombreComercial', $datos['infoTributaria']['nombreComercial']);
        $infoTributaria->addChild('ruc', $datos['infoTributaria']['ruc']);
        $infoTributaria->addChild('claveAcceso', $datos['infoTributaria']['claveAcceso']);
        $infoTributaria->addChild('codDoc', $datos['infoTributaria']['codDoc']);
        $infoTributaria->addChild('estab', $datos['infoTributaria']['estab']);
        $infoTributaria->addChild('ptoEmi', $datos['infoTributaria']['ptoEmi']);
        $infoTributaria->addChild('secuencial', $datos['infoTributaria']['secuencial']);
        $infoTributaria->addChild('dirMatriz', $datos['infoTributaria']['dirMatriz']);
        if (!empty($datos['infoTributaria']['agenteRetencion'])) {
            $infoTributaria->addChild('agenteRetencion', $datos['infoTributaria']['agenteRetencion']);
        }
        if (!empty($datos['infoTributaria']['contribuyenteRimpe'])) {
            $infoTributaria->addChild('contribuyenteRimpe', $datos['infoTributaria']['contribuyenteRimpe']);
        }
        // infoCompRetencion
        $infoCompRetencion = $retencion->addChild('infoCompRetencion');
        $infoCompRetencion->addChild('fechaEmision', $datos['infoCompRetencion']['fechaEmision']);
        if (!empty($datos['infoCompRetencion']['dirEstablecimiento'])) {
            $infoCompRetencion->addChild('dirEstablecimiento', $datos['infoCompRetencion']['dirEstablecimiento']);
        }
        if (!empty($datos['infoCompRetencion']['contribuyenteEspecial'])) {
            $infoCompRetencion->addChild('contribuyenteEspecial', $datos['infoCompRetencion']['contribuyenteEspecial']);
        }
        if (!empty($datos['infoCompRetencion']['obligadoContabilidad'])) {
            $infoCompRetencion->addChild('obligadoContabilidad', $datos['infoCompRetencion']['obligadoContabilidad']);
        }
        $infoCompRetencion->addChild('tipoIdentificacionSujetoRetenido', $datos['infoCompRetencion']['tipoIdentificacionSujetoRetenido']);
        if (!empty($datos['infoCompRetencion']['tipoSujetoRetenido'])) {
            $infoCompRetencion->addChild('tipoSujetoRetenido', $datos['infoCompRetencion']['tipoSujetoRetenido']);
        }
        $infoCompRetencion->addChild('parteRel', $datos['infoCompRetencion']['parteRel']);
        $infoCompRetencion->addChild('razonSocialSujetoRetenido', $datos['infoCompRetencion']['razonSocialSujetoRetenido']);
        $infoCompRetencion->addChild('identificacionSujetoRetenido', $datos['infoCompRetencion']['identificacionSujetoRetenido']);
        $infoCompRetencion->addChild('periodoFiscal', $datos['infoCompRetencion']['periodoFiscal']);
        // docsSustento
        $docsSustento = $retencion->addChild('docsSustento');
        foreach ($datos['docsSustento'] as $doc) {
            $docSustento = $docsSustento->addChild('docSustento');
            $docSustento->addChild('codSustento', $doc['codSustento']);
            $docSustento->addChild('codDocSustento', $doc['codDocSustento']);
            $docSustento->addChild('numDocSustento', $doc['numDocSustento']);
            $docSustento->addChild('fechaEmisionDocSustento', $doc['fechaEmisionDocSustento']);
            if (!empty($doc['fechaRegistroContable'])) {
                $docSustento->addChild('fechaRegistroContable', $doc['fechaRegistroContable']);
            }
            if (!empty($doc['numAutDocSustento'])) {
                $docSustento->addChild('numAutDocSustento', $doc['numAutDocSustento']);
            }
            $docSustento->addChild('pagoLocExt', $doc['pagoLocExt']);
            if (!empty($doc['tipoRegi'])) {
                $docSustento->addChild('tipoRegi', $doc['tipoRegi']);
            }
            if (!empty($doc['paisEfecPago'])) {
                $docSustento->addChild('paisEfecPago', $doc['paisEfecPago']);
            }
            if (!empty($doc['aplicConvDobTrib'])) {
                $docSustento->addChild('aplicConvDobTrib', $doc['aplicConvDobTrib']);
            }
            if (!empty($doc['pagExtSujRetNorLeg'])) {
                $docSustento->addChild('pagExtSujRetNorLeg', $doc['pagExtSujRetNorLeg']);
            }
            if (!empty($doc['pagoRegFis'])) {
                $docSustento->addChild('pagoRegFis', $doc['pagoRegFis']);
            }
            if (!empty($doc['totalComprobantesReembolso'])) {
                $docSustento->addChild('totalComprobantesReembolso', number_format($doc['totalComprobantesReembolso'], 2, '.', ''));
            }
            if (!empty($doc['totalBaseImponibleReembolso'])) {
                $docSustento->addChild('totalBaseImponibleReembolso', number_format($doc['totalBaseImponibleReembolso'], 2, '.', ''));
            }
            if (!empty($doc['totalImpuestoReembolso'])) {
                $docSustento->addChild('totalImpuestoReembolso', number_format($doc['totalImpuestoReembolso'], 2, '.', ''));
            }
            $docSustento->addChild('totalSinImpuestos', number_format($doc['totalSinImpuestos'], 2, '.', ''));
            $docSustento->addChild('importeTotal', number_format($doc['importeTotal'], 2, '.', ''));
            // impuestosDocSustento
            $impuestosDocSustento = $docSustento->addChild('impuestosDocSustento');
            foreach ($doc['impuestosDocSustento'] as $impDoc) {
                $impuestoDocSustento = $impuestosDocSustento->addChild('impuestoDocSustento');
                $impuestoDocSustento->addChild('codImpuestoDocSustento', $impDoc['codImpuestoDocSustento']);
                $impuestoDocSustento->addChild('codigoPorcentaje', $impDoc['codigoPorcentaje']);
                $impuestoDocSustento->addChild('baseImponible', number_format($impDoc['baseImponible'], 2, '.', ''));
                $impuestoDocSustento->addChild('tarifa', number_format($impDoc['tarifa'], 2, '.', ''));
                $impuestoDocSustento->addChild('valorImpuesto', number_format($impDoc['valorImpuesto'], 2, '.', ''));
            }
            // retenciones
            $retenciones = $docSustento->addChild('retenciones');
            foreach ($doc['retenciones'] as $ret) {
                $retencionNode = $retenciones->addChild('retencion');
                $retencionNode->addChild('codigo', $ret['codigo']);
                $retencionNode->addChild('codigoRetencion', $ret['codigoRetencion']);
                $retencionNode->addChild('baseImponible', number_format($ret['baseImponible'], 2, '.', ''));
                $retencionNode->addChild('porcentajeRetener', number_format($ret['porcentajeRetener'], 2, '.', ''));
                $retencionNode->addChild('valorRetenido', number_format($ret['valorRetenido'], 2, '.', ''));
                if (!empty($ret['dividendos'])) {
                    $dividendos = $retencionNode->addChild('dividendos');
                    $dividendos->addChild('fechaPagoDiv', $ret['dividendos']['fechaPagoDiv']);
                    $dividendos->addChild('imRentaSoc', number_format($ret['dividendos']['imRentaSoc'], 2, '.', ''));
                    $dividendos->addChild('ejerFisUtDiv', number_format($ret['dividendos']['ejerFisUtDiv'], 2, '.', ''));
                }
                if (!empty($ret['compraCajBanano'])) {
                    $compraCajBanano = $retencionNode->addChild('compraCajBanano');
                    $compraCajBanano->addChild('numCajBan', number_format($ret['compraCajBanano']['numCajBan'], 2, '.', ''));
                    $compraCajBanano->addChild('precCajBan', number_format($ret['compraCajBanano']['precCajBan'], 2, '.', ''));
                }
            }
            // reembolsos (opcional)
            if (!empty($doc['reembolsos'])) {
                $reembolsos = $docSustento->addChild('reembolsos');
                foreach ($doc['reembolsos'] as $reembolso) {
                    $reembolsoDetalle = $reembolsos->addChild('reembolsoDetalle');
                    $reembolsoDetalle->addChild('tipoIdentificacionProveedorReembolso', $reembolso['tipoIdentificacionProveedorReembolso']);
                    $reembolsoDetalle->addChild('identificacionProveedorReembolso', $reembolso['identificacionProveedorReembolso']);
                    if (!empty($reembolso['codPaisPagoProveedorReembolso'])) {
                        $reembolsoDetalle->addChild('codPaisPagoProveedorReembolso', $reembolso['codPaisPagoProveedorReembolso']);
                    }
                    // Agregar otros campos de reembolso según necesidad
                }
            }
            // pagos
            $pagos = $docSustento->addChild('pagos');
            foreach ($doc['pagos'] as $pago) {
                $pagoNode = $pagos->addChild('pago');
                $pagoNode->addChild('formaPago', $pago['formaPago']);
                $pagoNode->addChild('total', number_format($pago['total'], 2, '.', ''));
                if (!empty($pago['plazo'])) {
                    $pagoNode->addChild('plazo', $pago['plazo']);
                }
                if (!empty($pago['unidadTiempo'])) {
                    $pagoNode->addChild('unidadTiempo', $pago['unidadTiempo']);
                }
            }
        }
        // maquinaFiscal (opcional)
        if (!empty($datos['maquinaFiscal'])) {
            $maquinaFiscal = $retencion->addChild('maquinaFiscal');
            $maquinaFiscal->addChild('marca', $datos['maquinaFiscal']['marca']);
            $maquinaFiscal->addChild('modelo', $datos['maquinaFiscal']['modelo']);
            $maquinaFiscal->addChild('serie', $datos['maquinaFiscal']['serie']);
        }
        // infoAdicional
        if (!empty($datos['infoAdicional'])) {
            $infoAdicional = $retencion->addChild('infoAdicional');
            foreach ($datos['infoAdicional'] as $campo) {
                if (!empty(trim($campo['valor']))) {
                    $campoNode = $infoAdicional->addChild('campoAdicional', htmlspecialchars($campo['valor']));
                    $campoNode->addAttribute('nombre', $campo['nombre']);
                }
            }
        }
        $xmlString = $retencion->asXML();
        return $this->formatXml($xmlString);
    }
    public function generarFacturaXml($datos)
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<factura xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="file:/C:/borrar/xsd/111-xsd-1_V2.1.0.xsd"
         id="comprobante" version="2.1.0">
</factura>
XML;
        $factura = new \SimpleXMLElement($xml);
        // infoTributaria
        $infoTributaria = $factura->addChild('infoTributaria');
        $infoTributaria->addChild('ambiente', $datos['infoTributaria']['ambiente']);
        $infoTributaria->addChild('tipoEmision', $datos['infoTributaria']['tipoEmision']);
        $infoTributaria->addChild('razonSocial', $datos['infoTributaria']['razonSocial']);
        $infoTributaria->addChild('nombreComercial', $datos['infoTributaria']['nombreComercial']);
        $infoTributaria->addChild('ruc', $datos['infoTributaria']['ruc']);
        $infoTributaria->addChild('claveAcceso', $datos['infoTributaria']['claveAcceso']);
        $infoTributaria->addChild('codDoc', $datos['infoTributaria']['codDoc']);
        $infoTributaria->addChild('estab', $datos['infoTributaria']['estab']);
        $infoTributaria->addChild('ptoEmi', $datos['infoTributaria']['ptoEmi']);
        $infoTributaria->addChild('secuencial', $datos['infoTributaria']['secuencial']);
        $infoTributaria->addChild('dirMatriz', $datos['infoTributaria']['dirMatriz']);
        if (!empty($datos['infoTributaria']['agenteRetencion'])) {
            $infoTributaria->addChild('agenteRetencion', $datos['infoTributaria']['agenteRetencion']);
        }
        if (!empty($datos['infoTributaria']['contribuyenteRimpe'])) {
            $infoTributaria->addChild('contribuyenteRimpe', $datos['infoTributaria']['contribuyenteRimpe']);
        }
        // infoFactura
        $infoFactura = $factura->addChild('infoFactura');
        $infoFactura->addChild('fechaEmision', $datos['infoFactura']['fechaEmision']);
        if (!empty($datos['infoTributaria']['contribuyenteEspecial'])) {
            $infoTributaria->addChild('contribuyenteEspecial', $datos['infoTributaria']['contribuyenteEspecial']);
        }
        $infoFactura->addChild('dirEstablecimiento', $datos['infoFactura']['dirEstablecimiento']);
        $infoFactura->addChild('obligadoContabilidad', $datos['infoFactura']['obligadoContabilidad']);
        $infoFactura->addChild('tipoIdentificacionComprador', $datos['infoFactura']['tipoIdentificacionComprador']);
        $infoFactura->addChild('razonSocialComprador', $datos['infoFactura']['razonSocialComprador']);
        $infoFactura->addChild('identificacionComprador', $datos['infoFactura']['identificacionComprador']);
        $infoFactura->addChild('direccionComprador', $datos['infoFactura']['direccionComprador']);
        $infoFactura->addChild('totalSinImpuestos', number_format($datos['infoFactura']['totalSinImpuestos'], 2, '.', ''));
        $infoFactura->addChild('totalDescuento', number_format($datos['infoFactura']['totalDescuento'], 2, '.', ''));
        // totalConImpuestos
        $totalConImpuestos = $infoFactura->addChild('totalConImpuestos');
        foreach ($datos['infoFactura']['totalConImpuestos'] as $impuesto) {
            $totalImpuesto = $totalConImpuestos->addChild('totalImpuesto');
            $totalImpuesto->addChild('codigo', $impuesto['codigo']);
            $totalImpuesto->addChild('codigoPorcentaje', $impuesto['codigoPorcentaje']);
            $totalImpuesto->addChild('baseImponible', number_format($impuesto['baseImponible'], 2, '.', ''));
            $totalImpuesto->addChild('valor', number_format($impuesto['valor'], 2, '.', ''));
        }
        $infoFactura->addChild('propina', number_format($datos['infoFactura']['propina'], 2, '.', ''));
        $infoFactura->addChild('importeTotal', number_format($datos['infoFactura']['importeTotal'], 2, '.', ''));
        $infoFactura->addChild('moneda', $datos['infoFactura']['moneda']);
        // pagos
        $pagos = $infoFactura->addChild('pagos');
        foreach ($datos['infoFactura']['pagos'] as $pago) {
            $pagoNode = $pagos->addChild('pago');
            $pagoNode->addChild('formaPago', $pago['formaPago']);
            $pagoNode->addChild('total', number_format($pago['total'], 2, '.', ''));
            if (!empty($pago['plazo'])) {
                $pagoNode->addChild('plazo', $pago['plazo']);
            } else {
                $pagoNode->addChild('plazo', 0);
            }
            if (!empty($pago['unidadTiempo'])) {
                $pagoNode->addChild('unidadTiempo', $pago['unidadTiempo']);
            }
        }
        // detalles
        $detalles = $factura->addChild('detalles');
        foreach ($datos['detalles'] as $detalle) {
            $detalleNode = $detalles->addChild('detalle');
            $detalleNode->addChild('codigoPrincipal', $detalle['codigoPrincipal']);
            $detalleNode->addChild('descripcion', $detalle['descripcion']);
            $detalleNode->addChild('cantidad', number_format($detalle['cantidad'], 6, '.', ''));
            $detalleNode->addChild('precioUnitario', number_format($detalle['precioUnitario'], 6, '.', ''));
            $detalleNode->addChild('descuento', number_format($detalle['descuento'], 2, '.', ''));
            $detalleNode->addChild('precioTotalSinImpuesto', number_format($detalle['precioTotalSinImpuesto'], 2, '.', ''));
            if (!empty($detalle['detallesAdicionales'])) {
                $detAdicionales = $detalleNode->addChild('detallesAdicionales');
                foreach ($detalle['detallesAdicionales'] as $adicional) {
                    $ad = $detAdicionales->addChild('detAdicional');
                    $ad->addAttribute('nombre', $adicional['nombre']);
                    $ad->addAttribute('valor', $adicional['valor']);
                }
            }
            $impuestos = $detalleNode->addChild('impuestos');
            foreach ($detalle['impuestos'] as $imp) {
                $impuesto = $impuestos->addChild('impuesto');
                $impuesto->addChild('codigo', $imp['codigo']);
                $impuesto->addChild('codigoPorcentaje', $imp['codigoPorcentaje']);
                $impuesto->addChild('tarifa', number_format($imp['tarifa'], 2, '.', ''));
                $impuesto->addChild('baseImponible', number_format($imp['baseImponible'], 2, '.', ''));
                $impuesto->addChild('valor', number_format($imp['valor'], 2, '.', ''));
            }
        }
        // infoAdicional
        if (!empty($datos['infoAdicional'])) {
            $infoAdicional = $factura->addChild('infoAdicional');
            foreach ($datos['infoAdicional'] as $campo) {
                if (!empty(trim($campo['valor']))) {
                    $campoNode = $infoAdicional->addChild('campoAdicional', htmlspecialchars($campo['valor']));
                    $campoNode->addAttribute('nombre', $campo['nombre']);
                }
            }
        }
        $xmlString = $factura->asXML();
        $xmlFormateado = $this->formatXml($xmlString);
        return $xmlFormateado;
    }
    public function generarNotaCreditoXml($datos)
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<notaCredito xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="file:/C:/borrar/xsd/11-xsd-3_V1.1.0.xsd"
             id="comprobante" version="1.1.0">
</notaCredito>
XML;
        $notaCredito = new SimpleXMLElement($xml);
        // infoTributaria
        $infoTributaria = $notaCredito->addChild('infoTributaria');
        $infoTributaria->addChild('ambiente', $datos['infoTributaria']['ambiente']);
        $infoTributaria->addChild('tipoEmision', $datos['infoTributaria']['tipoEmision']);
        $infoTributaria->addChild('razonSocial', $datos['infoTributaria']['razonSocial']);
        $infoTributaria->addChild('nombreComercial', $datos['infoTributaria']['nombreComercial']);
        $infoTributaria->addChild('ruc', $datos['infoTributaria']['ruc']);
        $infoTributaria->addChild('claveAcceso', $datos['infoTributaria']['claveAcceso']);
        $infoTributaria->addChild('codDoc', $datos['infoTributaria']['codDoc']);
        $infoTributaria->addChild('estab', $datos['infoTributaria']['estab']);
        $infoTributaria->addChild('ptoEmi', $datos['infoTributaria']['ptoEmi']);
        $infoTributaria->addChild('secuencial', $datos['infoTributaria']['secuencial']);
        $infoTributaria->addChild('dirMatriz', $datos['infoTributaria']['dirMatriz']);
        // infoNotaCredito
        $infoNotaCredito = $notaCredito->addChild('infoNotaCredito');
        $infoNotaCredito->addChild('fechaEmision', $datos['infoNotaCredito']['fechaEmision']);
        $infoNotaCredito->addChild('dirEstablecimiento', $datos['infoNotaCredito']['dirEstablecimiento']);
        $infoNotaCredito->addChild('tipoIdentificacionComprador', $datos['infoNotaCredito']['tipoIdentificacionComprador']);
        $infoNotaCredito->addChild('razonSocialComprador', $datos['infoNotaCredito']['razonSocialComprador']);
        $infoNotaCredito->addChild('identificacionComprador', $datos['infoNotaCredito']['identificacionComprador']);
        $infoNotaCredito->addChild('obligadoContabilidad', $datos['infoNotaCredito']['obligadoContabilidad']);
        $infoNotaCredito->addChild('codDocModificado', $datos['infoNotaCredito']['codDocModificado']);
        $infoNotaCredito->addChild('numDocModificado', $datos['infoNotaCredito']['numDocModificado']);
        $infoNotaCredito->addChild('fechaEmisionDocSustento', $datos['infoNotaCredito']['fechaEmisionDocSustento']);
        $infoNotaCredito->addChild('totalSinImpuestos', number_format($datos['infoNotaCredito']['totalSinImpuestos'], 2, '.', ''));
        $infoNotaCredito->addChild('valorModificacion', number_format($datos['infoNotaCredito']['valorModificacion'], 2, '.', ''));
        $infoNotaCredito->addChild('moneda', $datos['infoNotaCredito']['moneda']);
        $totalConImpuestos = $infoNotaCredito->addChild('totalConImpuestos');
        foreach ($datos['infoNotaCredito']['totalConImpuestos'] as $impuesto) {
            $totalImpuesto = $totalConImpuestos->addChild('totalImpuesto');
            $totalImpuesto->addChild('codigo', $impuesto['codigo']);
            $totalImpuesto->addChild('codigoPorcentaje', $impuesto['codigoPorcentaje']);
            $totalImpuesto->addChild('baseImponible', number_format($impuesto['baseImponible'], 2, '.', ''));
            $totalImpuesto->addChild('valor', number_format($impuesto['valor'], 2, '.', ''));
        }
        $infoNotaCredito->addChild('motivo', $datos['infoNotaCredito']['motivo']);
        // detalles
        $detalles = $notaCredito->addChild('detalles');
        foreach ($datos['detalles'] as $detalle) {
            $detalleNode = $detalles->addChild('detalle');
            $detalleNode->addChild('codigoInterno', $detalle['codigoInterno']);
            $detalleNode->addChild('descripcion', $detalle['descripcion']);
            $detalleNode->addChild('cantidad', number_format($detalle['cantidad'], 6, '.', ''));
            $detalleNode->addChild('precioUnitario', number_format($detalle['precioUnitario'], 6, '.', ''));
            $detalleNode->addChild('descuento', number_format($detalle['descuento'], 2, '.', ''));
            $detalleNode->addChild('precioTotalSinImpuesto', number_format($detalle['precioTotalSinImpuesto'], 2, '.', ''));
            if (!empty($detalle['detallesAdicionales'])) {
                $detallesAdicionales = $detalleNode->addChild('detallesAdicionales');
                foreach ($detalle['detallesAdicionales'] as $adicional) {
                    $detAdicional = $detallesAdicionales->addChild('detAdicional');
                    $detAdicional->addAttribute('nombre', $adicional['nombre']);
                    $detAdicional->addAttribute('valor', $adicional['valor']);
                }
            }
            $impuestos = $detalleNode->addChild('impuestos');
            foreach ($detalle['impuestos'] as $impuesto) {
                $impuestoNode = $impuestos->addChild('impuesto');
                $impuestoNode->addChild('codigo', $impuesto['codigo']);
                $impuestoNode->addChild('codigoPorcentaje', $impuesto['codigoPorcentaje']);
                $impuestoNode->addChild('tarifa', number_format($impuesto['tarifa'], 2, '.', ''));
                $impuestoNode->addChild('baseImponible', number_format($impuesto['baseImponible'], 2, '.', ''));
                $impuestoNode->addChild('valor', number_format($impuesto['valor'], 2, '.', ''));
            }
        }
        // infoAdicional
        if (!empty($datos['infoAdicional'])) {
            $infoAdicional = $notaCredito->addChild('infoAdicional');
            foreach ($datos['infoAdicional'] as $campo) {
                $campoAdicional = $infoAdicional->addChild('campoAdicional', htmlspecialchars($campo['valor']));
                $campoAdicional->addAttribute('nombre', $campo['nombre']);
            }
        }
        $xmlString = $notaCredito->asXML();
        $xmlFormateado = $this->formatXml($xmlString);
        return $xmlFormateado;
    }
    private function formatXml($xmlString)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlString);
        return $dom->saveXML();
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
