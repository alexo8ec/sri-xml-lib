<?php

namespace SRI;

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

    public function firmarXML($xmlString)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xmlString);

        // Crear el objeto de firma
        $xmlDSig = new XMLSecurityDSig();
        $xmlDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
        $xmlDSig->addReference(
            $doc,
            XMLSecurityDSig::SHA1,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature']
        );

        // Cargar la clave privada para firmar
        $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, ['type' => 'private']);
        $key->loadKey($this->clave);

        // Firmar y agregar al documento XML
        $xmlDSig->sign($key);
        $xmlDSig->add509Cert($this->certificado, true, false);
        $xmlDSig->appendSignature($doc->documentElement);

        return $doc->saveXML();
    }
}
