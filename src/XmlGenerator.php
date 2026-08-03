<?php

namespace SRI;

use DOMDocument;
use SimpleXMLElement;

class XmlGenerator
{
    private $xml;
    public function __construct() {}
    public function generarRetencionXml(array $datos)
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
    public function generarFacturaXml(array $datos)
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
    public function generarNotaCreditoXml(array $datos)
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
    public function generarGuiaRemisionXml(array $datos): string
    {
        $version = $datos['version'] ?? '1.1.0';

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<guiaRemision id="comprobante" version="{$version}">
</guiaRemision>
XML;

        $guia = new \SimpleXMLElement($xml);

        /*
    |--------------------------------------------------------------------------
    | infoTributaria
    |--------------------------------------------------------------------------
    */
        $infoTributaria = $guia->addChild('infoTributaria');

        $this->addChildText($infoTributaria, 'ambiente', $datos['infoTributaria']['ambiente']);
        $this->addChildText($infoTributaria, 'tipoEmision', $datos['infoTributaria']['tipoEmision'] ?? '1');
        $this->addChildText($infoTributaria, 'razonSocial', $datos['infoTributaria']['razonSocial']);

        if (!empty($datos['infoTributaria']['nombreComercial'])) {
            $this->addChildText($infoTributaria, 'nombreComercial', $datos['infoTributaria']['nombreComercial']);
        }

        $this->addChildText($infoTributaria, 'ruc', $datos['infoTributaria']['ruc']);
        $this->addChildText($infoTributaria, 'claveAcceso', $datos['infoTributaria']['claveAcceso']);
        $this->addChildText($infoTributaria, 'codDoc', '06');
        $this->addChildText($infoTributaria, 'estab', $datos['infoTributaria']['estab']);
        $this->addChildText($infoTributaria, 'ptoEmi', $datos['infoTributaria']['ptoEmi']);
        $this->addChildText($infoTributaria, 'secuencial', $datos['infoTributaria']['secuencial']);
        $this->addChildText($infoTributaria, 'dirMatriz', $datos['infoTributaria']['dirMatriz']);

        if (!empty($datos['infoTributaria']['agenteRetencion'])) {
            $this->addChildText($infoTributaria, 'agenteRetencion', $datos['infoTributaria']['agenteRetencion']);
        }

        if (!empty($datos['infoTributaria']['contribuyenteRimpe'])) {
            $this->addChildText($infoTributaria, 'contribuyenteRimpe', $datos['infoTributaria']['contribuyenteRimpe']);
        }

        /*
    |--------------------------------------------------------------------------
    | infoGuiaRemision
    |--------------------------------------------------------------------------
    */
        $infoGuia = $guia->addChild('infoGuiaRemision');

        if (!empty($datos['infoGuiaRemision']['dirEstablecimiento'])) {
            $this->addChildText($infoGuia, 'dirEstablecimiento', $datos['infoGuiaRemision']['dirEstablecimiento']);
        }

        $this->addChildText($infoGuia, 'dirPartida', $datos['infoGuiaRemision']['dirPartida']);
        $this->addChildText($infoGuia, 'razonSocialTransportista', $datos['infoGuiaRemision']['razonSocialTransportista']);
        $this->addChildText($infoGuia, 'tipoIdentificacionTransportista', $datos['infoGuiaRemision']['tipoIdentificacionTransportista']);
        $this->addChildText($infoGuia, 'rucTransportista', $datos['infoGuiaRemision']['rucTransportista']);

        if (!empty($datos['infoGuiaRemision']['rise'])) {
            $this->addChildText($infoGuia, 'rise', $datos['infoGuiaRemision']['rise']);
        }

        if (!empty($datos['infoGuiaRemision']['obligadoContabilidad'])) {
            $this->addChildText($infoGuia, 'obligadoContabilidad', $datos['infoGuiaRemision']['obligadoContabilidad']);
        }

        if (!empty($datos['infoGuiaRemision']['contribuyenteEspecial'])) {
            $this->addChildText($infoGuia, 'contribuyenteEspecial', $datos['infoGuiaRemision']['contribuyenteEspecial']);
        }

        $this->addChildText($infoGuia, 'fechaIniTransporte', $this->fechaSri($datos['infoGuiaRemision']['fechaIniTransporte']));
        $this->addChildText($infoGuia, 'fechaFinTransporte', $this->fechaSri($datos['infoGuiaRemision']['fechaFinTransporte']));
        $this->addChildText($infoGuia, 'placa', $datos['infoGuiaRemision']['placa']);

        /*
    |--------------------------------------------------------------------------
    | destinatarios
    |--------------------------------------------------------------------------
    */
        $destinatarios = $guia->addChild('destinatarios');

        foreach ($datos['destinatarios'] as $destinatarioData) {
            $destinatario = $destinatarios->addChild('destinatario');

            $this->addChildText($destinatario, 'identificacionDestinatario', $destinatarioData['identificacionDestinatario']);
            $this->addChildText($destinatario, 'razonSocialDestinatario', $destinatarioData['razonSocialDestinatario']);
            $this->addChildText($destinatario, 'dirDestinatario', $destinatarioData['dirDestinatario']);
            $this->addChildText($destinatario, 'motivoTraslado', $destinatarioData['motivoTraslado']);

            if (!empty($destinatarioData['docAduaneroUnico'])) {
                $this->addChildText($destinatario, 'docAduaneroUnico', $destinatarioData['docAduaneroUnico']);
            }

            if (!empty($destinatarioData['codEstabDestino'])) {
                $this->addChildText($destinatario, 'codEstabDestino', $destinatarioData['codEstabDestino']);
            }

            if (!empty($destinatarioData['ruta'])) {
                $this->addChildText($destinatario, 'ruta', $destinatarioData['ruta']);
            }

            if (!empty($destinatarioData['codDocSustento'])) {
                $this->addChildText($destinatario, 'codDocSustento', $destinatarioData['codDocSustento']);
            }

            if (!empty($destinatarioData['numDocSustento'])) {
                $this->addChildText($destinatario, 'numDocSustento', $destinatarioData['numDocSustento']);
            }

            if (!empty($destinatarioData['numAutDocSustento'])) {
                $this->addChildText($destinatario, 'numAutDocSustento', $destinatarioData['numAutDocSustento']);
            }

            if (!empty($destinatarioData['fechaEmisionDocSustento'])) {
                $this->addChildText($destinatario, 'fechaEmisionDocSustento', $this->fechaSri($destinatarioData['fechaEmisionDocSustento']));
            }

            /*
        |--------------------------------------------------------------------------
        | detalles
        |--------------------------------------------------------------------------
        */
            $detalles = $destinatario->addChild('detalles');

            foreach ($destinatarioData['detalles'] as $detalleData) {
                $detalle = $detalles->addChild('detalle');

                if (!empty($detalleData['codigoInterno'])) {
                    $this->addChildText($detalle, 'codigoInterno', $detalleData['codigoInterno']);
                }

                if (!empty($detalleData['codigoAdicional'])) {
                    $this->addChildText($detalle, 'codigoAdicional', $detalleData['codigoAdicional']);
                }

                $this->addChildText($detalle, 'descripcion', $detalleData['descripcion']);
                $this->addChildText($detalle, 'cantidad', $this->numeroSri($detalleData['cantidad'], 6));

                if (!empty($detalleData['detallesAdicionales']) && is_array($detalleData['detallesAdicionales'])) {
                    $detallesAdicionales = $detalle->addChild('detallesAdicionales');

                    foreach ($detalleData['detallesAdicionales'] as $detAdicionalData) {
                        if (empty($detAdicionalData['nombre']) || empty($detAdicionalData['valor'])) {
                            continue;
                        }

                        $detAdicional = $detallesAdicionales->addChild('detAdicional');
                        $detAdicional->addAttribute('nombre', $this->limpiarTexto($detAdicionalData['nombre']));
                        $detAdicional->addAttribute('valor', $this->limpiarTexto($detAdicionalData['valor']));
                    }
                }
            }
        }

        /*
    |--------------------------------------------------------------------------
    | infoAdicional
    |--------------------------------------------------------------------------
    */
        if (!empty($datos['infoAdicional']) && is_array($datos['infoAdicional'])) {
            $infoAdicional = $guia->addChild('infoAdicional');

            foreach ($datos['infoAdicional'] as $campo) {
                if (empty($campo['nombre']) || empty($campo['valor'])) {
                    continue;
                }

                $campoAdicional = $infoAdicional->addChild('campoAdicional');
                $campoAdicional->addAttribute('nombre', $this->limpiarTexto($campo['nombre']));

                $node = dom_import_simplexml($campoAdicional);
                $node->appendChild($node->ownerDocument->createTextNode($this->limpiarTexto($campo['valor'])));
            }
        }

        return $this->formatXml($guia->asXML());
    }
    public function generarLiquidacionCompraXml(array $datos): string
    {
        $version = (string) ($datos['version'] ?? '1.1.0');

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<liquidacionCompra id="comprobante" version="{$version}">
</liquidacionCompra>
XML;

        $liquidacion = new \SimpleXMLElement($xml);

        /*
    |--------------------------------------------------------------------------
    | infoTributaria
    |--------------------------------------------------------------------------
    */
        $infoTributaria = $liquidacion->addChild('infoTributaria');

        $this->addChildText($infoTributaria, 'ambiente', $datos['infoTributaria']['ambiente']);
        $this->addChildText($infoTributaria, 'tipoEmision', $datos['infoTributaria']['tipoEmision'] ?? '1');
        $this->addChildText($infoTributaria, 'razonSocial', $datos['infoTributaria']['razonSocial']);

        if (!empty($datos['infoTributaria']['nombreComercial'])) {
            $this->addChildText($infoTributaria, 'nombreComercial', $datos['infoTributaria']['nombreComercial']);
        }

        $this->addChildText($infoTributaria, 'ruc', $datos['infoTributaria']['ruc']);
        $this->addChildText($infoTributaria, 'claveAcceso', $datos['infoTributaria']['claveAcceso']);
        $this->addChildText($infoTributaria, 'codDoc', '03');
        $this->addChildText($infoTributaria, 'estab', $datos['infoTributaria']['estab']);
        $this->addChildText($infoTributaria, 'ptoEmi', $datos['infoTributaria']['ptoEmi']);
        $this->addChildText($infoTributaria, 'secuencial', $datos['infoTributaria']['secuencial']);
        $this->addChildText($infoTributaria, 'dirMatriz', $datos['infoTributaria']['dirMatriz']);

        if (!empty($datos['infoTributaria']['agenteRetencion'])) {
            $this->addChildText($infoTributaria, 'agenteRetencion', $datos['infoTributaria']['agenteRetencion']);
        }

        if (!empty($datos['infoTributaria']['contribuyenteRimpe'])) {
            $this->addChildText($infoTributaria, 'contribuyenteRimpe', $datos['infoTributaria']['contribuyenteRimpe']);
        }

        /*
    |--------------------------------------------------------------------------
    | infoLiquidacionCompra
    |--------------------------------------------------------------------------
    */
        $infoLiquidacion = $liquidacion->addChild('infoLiquidacionCompra');

        $this->addChildText($infoLiquidacion, 'fechaEmision', $this->fechaSri($datos['infoLiquidacionCompra']['fechaEmision']));

        if (!empty($datos['infoLiquidacionCompra']['dirEstablecimiento'])) {
            $this->addChildText($infoLiquidacion, 'dirEstablecimiento', $datos['infoLiquidacionCompra']['dirEstablecimiento']);
        }

        if (!empty($datos['infoLiquidacionCompra']['contribuyenteEspecial'])) {
            $this->addChildText($infoLiquidacion, 'contribuyenteEspecial', $datos['infoLiquidacionCompra']['contribuyenteEspecial']);
        }

        if (!empty($datos['infoLiquidacionCompra']['obligadoContabilidad'])) {
            $this->addChildText($infoLiquidacion, 'obligadoContabilidad', $datos['infoLiquidacionCompra']['obligadoContabilidad']);
        }

        $this->addChildText($infoLiquidacion, 'tipoIdentificacionProveedor', $datos['infoLiquidacionCompra']['tipoIdentificacionProveedor']);
        $this->addChildText($infoLiquidacion, 'razonSocialProveedor', $datos['infoLiquidacionCompra']['razonSocialProveedor']);
        $this->addChildText($infoLiquidacion, 'identificacionProveedor', $datos['infoLiquidacionCompra']['identificacionProveedor']);

        if (!empty($datos['infoLiquidacionCompra']['direccionProveedor'])) {
            $this->addChildText($infoLiquidacion, 'direccionProveedor', $datos['infoLiquidacionCompra']['direccionProveedor']);
        }

        $this->addChildText($infoLiquidacion, 'totalSinImpuestos', $this->numeroSri($datos['infoLiquidacionCompra']['totalSinImpuestos'], 2));
        $this->addChildText($infoLiquidacion, 'totalDescuento', $this->numeroSri($datos['infoLiquidacionCompra']['totalDescuento'] ?? 0, 2));

        /*
    |--------------------------------------------------------------------------
    | Reembolso - opcional
    |--------------------------------------------------------------------------
    | Úsalo solo cuando corresponda.
    */
        if (!empty($datos['infoLiquidacionCompra']['codDocReembolso'])) {
            $this->addChildText($infoLiquidacion, 'codDocReembolso', $datos['infoLiquidacionCompra']['codDocReembolso']);

            if (!empty($datos['infoLiquidacionCompra']['totalComprobantesReembolso'])) {
                $this->addChildText($infoLiquidacion, 'totalComprobantesReembolso', $this->numeroSri($datos['infoLiquidacionCompra']['totalComprobantesReembolso'], 2));
            }

            if (!empty($datos['infoLiquidacionCompra']['totalBaseImponibleReembolso'])) {
                $this->addChildText($infoLiquidacion, 'totalBaseImponibleReembolso', $this->numeroSri($datos['infoLiquidacionCompra']['totalBaseImponibleReembolso'], 2));
            }

            if (!empty($datos['infoLiquidacionCompra']['totalImpuestoReembolso'])) {
                $this->addChildText($infoLiquidacion, 'totalImpuestoReembolso', $this->numeroSri($datos['infoLiquidacionCompra']['totalImpuestoReembolso'], 2));
            }
        }

        /*
    |--------------------------------------------------------------------------
    | totalConImpuestos
    |--------------------------------------------------------------------------
    */
        $totalConImpuestos = $infoLiquidacion->addChild('totalConImpuestos');

        foreach ($datos['infoLiquidacionCompra']['totalConImpuestos'] as $impuestoData) {
            $totalImpuesto = $totalConImpuestos->addChild('totalImpuesto');

            $this->addChildText($totalImpuesto, 'codigo', $impuestoData['codigo']);
            $this->addChildText($totalImpuesto, 'codigoPorcentaje', $impuestoData['codigoPorcentaje']);

            if (isset($impuestoData['descuentoAdicional'])) {
                $this->addChildText($totalImpuesto, 'descuentoAdicional', $this->numeroSri($impuestoData['descuentoAdicional'], 2));
            }

            $this->addChildText($totalImpuesto, 'baseImponible', $this->numeroSri($impuestoData['baseImponible'], 2));

            if (isset($impuestoData['tarifa'])) {
                $this->addChildText($totalImpuesto, 'tarifa', $this->numeroSri($impuestoData['tarifa'], 2));
            }

            $this->addChildText($totalImpuesto, 'valor', $this->numeroSri($impuestoData['valor'], 2));
        }

        $this->addChildText($infoLiquidacion, 'importeTotal', $this->numeroSri($datos['infoLiquidacionCompra']['importeTotal'], 2));
        $this->addChildText($infoLiquidacion, 'moneda', $datos['infoLiquidacionCompra']['moneda'] ?? 'DOLAR');

        /*
    |--------------------------------------------------------------------------
    | pagos
    |--------------------------------------------------------------------------
    */
        $pagos = $infoLiquidacion->addChild('pagos');

        foreach ($datos['infoLiquidacionCompra']['pagos'] as $pagoData) {
            $pago = $pagos->addChild('pago');

            $this->addChildText($pago, 'formaPago', $pagoData['formaPago']);
            $this->addChildText($pago, 'total', $this->numeroSri($pagoData['total'], 2));

            if (isset($pagoData['plazo'])) {
                $this->addChildText($pago, 'plazo', $pagoData['plazo']);
            }

            if (!empty($pagoData['unidadTiempo'])) {
                $this->addChildText($pago, 'unidadTiempo', $pagoData['unidadTiempo']);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | detalles
    |--------------------------------------------------------------------------
    */
        $detalles = $liquidacion->addChild('detalles');

        foreach ($datos['detalles'] as $detalleData) {
            $detalle = $detalles->addChild('detalle');
            $this->addChildText($detalle, 'codigoPrincipal', $detalleData['codigoPrincipal']);
            if (!empty($detalleData['codigoAuxiliar'])) {
                $this->addChildText($detalle, 'codigoAuxiliar', $detalleData['codigoAuxiliar']);
            }
            $this->addChildText($detalle, 'descripcion', $detalleData['descripcion']);
            if (!empty($detalleData['unidadMedida'])) {
                $this->addChildText($detalle, 'unidadMedida', $detalleData['unidadMedida']);
            }
            $this->addChildText($detalle, 'cantidad', $this->numeroSri($detalleData['cantidad'], 6));
            $this->addChildText($detalle, 'precioUnitario', $this->numeroSri($detalleData['precioUnitario'], 6));
            $this->addChildText($detalle, 'descuento', $this->numeroSri($detalleData['descuento'] ?? 0, 2));
            $this->addChildText($detalle, 'precioTotalSinImpuesto', $this->numeroSri($detalleData['precioTotalSinImpuesto'], 2));
            /*
        |--------------------------------------------------------------------------
        | detallesAdicionales - opcional
        |--------------------------------------------------------------------------
        */
            if (!empty($detalleData['detallesAdicionales']) && is_array($detalleData['detallesAdicionales'])) {
                $detallesAdicionales = $detalle->addChild('detallesAdicionales');

                foreach ($detalleData['detallesAdicionales'] as $detAdicionalData) {
                    if (empty($detAdicionalData['nombre']) || empty($detAdicionalData['valor'])) {
                        continue;
                    }

                    $detAdicional = $detallesAdicionales->addChild('detAdicional');
                    $detAdicional->addAttribute('nombre', $this->limpiarTexto($detAdicionalData['nombre']));
                    $detAdicional->addAttribute('valor', $this->limpiarTexto($detAdicionalData['valor']));
                }
            }

            /*
        |--------------------------------------------------------------------------
        | impuestos detalle
        |--------------------------------------------------------------------------
        */
            $impuestos = $detalle->addChild('impuestos');

            foreach ($detalleData['impuestos'] as $impuestoData) {
                $impuesto = $impuestos->addChild('impuesto');

                $this->addChildText($impuesto, 'codigo', $impuestoData['codigo']);
                $this->addChildText($impuesto, 'codigoPorcentaje', $impuestoData['codigoPorcentaje']);
                $this->addChildText($impuesto, 'tarifa', $this->numeroSri($impuestoData['tarifa'], 2));
                $this->addChildText($impuesto, 'baseImponible', $this->numeroSri($impuestoData['baseImponible'], 2));
                $this->addChildText($impuesto, 'valor', $this->numeroSri($impuestoData['valor'], 2));
            }
        }

        /*
    |--------------------------------------------------------------------------
    | reembolsos - opcional
    |--------------------------------------------------------------------------
    */
        if (!empty($datos['reembolsos']) && is_array($datos['reembolsos'])) {
            $reembolsos = $liquidacion->addChild('reembolsos');

            foreach ($datos['reembolsos'] as $reembolsoData) {
                $reembolsoDetalle = $reembolsos->addChild('reembolsoDetalle');

                $this->addChildText($reembolsoDetalle, 'tipoIdentificacionProveedorReembolso', $reembolsoData['tipoIdentificacionProveedorReembolso']);
                $this->addChildText($reembolsoDetalle, 'identificacionProveedorReembolso', $reembolsoData['identificacionProveedorReembolso']);

                if (!empty($reembolsoData['codPaisPagoProveedorReembolso'])) {
                    $this->addChildText($reembolsoDetalle, 'codPaisPagoProveedorReembolso', $reembolsoData['codPaisPagoProveedorReembolso']);
                }

                $this->addChildText($reembolsoDetalle, 'tipoProveedorReembolso', $reembolsoData['tipoProveedorReembolso']);
                $this->addChildText($reembolsoDetalle, 'codDocReembolso', $reembolsoData['codDocReembolso']);
                $this->addChildText($reembolsoDetalle, 'estabDocReembolso', $reembolsoData['estabDocReembolso']);
                $this->addChildText($reembolsoDetalle, 'ptoEmiDocReembolso', $reembolsoData['ptoEmiDocReembolso']);
                $this->addChildText($reembolsoDetalle, 'secuencialDocReembolso', $reembolsoData['secuencialDocReembolso']);
                $this->addChildText($reembolsoDetalle, 'fechaEmisionDocReembolso', $this->fechaSri($reembolsoData['fechaEmisionDocReembolso']));
                $this->addChildText($reembolsoDetalle, 'numeroautorizacionDocReemb', $reembolsoData['numeroautorizacionDocReemb']);

                $detalleImpuestos = $reembolsoDetalle->addChild('detalleImpuestos');

                foreach ($reembolsoData['detalleImpuestos'] as $impuestoReembolsoData) {
                    $detalleImpuesto = $detalleImpuestos->addChild('detalleImpuesto');

                    $this->addChildText($detalleImpuesto, 'codigo', $impuestoReembolsoData['codigo']);
                    $this->addChildText($detalleImpuesto, 'codigoPorcentaje', $impuestoReembolsoData['codigoPorcentaje']);
                    $this->addChildText($detalleImpuesto, 'tarifa', $this->numeroSri($impuestoReembolsoData['tarifa'], 2));
                    $this->addChildText($detalleImpuesto, 'baseImponibleReembolso', $this->numeroSri($impuestoReembolsoData['baseImponibleReembolso'], 2));
                    $this->addChildText($detalleImpuesto, 'impuestoReembolso', $this->numeroSri($impuestoReembolsoData['impuestoReembolso'], 2));
                }
            }
        }

        /*
    |--------------------------------------------------------------------------
    | maquinaFiscal - opcional
    |--------------------------------------------------------------------------
    */
        if (!empty($datos['maquinaFiscal']) && is_array($datos['maquinaFiscal'])) {
            $maquinaFiscal = $liquidacion->addChild('maquinaFiscal');

            if (!empty($datos['maquinaFiscal']['marca'])) {
                $this->addChildText($maquinaFiscal, 'marca', $datos['maquinaFiscal']['marca']);
            }

            if (!empty($datos['maquinaFiscal']['modelo'])) {
                $this->addChildText($maquinaFiscal, 'modelo', $datos['maquinaFiscal']['modelo']);
            }

            if (!empty($datos['maquinaFiscal']['serie'])) {
                $this->addChildText($maquinaFiscal, 'serie', $datos['maquinaFiscal']['serie']);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | infoAdicional
    |--------------------------------------------------------------------------
    */
        if (!empty($datos['infoAdicional']) && is_array($datos['infoAdicional'])) {
            $infoAdicional = $liquidacion->addChild('infoAdicional');

            foreach ($datos['infoAdicional'] as $campo) {
                if (empty($campo['nombre']) || empty($campo['valor'])) {
                    continue;
                }

                $campoAdicional = $infoAdicional->addChild('campoAdicional');
                $campoAdicional->addAttribute('nombre', $this->limpiarTexto($campo['nombre']));

                $node = dom_import_simplexml($campoAdicional);
                $node->appendChild(
                    $node->ownerDocument->createTextNode($this->limpiarTexto($campo['valor']))
                );
            }
        }

        return $this->formatXml($liquidacion->asXML());
    }
    private function addChildText(\SimpleXMLElement $parent, string $name, $value): \SimpleXMLElement
    {
        $child = $parent->addChild($name);

        $node = dom_import_simplexml($child);
        $node->appendChild(
            $node->ownerDocument->createTextNode($this->limpiarTexto($value))
        );

        return $child;
    }
    private function limpiarTexto($texto): string
    {
        $texto = (string) ($texto ?? '');

        $texto = trim($texto);

        // Evita caracteres raros que suelen romper XML o XSD
        $texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $texto);

        return $texto;
    }
    private function fechaSri($fecha): string
    {
        if (empty($fecha)) {
            return '';
        }

        return date('d/m/Y', strtotime($fecha));
    }
    private function numeroSri($valor, int $decimales = 2): string
    {
        return number_format((float) ($valor ?? 0), $decimales, '.', '');
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
