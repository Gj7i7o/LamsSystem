<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Proveedores</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #314158;
        }

        .header h1 {
            color: #314158;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 11px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info p {
            margin: 3px 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #314158;
            color: #fff;
            padding: 10px 5px;
            text-align: left;
            font-size: 11px;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px 5px;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .estado-activo {
            color: #16a64d;
            font-weight: bold;
        }

        .estado-inactivo {
            color: #e53935;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }

        .no-print button {
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            background-color: #314158;
            color: white;
            border: none;
            border-radius: 5px;
            margin: 0 5px;
        }

        .no-print button:hover {
            background-color: #1e2a38;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            @page {
                margin: 1cm;
                size: letter;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()">Imprimir / Guardar PDF</button>
        <button onclick="window.close()">Cerrar</button>
    </div>

    <div class="header">
        <h1>REPORTE DE PROVEEDORES</h1>
        <p>LamsSystem - Sistema de Gestion de Inventario</p>
    </div>

    <div class="info">
        <p><strong>Fecha de generacion:</strong> <?php echo date('d/m/Y h:i:s'); ?></p>
        <p><strong>Total de registros:</strong> <?php echo count($proveedores); ?></p>
        <?php if (!empty($filtro_fecha_desde) || !empty($filtro_fecha_hasta)): ?>
            <p><strong>Rango de fecha:</strong>
                <?php echo !empty($filtro_fecha_desde) ? $filtro_fecha_desde : 'Inicio'; ?> -
                <?php echo !empty($filtro_fecha_hasta) ? $filtro_fecha_hasta : 'Actual'; ?>
            </p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">RIF</th>
                <th style="width: 20%;">NOMBRE</th>
                <th style="width: 12%;">TELEFONO</th>
                <th style="width: 15%;">CONTACTO</th>
                <th style="width: 30%;">DIRECCION</th>
                <th style="width: 11%;">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proveedores as $proveedor): ?>
                <tr>
                    <td><?php echo strtoupper($proveedor['rif']); ?></td>
                    <td><?php echo strtoupper($proveedor['nombre']); ?></td>
                    <td><?php echo $proveedor['telefono'] ?? 'N/A'; ?></td>
                    <td><?php echo strtoupper($proveedor['persona_contacto'] ?? 'N/A'); ?></td>
                    <td><?php echo strtoupper($proveedor['direccion'] ?? ''); ?></td>
                    <td class="<?php echo $proveedor['estado'] == 'activo' ? 'estado-activo' : 'estado-inactivo'; ?>">
                        <?php echo strtoupper($proveedor['estado']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Generado el <?php echo date('d/m/Y H:i:s'); ?> - LamsSystem
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>