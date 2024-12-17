<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido {{$pedido->codigo_persona}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        header, footer {
            text-align: center;
            margin: 2px;
        }
        #logo{
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }

        #imagen{
        width: 100px;
        }
        #datosempresa{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }
        #encabezado{
        text-align: center;
        margin-left: 10%;
        margin-right: 35%;
        font-size: 15px;
        }
        section{
        clear: left;
        }

        #datos-cliente {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        } 
        #datos-cliente thead{
        padding: 20px;
        background: #7e858b;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }
        #fv {
        color: #FFFFFF;
        font-size: 15px;
        }
        #detalles-pedido {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        #detalles-pedido th, #detalles-pedido td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        #detalles-pedido th {
            background-color: #f14e4e;
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
        <div id="logo">
            <img src="img/logo-sinfondo.png" alt="VRC" id="imagen">
        </div>
        <div id="datosempresa">
            <p id="encabezado">
                <b>VRC Tires</b><br>Colonia San Pedro, San Pablo Tacachico<br>Telefono: (+503) 7555-8182<br>Correo electrónico: vrctiressv@gmail.com
            </p>
        </div>
        <h1>Detalle de Pedido</h1>
        <p><strong>Fecha / hora:</strong> {{$pedido->fecha_hora}}</p>
    </header>
    <section>
        <div>
            <h3>Datos del Cliente</h3>
            <table id="datos-cliente">
                <thead>
                    <tr id="fv">
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center">{{$pedido->codigo_persona}}</td>
                        <td style="text-align: center">{{$pedido->nombre}}</td>
                        <td style="text-align: center">{{$pedido->tipo_pago}}</td>
                    </tr>
                </tbody>
            </table>
            <table id="datos-cliente">
                <thead>
                    <tr id="fv">
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center">{{$pedido->direccion}}</td>
                        <td style="text-align: center">{{$pedido->telefono}}</td>
                        <td style="text-align: center">{{$pedido->email}}</td>
                    </tr>
                </tbody>
            </table>
            <table id="datos-cliente">
                <thead>
                    <tr id="fv">
                        <th>NIT</th>
                        <th>NRC</th>
                        <th>Giro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center">{{$pedido->nit}}</td>
                        <td style="text-align: center">{{$pedido->nrc}}</td>
                        <td style="text-align: center">{{$pedido->giro}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <br><br>
    <hr>
    <section>
        <h3>Detalles del Pedido</h3>
        <table id="detalles-pedido">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo Producto</th>
                    <th>Medida</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $detalle)
                <tr>
                    <td>{{$detalle->codigo}}</td>
                    <td>{{$detalle->tipoproducto}}</td>
                    <td>{{$detalle->medida}}</td>
                    <td>{{$detalle->descripcion}}</td>
                    <td>{{$detalle->cantidad}}</td>
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
