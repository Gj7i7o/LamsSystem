<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos</title>
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
        <h1>REPORTE DE INVENTARIO DE PRODUCTOS</h1>
        <p>LamsSystem - Sistema de Gestion de Inventario</p>
    </div>

    <div class="info">
        <p><strong>Fecha de generacion:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
        <p><strong>Total de registros:</strong> <?php echo count($productos); ?></p>
        <?php if (!empty($filtro_estado)): ?>
            <p><strong>Estado:</strong> <?php echo ucfirst($filtro_estado); ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">CODIGO</th>
                <th style="width: 25%;">NOMBRE</th>
                <th style="width: 10%;">PRECIO</th>
                <th style="width: 10%;">CANTIDAD</th>
                <th style="width: 18%;">CATEGORIA</th>
                <th style="width: 15%;">MARCA</th>
                <th style="width: 10%;">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo strtoupper($producto['codigo']); ?></td>
                    <td><?php echo strtoupper($producto['nombre']); ?></td>
                    <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                    <td style="text-align: center;"><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo strtoupper($producto['categoria'] ?? 'N/A'); ?></td>
                    <td><?php echo strtoupper($producto['marca'] ?? 'N/A'); ?></td>
                    <td class="<?php echo $producto['estado'] == 'activo' ? 'estado-activo' : 'estado-inactivo'; ?>">
                        <?php echo strtoupper($producto['estado']); ?>
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