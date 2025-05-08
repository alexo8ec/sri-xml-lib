# sri-xml-lib
FirmaElectronicaEc
# Facturación Electrónica SRI - Ecuador

Este proyecto permite la **generación, firma y emisión de comprobantes electrónicos** al SRI (Servicio de Rentas Internas del Ecuador) de acuerdo con las normativas vigentes.

## Características

- ✅ Generación de archivos XML válidos según los esquemas XSD del SRI
- 🔐 Firma digital de los comprobantes usando certificados electrónicos (.p12)
- 📤 Envío automático a los web services del SRI (pruebas y producción)
- 📥 Consulta del estado de los comprobantes autorizados
- 🧾 Soporte para: facturas, notas de crédito, notas de débito, guías de remisión y retenciones

## Tecnologías Utilizadas

- Java 8+
- JAXB para generación de XML
- BouncyCastle / Java KeyStore para firma digital
- HTTP / SOAP para comunicación con el SRI
- (Opcional) Laravel/PHP como backend o frontend integrador

## Requisitos Previos

- Certificado digital (.p12 o .jks)
- Clave de firma del certificado
- Acceso a los web services del SRI:
  - Ambiente de pruebas: https://celcer.sri.gob.ec
  - Ambiente de producción: https://cel.sri.gob.ec

## Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/alexo8ec/sri-xml-lib.git
   cd sri-xml-lib