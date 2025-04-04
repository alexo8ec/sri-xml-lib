<?php
require __DIR__ . '/vendor/autoload.php'; // Cargar dependencias de Composer

use SRI\FirmaElectronica;
use SRI\SriClient;
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


$archivoP12 = 'tokens/Contimax.p12';
$password = 'Karla2025';
try {
    $firma = new FirmaElectronica($archivoP12, $password);
} catch (Exception $e) {
    echo 'Error al instanciar la clase FirmaElectronica: ' . $e->getMessage();
}
$xmlSinFirmar = $generator->getXml();
$xmlFirmado = $firma->firmarXML($xmlSinFirmar);

/*$xmlFirmado = '<?xml version="1.0" encoding="UTF-8"?><factura id="comprobante" version="1.1.0">
  <infoTributaria>
    <ambiente>2</ambiente>
    <tipoEmision>1</tipoEmision>
    <razonSocial>Contimax Sas</razonSocial>
    <nombreComercial>Contimax Sas</nombreComercial>
    <ruc>0993379687001</ruc>
    <claveAcceso>3112202401099337968700120010010000001740000017419</claveAcceso>
    <codDoc>01</codDoc>
    <estab>001</estab>
    <ptoEmi>001</ptoEmi>
    <secuencial>000000174</secuencial>
    <dirMatriz>Urbanizacion Jardines Del Rio, Solar Seis Avenida Narcisa De Jesus # 4430, Franco Davila Y 6 De Marzo</dirMatriz>
  </infoTributaria>
  <infoFactura>
    <fechaEmision>31/12/2024</fechaEmision>
    <dirEstablecimiento>URBANIZACION JARDINES DEL RIO, SOLAR SEIS AVENIDA NARCISA DE JESUS # 4430, FRANCO DAVILA Y 6 DE MARZO</dirEstablecimiento>
    <obligadoContabilidad>NO</obligadoContabilidad>
    <tipoIdentificacionComprador>04</tipoIdentificacionComprador>
    <razonSocialComprador>ROMAGNAREST  S.A.S.</razonSocialComprador>
    <identificacionComprador>0993379467001</identificacionComprador>
    <direccionComprador>cc jardines plaza local 7</direccionComprador>
    <totalSinImpuestos>300.00</totalSinImpuestos>
    <totalDescuento>0.00</totalDescuento>
    <totalConImpuestos>
      <totalImpuesto>
        <codigo>2</codigo>
        <codigoPorcentaje>4</codigoPorcentaje>
        <baseImponible>300.00</baseImponible>
        <valor>45.00</valor>
      </totalImpuesto>
    </totalConImpuestos>
    <propina>0.00</propina>
    <importeTotal>345.00</importeTotal>
    <moneda>DOLAR</moneda>
    <pagos>
      <pago>
        <formaPago>20</formaPago>
        <total>345.00</total>
        <plazo>0</plazo>
        <unidadTiempo>dias</unidadTiempo>
      </pago>
    </pagos>
  </infoFactura>
  <detalles>
    <detalle>
      <codigoPrincipal>000001</codigoPrincipal>
      <codigoAuxiliar>000001</codigoAuxiliar>
      <descripcion>SERVICIOS PRESTADOS</descripcion>
      <cantidad>1.00</cantidad>
      <precioUnitario>300.00</precioUnitario>
      <descuento>0.00</descuento>
      <precioTotalSinImpuesto>300.00</precioTotalSinImpuesto>
      <detallesAdicionales>
        <detAdicional nombre="romagnarest  s.a.s." valor="CC JARDINES PLAZA LOCAL 7"/>
      </detallesAdicionales>
      <impuestos>
        <impuesto>
          <codigo>2</codigo>
          <codigoPorcentaje>4</codigoPorcentaje>
          <tarifa>15.00</tarifa>
          <baseImponible>300.00</baseImponible>
          <valor>45.00</valor>
        </impuesto>
      </impuestos>
    </detalle>
  </detalles>
  <infoAdicional>
    <campoAdicional nombre="Cliente">ROMAGNAREST  S.A.S.</campoAdicional>
    <campoAdicional nombre="Email">ROMAGNATRATORIA@GMAIL.COM</campoAdicional>
  </infoAdicional>
<ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:etsi="http://uri.etsi.org/01903/v1.3.2#" Id="Signature836068">
<ds:SignedInfo Id="Signature-SignedInfo962525">
<ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
<ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
<ds:Reference Id="SignedPropertiesID987901" Type="http://uri.etsi.org/01903#SignedProperties" URI="#Signature836068-SignedProperties137033">
<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
<ds:DigestValue>qKQMD3T/KXJYQ9ObbXlj9Xc1Gm4=</ds:DigestValue>
</ds:Reference>
<ds:Reference URI="#Certificate1819337">
<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
<ds:DigestValue>9UErmwJESVxHTB/Dz64eEs41UGU=</ds:DigestValue>
</ds:Reference>
<ds:Reference Id="Reference-ID-828053" URI="#comprobante">
<ds:Transforms>
<ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
</ds:Transforms>
<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
<ds:DigestValue>bQ81jRf3ZkhihEOQwMN1Qw58uXY=</ds:DigestValue>
</ds:Reference>
</ds:SignedInfo>
<ds:SignatureValue Id="SignatureValue442868">
RLQRZTXo+hv0nulFbPdSyY7Ny+ADULKqH/XUwW36v9ykdRM+Y5ChQs19rplw9zWmiReZUqIENQ3U
SS2j+nIpqDmhT7ucGs+OE5DwN4D1AIB4PZxV8hlX5KsU0hfGWMz8u+xe2zfKZWETSdRA6CSONzpi
tNuuU0twYy7bFmaQcm945A5a8N+pRm+6uQEWpR66sbLU2UYCTJ2G4KjwvHd3s1tNHmRfb8x7bfKH
kzbucoyNHgCzOIWOFoaAofKZD/Hy5jmq3A8cnbpdmFy9ivn/VvYXPUThdXTgyoNIq8sY/e+y9j+b
ILsNG3zrKVzitDRMauhBvRuWDO/E/oMnfM7rVQ==
</ds:SignatureValue>
<ds:KeyInfo Id="Certificate1819337">
<ds:X509Data>
<ds:X509Certificate>
MIIMIDCCCgigAwIBAgIEDF9FOjANBgkqhkiG9w0BAQsFADCBmTELMAkGA1UEBhMCRUMxHTAbBgNV
BAoMFFNFQ1VSSVRZIERBVEEgUy5BLiAyMTAwLgYDVQQLDCdFTlRJREFEIERFIENFUlRJRklDQUNJ
T04gREUgSU5GT1JNQUNJT04xOTA3BgNVBAMMMEFVVE9SSURBRCBERSBDRVJUSUZJQ0FDSU9OIFNV
QkNBLTIgU0VDVVJJVFkgREFUQTAeFw0yMzAzMjMxOTA0MDlaFw0yNTAzMjIxOTA0MDlaMIGeMScw
JQYDVQQDDB5UQVRJQU5BIEtBUkxBIENPUlJBTEVTIFJBTUlSRVoxFTATBgNVBAUTDDIzMDMyMzE0
MTM0NDEwMC4GA1UECwwnRU5USURBRCBERSBDRVJUSUZJQ0FDSU9OIERFIElORk9STUFDSU9OMR0w
GwYDVQQKDBRTRUNVUklUWSBEQVRBIFMuQS4gMjELMAkGA1UEBhMCRUMwggEiMA0GCSqGSIb3DQEB
AQUAA4IBDwAwggEKAoIBAQDDUN+n/QfH6ECIi0Ovk6/CAJt4+mm5mxB4ENRArfJPjdYVbZ485Dcy
xWlkIp72OuWZcwHwKpPsa5tzeB6oMY3EGpntkUKYtq4yZwnJqnuADuvdopVxCe8EibcXHeD31l9d
dUrVm6GRN/MbgPapDjAMGFQNGFGbe2IA3DOl2mtv+yIdksWMvYeZru9n55taLD3Gj0z44HHpF6zD
jlkJo4Xz54q0R4c9I7P2WF1gY59ukpCXgrz6AgjXrTHDyKJxa+yS82eet/V0/YhDlPzix4pA5MTt
jFRGRR5RaOD9DEV9aOwFlgyIxBj3IXYsvh/f6F/xuBSWEtFFTiy2lkO2SGXJAgMBAAGjggdnMIIH
YzAMBgNVHRMBAf8EAjAAMB8GA1UdIwQYMBaAFIy6yhFXeCWAHWsKS1W/ja5i3b2PMFkGCCsGAQUF
BwEBBE0wSzBJBggrBgEFBQcwAYY9aHR0cDovL29jc3Bndy5zZWN1cml0eWRhdGEubmV0LmVjL2Vq
YmNhL3B1YmxpY3dlYi9zdGF0dXMvb2NzcDCBzwYDVR0uBIHHMIHEMIHBoIG+oIG7hoG4bGRhcDov
L2xkYXBzZGNhMi5zZWN1cml0eWRhdGEubmV0LmVjL0NOPUFVVE9SSURBRCBERSBDRVJUSUZJQ0FD
SU9OIFNVQkNBLTIgU0VDVVJJVFkgREFUQSxPVT1FTlRJREFEIERFIENFUlRJRklDQUNJT04gREUg
SU5GT1JNQUNJT04sTz1TRUNVUklUWSBEQVRBIFMuQS4gMixDPUVDP2RlbHRhUmV2b2NhdGlvbkxp
c3Q/YmFzZTAdBgNVHREEFjAUgRJ0a2NyMTk4NUBnbWFpbC5jb20wggEVBgNVHSAEggEMMIIBCDBi
BgorBgEEAYKmcgIJMFQwUgYIKwYBBQUHAgIwRh5EAEMAZQByAHQAaQBmAGkAYwBhAGQAbwAgAGQA
ZQAgAFIAZQBwAHIAZQBzAGUAbgB0AGEAbgB0AGUAIABMAGUAZwBhAGwwgaEGCisGAQQBgqZyAgMw
gZIwgY8GCCsGAQUFBwIBFoGCaHR0cHM6Ly93d3cuc2VjdXJpdHlkYXRhLm5ldC5lYy93cC1jb250
ZW50L2Rvd25sb2Fkcy9Ob3JtYXRpdmFzL1BfZGVfQ2VydGlmaWNhZG9zL1BvbGl0aWNhcyBkZSBD
ZXJ0aWZpY2FkbyBSZXByZXNlbnRhbnRlIExlZ2FsLnBkZjCCAqIGA1UdHwSCApkwggKVMIHloEGg
P4Y9aHR0cDovL29jc3Bndy5zZWN1cml0eWRhdGEubmV0LmVjL2VqYmNhL3B1YmxpY3dlYi9zdGF0
dXMvb2NzcKKBn6SBnDCBmTE5MDcGA1UEAwwwQVVUT1JJREFEIERFIENFUlRJRklDQUNJT04gU1VC
Q0EtMiBTRUNVUklUWSBEQVRBMTAwLgYDVQQLDCdFTlRJREFEIERFIENFUlRJRklDQUNJT04gREUg
SU5GT1JNQUNJT04xHTAbBgNVBAoMFFNFQ1VSSVRZIERBVEEgUy5BLiAyMQswCQYDVQQGEwJFQzCB
x6CBxKCBwYaBvmxkYXA6Ly9sZGFwc2RjYTIuc2VjdXJpdHlkYXRhLm5ldC5lYy9DTj1BVVRPUklE
QUQgREUgQ0VSVElGSUNBQ0lPTiBTVUJDQS0yIFNFQ1VSSVRZIERBVEEsT1U9RU5USURBRCBERSBD
RVJUSUZJQ0FDSU9OIERFIElORk9STUFDSU9OLE89U0VDVVJJVFkgREFUQSBTLkEuIDIsQz1FQz9j
ZXJ0aWZpY2F0ZVJldm9jYXRpb25MaXN0P2Jhc2UwgeCggd2ggdqGgddodHRwczovL3BvcnRhbC1v
cGVyYWRvcjIuc2VjdXJpdHlkYXRhLm5ldC5lYy9lamJjYS9wdWJsaWN3ZWIvd2ViZGlzdC9jZXJ0
ZGlzdD9jbWQ9Y3JsJmlzc3Vlcj1DTj1BVVRPUklEQUQgREUgQ0VSVElGSUNBQ0lPTiBTVUJDQS0y
IFNFQ1VSSVRZIERBVEEsT1U9RU5USURBRCBERSBDRVJUSUZJQ0FDSU9OIERFIElORk9STUFDSU9O
LE89U0VDVVJJVFkgREFUQSBTLkEuIDIsQz1FQzAdBgNVHQ4EFgQUC62zziLnVWCEVwrz+WBrQcCT
4WswKwYDVR0QBCQwIoAPMjAyMzAzMjMxOTA0MDlagQ8yMDI1MDMyMjE5MDQwOVowCwYDVR0PBAQD
AgXgMB8GCisGAQQBgqZyAwUEEQwPR0VSRU5URSBHRU5FUkFMMBoGCisGAQQBgqZyAwEEDAwKMDky
NDI1ODg1ODAZBgorBgEEAYKmcgMJBAsMCUdVQVlBUVVJTDARBgorBgEEAYKmcgMiBAMMAS4wUwYK
KwYBBAGCpnIDBwRFDENVUkJBTklaQUNJT04gSkFSRElORVMgREVMIFJJTyBZIFNPTEFSIFNFSVMg
QVZFTklEQSBOQVJDSVNBIERFIEpFU1VTMB0GCisGAQQBgqZyAwIEDwwNVEFUSUFOQSBLQVJMQTAf
BgorBgEEAYKmcgMgBBEMDzAwMTAwMjAwMDM3MzI5MTATBgorBgEEAYKmcgMhBAUMA1BGWDAXBgor
BgEEAYKmcgMMBAkMB0VDVUFET1IwGAYKKwYBBAGCpnIDAwQKDAhDT1JSQUxFUzAeBgorBgEEAYKm
cgMKBBAMDkNPTlRJTUFYIFMgQSBTMB0GCisGAQQBgqZyAwsEDwwNMDk5MzM3OTY4NzAwMTASBgor
BgEEAYKmcgMdBAQMAk5vMBcGCisGAQQBgqZyAwQECQwHUkFNSVJFWjAaBgorBgEEAYKmcgMIBAwM
CjA5OTAxNjAyNjkwDQYJKoZIhvcNAQELBQADggIBAKMCvCeIlsYbhu+Ss9rjy5WkRGI/Vr/QR/S2
r7KPKoX8wTvJRMmnDT4eTWagOtFtk5jD7b9MDsSTnSh5mqgtgr7SUSFM34wBhuE0Nwn5GfEyBb0H
zet+mHSIDMx1qs+aICNv9UmrZSDN5WwPe7ZXHxafG2xwlQ9iRAuM4FRxzKqAmEuad02kAjdnajiL
/hte9+SwB5TFExTwk0nfNe+uClmTQ4bY+FjUiz+xGnO9CYlgeBQHNsnTU9XkERzVb35Q00R+lZpi
gtnhLKomBwLyskbXRgI1lZpn+gfkmstwnAJ9qJ2LxXwq54bC7WijUHnX5ijlpyRDmhcTnayfZicK
30CNf0MIEPJIXj2eb7y3+tDJ+WqRTTClUrtpnCPtlnXq+XhMIE0SVxKarkMQiVtbpsdsI10eaq90
DjyVWyeTLPygvz07E6214xlXHbvCyoAWipzx6gtbEzTcqieHQgunWbnO4gLiJcMNKMsbNWRxGWbU
YCehj684AGHxFt1ASe2UZ5nAa82bHlAW3nwzRfEaXp2x4Jo80EdeEI26IFCyDgjVuKjB+deLCeZR
MwW/RxgxcLNraMIrNq7F6I/h3jAOaDAFnU77hZD6dPgHSDJxWmw9nvTBTTmi8Oq8iRR29/yiajep
ojPHcVNjuo7mDB9zsQRwmNsrDlnHNjaXqETTaLao
</ds:X509Certificate>
</ds:X509Data>
<ds:KeyValue>
<ds:RSAKeyValue>
<ds:Modulus>
w1Dfp/0Hx+hAiItDr5OvwgCbePppuZsQeBDUQK3yT43WFW2ePOQ3MsVpZCKe9jrlmXMB8CqT7Gub
c3geqDGNxBqZ7ZFCmLauMmcJyap7gA7r3aKVcQnvBIm3Fx3g99ZfXXVK1ZuhkTfzG4D2qQ4wDBhU
DRhRm3tiANwzpdprb/siHZLFjL2Hma7vZ+ebWiw9xo9M+OBx6Resw45ZCaOF8+eKtEeHPSOz9lhd
YGOfbpKQl4K8+gII160xw8iicWvskvNnnrf1dP2IQ5T84seKQOTE7YxURkUeUWjg/QxFfWjsBZYM
iMQY9yF2LL4f3+hf8bgUlhLRRU4stpZDtkhlyQ==
</ds:Modulus>
<ds:Exponent>AQAB</ds:Exponent>
</ds:RSAKeyValue>
</ds:KeyValue>
</ds:KeyInfo>
<ds:Object Id="Signature836068-Object18454"><etsi:QualifyingProperties Target="#Signature836068"><etsi:SignedProperties Id="Signature836068-SignedProperties137033"><etsi:SignedSignatureProperties><etsi:SigningTime>2024-12-31T23:54:44-05:00</etsi:SigningTime><etsi:SigningCertificate><etsi:Cert><etsi:CertDigest><ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><ds:DigestValue>8S7WgUiZIuIkjLnWmcDzCvFlU+U=</ds:DigestValue></etsi:CertDigest><etsi:IssuerSerial><ds:X509IssuerName>CN=AUTORIDAD DE CERTIFICACION SUBCA-2 SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A. 2,C=EC</ds:X509IssuerName><ds:X509SerialNumber>207570234</ds:X509SerialNumber></etsi:IssuerSerial></etsi:Cert></etsi:SigningCertificate></etsi:SignedSignatureProperties><etsi:SignedDataObjectProperties><etsi:DataObjectFormat ObjectReference="#Reference-ID-828053"><etsi:Description>compel</etsi:Description><etsi:MimeType>text/xml</etsi:MimeType></etsi:DataObjectFormat></etsi:SignedDataObjectProperties></etsi:SignedProperties></etsi:QualifyingProperties></ds:Object></ds:Signature></factura>
';*/

$sriCliente = new SriClient;
$resultado = $sriCliente->enviarSRI($xmlFirmado);
echo '<pre>';
print_r($resultado);
exit;

file_put_contents('factura_firmada.xml', $xmlFirmado);

header('Content-Type: text/xml');
echo $xmlFirmado;
