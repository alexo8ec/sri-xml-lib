<?php

namespace SRI;

use DateTime;
use DateTimeZone;
use Exception;
use DOMDocument;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class FirmaElectronica
{
    private $certificado;
    private $clave;

    public function __construct($archivoP12, $password)
    {
        $this->cargarCertificado($archivoP12, $password);
    }

    private function cargarCertificado($archivoP12, $password)
    {
        $contenidoP12 = file_get_contents($archivoP12);
        $certificados = [];

        try {
            if (!openssl_pkcs12_read($contenidoP12, $certificados, $password)) {
                //throw new Exception("Error al leer el certificado P12.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $this->certificado = $certificados['cert'];
        $this->clave = $certificados['pkey'];
    }

    public function firmarXML($xmlString, $archivoP12, $password)
    {
        $xmlPath = $xmlString;
        $p12File = $archivoP12;
        $p12Password = $password;

        if (!openssl_pkcs12_read(file_get_contents($p12File), $certs, $p12Password)) {
            throw new Exception("Error al leer el certificado P12");
        }

        $certificado = $certs['cert'];
        $clavePrivada = $certs['pkey'];
        $certParsed = openssl_x509_parse($certificado);
        $certSerialNumber = $certParsed['serialNumber'];
        $issuer = $certParsed['issuer'];
        $issuerDN = "CN={$issuer['CN']},OU={$issuer['OU']},O={$issuer['O']},C={$issuer['C']}";

        $certClean = str_replace(["-----BEGIN CERTIFICATE-----", "-----END CERTIFICATE-----", "\r", "\n"], '', $certificado);
        $certBin = base64_decode($certClean);
        $digestCert = base64_encode(sha1($certBin, true));

        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXml($xmlPath);

        $idFirma = 'Signature' . rand(100000, 999999);
        $idSignedProps = $idFirma . '-SignedProperties' . rand(100000, 999999);
        $idReferenceComprobante = 'Reference-ID-' . rand(100000, 999999);
        $idKeyInfo = 'Certificate' . rand(1000000, 9999999);
        $idObject = $idFirma . '-Object' . rand(100000, 999999);
        $fechaFirma = (new DateTime('now', new DateTimeZone('America/Guayaquil')))->format('Y-m-d\TH:i:sP');

        $comprobante = $doc->getElementsByTagName('*')->item(0);
        $comprobanteClone = $comprobante->cloneNode(true);
        $tempDOM = new DOMDocument('1.0', 'UTF-8');
        $tempDOM->appendChild($tempDOM->importNode($comprobanteClone, true));
        $canonical = $tempDOM->C14N();
        $digestComprobante = base64_encode(sha1($canonical, true));

        $signedPropsXml = <<<XML
    <etsi:SignedProperties xmlns:etsi="http://uri.etsi.org/01903/v1.3.2#" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="$idSignedProps">
        <etsi:SignedSignatureProperties>
            <etsi:SigningTime>$fechaFirma</etsi:SigningTime>
            <etsi:SigningCertificate>
                <etsi:Cert>
                    <etsi:CertDigest>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>$digestCert</ds:DigestValue>
                    </etsi:CertDigest>
                    <etsi:IssuerSerial>
                        <ds:X509IssuerName>$issuerDN</ds:X509IssuerName>
                        <ds:X509SerialNumber>$certSerialNumber</ds:X509SerialNumber>
                    </etsi:IssuerSerial>
                </etsi:Cert>
            </etsi:SigningCertificate>
        </etsi:SignedSignatureProperties>
        <etsi:SignedDataObjectProperties>
            <etsi:DataObjectFormat ObjectReference="#$idReferenceComprobante">
                <etsi:Description>compel</etsi:Description>
                <etsi:MimeType>text/xml</etsi:MimeType>
            </etsi:DataObjectFormat>
        </etsi:SignedDataObjectProperties>
    </etsi:SignedProperties>
    XML;

        $tempDOM2 = new DOMDocument();
        $tempDOM2->loadXML($signedPropsXml);
        $canonicalSignedProps = $tempDOM2->C14N();
        $digestSignedProps = base64_encode(sha1($canonicalSignedProps, true));

        // CREAR NODO FIRMA
        $objDSig = new DOMDocument('1.0', 'UTF-8');
        $signatureNode = $objDSig->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'ds:Signature');
        $signatureNode->setAttribute('Id', $idFirma);

        $signedInfo = $objDSig->createElement('ds:SignedInfo');
        $signedInfo->appendChild($objDSig->createElement('ds:CanonicalizationMethod'))->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
        $signedInfo->appendChild($objDSig->createElement('ds:SignatureMethod'))->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');

        // Referencias
        $ref1 = $objDSig->createElement('ds:Reference');
        $ref1->setAttribute('Id', 'SignedPropertiesID' . rand(100000, 999999));
        $ref1->setAttribute('Type', 'http://uri.etsi.org/01903#SignedProperties');
        $ref1->setAttribute('URI', "#$idSignedProps");
        $ref1->appendChild($objDSig->createElement('ds:DigestMethod'))->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');
        $ref1->appendChild($objDSig->createElement('ds:DigestValue', $digestSignedProps));
        $signedInfo->appendChild($ref1);

        $ref2 = $objDSig->createElement('ds:Reference');
        $ref2->setAttribute('URI', "#$idKeyInfo");
        $ref2->appendChild($objDSig->createElement('ds:DigestMethod'))->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');
        $ref2->appendChild($objDSig->createElement('ds:DigestValue', $digestCert));
        $signedInfo->appendChild($ref2);

        $ref3 = $objDSig->createElement('ds:Reference');
        $ref3->setAttribute('Id', $idReferenceComprobante);
        $ref3->setAttribute('URI', '#comprobante');
        $transforms = $objDSig->createElement('ds:Transforms');
        $transform = $objDSig->createElement('ds:Transform');
        $transform->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');
        $transforms->appendChild($transform);
        $ref3->appendChild($transforms);
        $ref3->appendChild($objDSig->createElement('ds:DigestMethod'))->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');
        $ref3->appendChild($objDSig->createElement('ds:DigestValue', $digestComprobante));
        $signedInfo->appendChild($ref3);

        $signatureNode->appendChild($signedInfo);

        // Firma
        $tempSigned = new DOMDocument();
        $tempSigned->appendChild($tempSigned->importNode($signedInfo, true));
        $canonicalSignedInfo = $tempSigned->C14N();
        openssl_sign($canonicalSignedInfo, $firmaBinaria, $clavePrivada, OPENSSL_ALGO_SHA1);
        $firmaBase64 = base64_encode($firmaBinaria);
        $signatureNode->appendChild($objDSig->createElement('ds:SignatureValue', $firmaBase64));

        // KeyInfo
        $pubKey = openssl_pkey_get_details(openssl_pkey_get_public($certificado));
        $mod = base64_encode($pubKey['rsa']['n']);
        $exp = base64_encode($pubKey['rsa']['e']);
        $keyInfo = $objDSig->createElement('ds:KeyInfo');
        $keyInfo->setAttribute('Id', $idKeyInfo);
        $x509Data = $objDSig->createElement('ds:X509Data');
        $x509Cert = $objDSig->createElement('ds:X509Certificate', chunk_split($certClean, 76, "\n"));
        $x509Data->appendChild($x509Cert);
        $keyInfo->appendChild($x509Data);
        $rsaKeyValue = $objDSig->createElement('ds:KeyValue');
        $rsa = $objDSig->createElement('ds:RSAKeyValue');
        $rsa->appendChild($objDSig->createElement('ds:Modulus', chunk_split($mod, 76, "\n")));
        $rsa->appendChild($objDSig->createElement('ds:Exponent', $exp));
        $rsaKeyValue->appendChild($rsa);
        $keyInfo->appendChild($rsaKeyValue);
        $signatureNode->appendChild($keyInfo);

        // Object con QualifyingProperties como string XML
        $objectXml = <<<XML
        <ds:Object Id="$idObject" xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
          <etsi:QualifyingProperties Target="#$idFirma">
            $signedPropsXml
          </etsi:QualifyingProperties>
        </ds:Object>
        XML;

        $objDOM = new DOMDocument();
        $objDOM->loadXML($objectXml);
        $signatureNode->appendChild($objDSig->importNode($objDOM->documentElement, true));

        // Adjuntar la firma al XML original
        $firmaFinal = $doc->importNode($signatureNode, true);
        $doc->documentElement->appendChild($firmaFinal);

        return $doc->saveXML();
    }
    function crearNodoObjectConQualifyingProperties($doc, $signatureId, $signedPropertiesId, $referenceId, $digestCertBase64, $issuerName, $serialNumber, $signingTime = null)
    {
        $DS = 'http://www.w3.org/2000/09/xmldsig#';
        $XADES = 'http://uri.etsi.org/01903/v1.3.2#';

        // Crear <ds:Object>
        $objectNode = $doc->createElementNS($DS, 'ds:Object');
        $objectId = $signatureId . '-Object' . rand(100000, 999999);
        $objectNode->setAttribute('Id', $objectId);

        // <etsi:QualifyingProperties>
        $qualProps = $doc->createElementNS($XADES, 'etsi:QualifyingProperties');
        $qualProps->setAttribute('Target', '#' . $signatureId);

        // <etsi:SignedProperties>
        $signedProps = $doc->createElementNS($XADES, 'etsi:SignedProperties');
        $signedProps->setAttribute('Id', $signedPropertiesId);

        // <etsi:SignedSignatureProperties>
        $sigSigProps = $doc->createElementNS($XADES, 'etsi:SignedSignatureProperties');

        // <etsi:SigningTime>
        if ($signingTime === null) {
            $signingTime = gmdate('Y-m-d\TH:i:sP'); // Firma en UTC
        }
        $signingTimeNode = $doc->createElementNS($XADES, 'etsi:SigningTime', $signingTime);

        // <etsi:SigningCertificate>
        $signingCert = $doc->createElementNS($XADES, 'etsi:SigningCertificate');
        $cert = $doc->createElementNS($XADES, 'etsi:Cert');

        // <etsi:CertDigest>
        $certDigest = $doc->createElementNS($XADES, 'etsi:CertDigest');
        $digestMethod = $doc->createElementNS($DS, 'ds:DigestMethod');
        $digestMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');
        $digestValue = $doc->createElementNS($DS, 'ds:DigestValue', $digestCertBase64);
        $certDigest->appendChild($digestMethod);
        $certDigest->appendChild($digestValue);

        // <etsi:IssuerSerial>
        $issuerSerial = $doc->createElementNS($XADES, 'etsi:IssuerSerial');
        $issuerNameNode = $doc->createElementNS($DS, 'ds:X509IssuerName', $issuerName);
        $serialNumberNode = $doc->createElementNS($DS, 'ds:X509SerialNumber', $serialNumber);
        $issuerSerial->appendChild($issuerNameNode);
        $issuerSerial->appendChild($serialNumberNode);

        // Armar Certificado
        $cert->appendChild($certDigest);
        $cert->appendChild($issuerSerial);
        $signingCert->appendChild($cert);

        // Firma política
        $signaturePolicyIdentifier = $doc->createElementNS($XADES, 'etsi:SignaturePolicyIdentifier');
        $signaturePolicyId = $doc->createElementNS($XADES, 'etsi:SignaturePolicyId');

        $sigPolicyId = $doc->createElementNS($XADES, 'etsi:SigPolicyId');
        $identifier = $doc->createElementNS($XADES, 'etsi:Identifier', 'http://www.w3.org/2000/09/xmldsig#');
        $sigPolicyId->appendChild($identifier);
        $signaturePolicyId->appendChild($sigPolicyId);

        $sigPolicyHash = $doc->createElementNS($XADES, 'etsi:SigPolicyHash');
        $digestMethodPolicy = $doc->createElementNS($DS, 'ds:DigestMethod');
        $digestMethodPolicy->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');
        $digestValuePolicy = $doc->createElementNS($DS, 'ds:DigestValue', base64_encode(sha1('http://www.w3.org/2000/09/xmldsig#', true)));
        $sigPolicyHash->appendChild($digestMethodPolicy);
        $sigPolicyHash->appendChild($digestValuePolicy);
        $signaturePolicyId->appendChild($sigPolicyHash);

        $signaturePolicyIdentifier->appendChild($signaturePolicyId);

        // Juntar propiedades de firma
        $sigSigProps->appendChild($signingTimeNode);
        $sigSigProps->appendChild($signingCert);

        // <etsi:SignedDataObjectProperties>
        $signedDataObjectProps = $doc->createElementNS($XADES, 'etsi:SignedDataObjectProperties');
        $dataObjectFormat = $doc->createElementNS($XADES, 'etsi:DataObjectFormat');
        $dataObjectFormat->setAttribute('ObjectReference', '#' . $referenceId);

        $description = $doc->createElementNS($XADES, 'etsi:Description', 'compel');
        $mimeType = $doc->createElementNS($XADES, 'etsi:MimeType', 'text/xml');

        $dataObjectFormat->appendChild($description);
        $dataObjectFormat->appendChild($mimeType);
        $signedDataObjectProps->appendChild($dataObjectFormat);

        // Armar SignedProperties
        $signedProps->appendChild($sigSigProps);
        $signedProps->appendChild($signedDataObjectProps);

        // Armar QualifyingProperties
        $qualProps->appendChild($signedProps);

        // Agregar a Object
        $objectNode->appendChild($qualProps);

        return $objectNode;
    }
}
