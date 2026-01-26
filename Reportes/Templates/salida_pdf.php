<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Salidas</title>
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
            font-size: 10px;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px 5px;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-venta {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-uso_interno {
            background-color: #cce5ff;
            color: #004085;
        }

        .badge-danado {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-devolucion {
            background-color: #fff3cd;
            color: #856404;
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
        <h1>REPORTE DE SALIDAS</h1>
        <p>LamsSystem - Sistema de Gestion de Inventario</p>
    </div>

    <div class="info">
        <p><strong>Fecha de generacion:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
        <p><strong>Total de registros:</strong> <?php echo count($salidas); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">DOCUMENTO</th>
                <th style="width: 12%;">TIPO DESPACHO</th>
                <th style="width: 10%;">FECHA</th>
                <th style="width: 8%;">HORA</th>
                <th style="width: 25%;">PRODUCTO</th>
                <th style="width: 8%;" class="text-center">CANT.</th>
                <th style="width: 12%;" class="text-right">PRECIO</th>
                <th style="width: 13%;" class="text-right">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salidas as $salida): ?>
                <tr>
                    <td><?php echo strtoupper($salida['cod_docum']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $salida['tipo_despacho']; ?>">
                            <?php echo strtoupper(str_replace('_', ' ', $salida['tipo_despacho'])); ?>
                        </span>
                    </td>
                    <td><?php echo $salida['fecha']; ?></td>
                    <td><?php echo $salida['hora']; ?></td>
                    <td><?php echo strtoupper($salida['producto'] ?? 'N/A'); ?></td>
                    <td class="text-center"><?php echo $salida['cantidad']; ?></td>
                    <td class="text-right">$<?php echo number_format($salida['precio'], 2); ?></td>
                    <td class="text-right">$<?php echo number_format($salida['sub_total'], 2); ?></td>
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