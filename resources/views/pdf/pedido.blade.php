<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{$pedido->id}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        header, footer {
            text-align: center;
            margin: 10px;
        }
        #datos-cliente, #detalles-pedido {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        #datos-cliente th, #datos-cliente td, #detalles-pedido th, #detalles-pedido td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        #datos-cliente th, #detalles-pedido th {
            background-color: #2183E3;
            color: white;
        }
        #gracias {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Reporte de Pedido</h1>
        <p><strong>Fecha:</strong> {{$pedido->fecha_hora}}</p>
    </header>
    <section>
        <h3>Datos del Cliente</h3>
        <table id="datos-cliente">
            <tbody>
                <tr>
                    <th>Nombre</th>
                    <td>{{$pedido->nombre}}</td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td>{{$pedido->direccion}}</td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td>{{$pedido->telefono}}</td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td>{{$pedido->email}}</td>
                </tr>
                <tr>
                    <th>NIT</th>
                    <td>{{$pedido->nit}}</td>
                </tr>
                <tr>
                    <th>NRC</th>
                    <td>{{$pedido->nrc}}</td>
                </tr>
                <tr>
                    <th>Giro</th>
                    <td>{{$pedido->giro}}</td>
                </tr>
            </tbody>
        </table>
    </section>
    <section>
        <h3>Detalles del Pedido</h3>
        <table id="detalles-pedido">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Código</th>
                    <th>Tipo Producto</th>
                    <th>Descripción</th>
                    <th>Medida</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $detalle)
                <tr>
                    <td>{{$detalle->cantidad}}</td>
                    <td>{{$detalle->codigo}}</td>
                    <td>{{$detalle->tipo_producto}}</td>
                    <td>{{$detalle->descripcion}}</td>
                    <td>{{$detalle->medida}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    <footer>
        <div id="gracias">
            <p>¡Gracias por su compra!</p>
        </div>
    </footer>
</body>
</html>
