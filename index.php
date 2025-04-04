<?php
echo 1;exit;
require __DIR__ . '/vendor/autoload.php'; // Cargar dependencias de Composer

use SRI\FirmaElectronica;
use SRI\XmlGenerator;

$generator = new XmlGenerator();

$generator->setInfoTributaria([
    'ambiente' => '2',
    'tipoEmision' => '1',
    'razonSocial' => 'Contimax Sas',
    'nombreComercial' => 'Contimax Sas',
    'ruc' => '0993379687001',
    'claveAcceso' => '3112202401099337968700120010010000001740000017419',
    'codDoc' => '01',
    'estab' => '001',
    'ptoEmi' => '001',
    'secuencial' => '000000174',
    'dirMatriz' => 'Urbanizacion Jardines Del Rio, Solar Seis Avenida Narcisa De Jesus # 4430, Franco Davila Y 6 De Marzo'
]);
$generator->setInfoFactura([
    'fechaEmision' => '31/12/2024',
    'dirEstablecimiento' => 'URBANIZACION JARDINES DEL RIO, SOLAR SEIS AVENIDA NARCISA DE JESUS # 4430, FRANCO DAVILA Y 6 DE MARZO',
    'obligadoContabilidad' => 'NO',
    'tipoIdentificacionComprador' => '04',
    'razonSocialComprador' => 'ROMAGNAREST S.A.S.',
    'identificacionComprador' => '0993379467001',
    'direccionComprador' => 'cc jardines plaza local 7',
    'totalSinImpuestos' => '300.00',
    'totalDescuento' => '0.00',
    'importeTotal' => '345.00',
    'moneda' => 'DOLAR'
]);

$generator->addDetalle([
    'codigoPrincipal' => '000001',
    'codigoAuxiliar' => '000001',
    'descripcion' => 'SERVICIOS PRESTADOS',
    'cantidad' => '1.00',
    'precioUnitario' => '300.00',
    'descuento' => '0.00',
    'precioTotalSinImpuesto' => '300.00'
]);

$generator->addCampoAdicional('Cliente', 'ROMAGNAREST S.A.S.');
$generator->addCampoAdicional('Email', 'ROMAGNATRATORIA@GMAIL.COM');

/*
$archivoP12 = 'tokens/Contimax.p12';
$password = 'Karla2025';
try {
    $firma = new FirmaElectronica($archivoP12, $password);
    echo '<pre>';
    print_r($firma);
    exit;
} catch (Exception $e) {
    echo 'Error al instanciar la clase FirmaElectronica: ' . $e->getMessage();
    exit;
}
$xmlFirmado = $firma->firmarXML($xmlSinFirmar);

file_put_contents('factura_firmada.xml', $xmlFirmado);*/

header('Content-Type: text/xml');
echo $generator->getXml();
