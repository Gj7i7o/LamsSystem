<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Historial</title>
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
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .accion-registrar {
            color: #16a64d;
            font-weight: bold;
        }

        .accion-modificar {
            color: #155DFC;
            font-weight: bold;
        }

        .accion-desactivar {
            color: #e53935;
            font-weight: bold;
        }

        .accion-activar {
            color: #16a64d;
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
                size: letter landscape;
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
        <h1>REPORTE DE HISTORIAL DE ACCIONES</h1>
        <p>LamsSystem - Sistema de Gestion de Inventario</p>
    </div>

    <div class="info">
        <p><strong>Fecha de generacion:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
        <p><strong>Total de registros:</strong> <?php echo count($historial); ?></p>
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
                <th style="width: 12%;">USUARIO</th>
                <th style="width: 12%;">MODULO</th>
                <th style="width: 12%;">ACCION</th>
                <th style="width: 40%;">DESCRIPCION</th>
                <th style="width: 12%;">FECHA</th>
                <th style="width: 12%;">HORA</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historial as $registro): ?>
                <tr>
                    <td><?php echo strtoupper($registro['usuario']); ?></td>
                    <td><?php echo strtoupper($registro['modulo']); ?></td>
                    <td class="accion-<?php echo $registro['accion']; ?>">
                        <?php echo strtoupper($registro['accion']); ?>
                    </td>
                    <td><?php echo strtoupper($registro['descripcion']); ?></td>
                    <td><?php echo $registro['fecha']; ?></td>
                    <td><?php echo $registro['hora']; ?></td>
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