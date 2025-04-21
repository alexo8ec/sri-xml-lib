<?php
require __DIR__ . '/vendor/autoload.php'; // Cargar dependencias de Composer

use SRI\XmlGenerator;
use SRI\Conexion;

$conn = new Conexion(
    'factural_F4ctur4lg0',
    'h(wa*c~6X5N,',
    'factural_f4ctvr4l60',
    '192.250.227.131'
);
generarXMLNotaCredito($conn);
function generarXMLNotaCredito($conn)
{
    $notasCredito = $conn->consulta(
        "SELECT 
    v.*,
    c.id_tipo_identificacion,
    c.ci,
    c.nombre,
    c.apellido,
    c.email,
    c.telefono,
    c.celular,
    c.direccion,
    p.sri_tipo_impuesto_iva_id
    FROM bm_venta as v 
    JOIN bm_cliente as c ON v.id_cliente=c.id_cliente
    join bm_productos as p ON v.id_producto=p.id_producto
    WHERE v.id_empresa = ? AND v.motivo=? AND v.id_venta=?
    GROUP BY v.id_venta",
        ['41', 'NOTACREDITO', 270414]
    );

    if (count($notasCredito) > 0) {

        foreach ($notasCredito as $row) {

            $empresa = $conn->consultarUno("SELECT * FROM bm_entidad WHERE id_empresa = ? LIMIT 1", [$row['id_empresa']]);

            $venta = $conn->consultarUno("SELECT * FROM bm_venta WHERE id_empresa = ? AND num_factura=? LIMIT 1", [$row['id_empresa'], $row['numComprobanteModificado']]);

            $generator = new XmlGenerator('notaCredito');

            $motivo = $row['motivoAnulacion'];

            $items = $conn->consulta(
                "SELECT 
                v.subtotal,
                p.sri_tipo_impuesto_iva_id,
                p.cod_producto,
                p.descripcion,
                v.cantidad,
                v.p_lista as precio,
                v.total_descontado as descuento,
                (v.valor12 + v.valor0) as subtotal
            FROM bm_venta as v 
            JOIN bm_cliente as c ON v.id_cliente=c.id_cliente
            join bm_productos as p ON v.id_producto=p.id_producto
            WHERE v.id_empresa = ? AND v.motivo=? AND v.establecimiento=? AND v.emision=? AND num_factura= ?
            GROUP BY v.id_venta",
                [$empresa['id_empresa'], 'NOTACREDITO', $row['establecimiento'], $row['emision'], $row['num_factura']]
            );
            $conImpuestos = $generator->calcularTotalConImpuestos($items);

            $detalles = $generator->generarDetalles($items);

            $datos = [
                'infoTributaria' => [
                    'ambiente' => $empresa['id_tipo_ambiente'],
                    'tipoEmision' => $empresa['id_tipo_emision'],
                    'razonSocial' => $empresa['razon_social'],
                    'nombreComercial' => $empresa['nombre_comercial'],
                    'ruc' => $empresa['ruc_empresa'],
                    'claveAcceso' => $row['clave_acceso'],
                    'codDoc' => '04',
                    'estab' => str_pad((int)$row['establecimiento'], 3, 0, STR_PAD_LEFT),
                    'ptoEmi' => str_pad((int)$row['emision'], 3, 0, STR_PAD_LEFT),
                    'secuencial' => str_pad($row['num_factura'], 9, 0, STR_PAD_LEFT),
                    'dirMatriz' => $empresa['direccion'],
                ],
                'infoNotaCredito' => [
                    'fechaEmision' => date('d/m/Y', strtotime($row['fecha_venta'])),
                    'dirEstablecimiento' => $empresa['direccion'],
                    'tipoIdentificacionComprador' => str_pad($row['id_tipo_identificacion'], 2, 0, STR_PAD_LEFT),
                    'razonSocialComprador' => $row['nombre'] . ' ' . $row['apellido'],
                    'identificacionComprador' => $row['ci'],
                    //'contribuyenteEspecial' => '5368', // opcional
                    'obligadoContabilidad' => $empresa['contabilidad'] == 1 ? 'SI' : 'NO',
                    'codDocModificado' => '01',
                    'numDocModificado' => $row['establecimiento'] . '-' . $row['emision'] . '-' . str_pad($row['numComprobanteModificado'], 9, 0, STR_PAD_LEFT),
                    'fechaEmisionDocSustento' => date('d/m/Y', strtotime($venta['fecha_venta'])),
                    'totalSinImpuestos' => $row['subtotal'],
                    'valorModificacion' => $row['valor12'] + ($row['valor12'] * ($row['iva'] / 100)) + $row['valor0'],
                    'moneda' => 'DOLAR',
                    'totalConImpuestos' => $conImpuestos,
                    'motivo' => $motivo
                ],
                'detalles' => $detalles,
                'infoAdicional' => [
                    ['nombre' => 'Email', 'valor' => $row['email']],
                    ['nombre' => 'Teléfono', 'valor' => $row['celular']],
                    ['nombre' => 'Dirección', 'valor' => $row['direccion']],
                ]
            ];
            $documento = $generator->generarNotaCreditoXml($datos);

            $parametros = [
                'F',
                'F',
                $documento,
                date('Y-m-d H:i:s'),
                $row['clave_acceso'],
                $empresa['id_empresa'],
                $row['id_vendedor'],
                'notaCredito',
                $row['num_factura'],
                $venta['establecimiento'] . '-' . $venta['emision'] . '-' . str_pad($venta['num_factura'], 9, 0, STR_PAD_LEFT),
                $empresa['id_tipo_ambiente'],
                $empresa['establecimiento'] . '-' . $empresa['emision'],
                $empresa['establecimiento'],
                $empresa['emision']
            ];

            $sql = "INSERT INTO bm_estado_archivos (
                estado_sri,
                estado_firma,
                archivo_generado,
                fecha_generado,
                claveAcceso,
                id_empresa,
                id_usuario,
                tipo_comprobante,
                num_comprobante,
                num_factura,
                tipo_ambiente,
                serie,
                establecimiento,
                emision) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $exito = $conn->ejecutar($sql, $parametros);

            if ($exito) {
                echo "✅ Xml registrado correctamente.";
            } else {
                echo "❌ Hubo un error al insertar.";
            }
        }
    }
}
