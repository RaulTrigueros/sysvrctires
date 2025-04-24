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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header, footer {
            text-align: center;
            margin: 1px;
        }
        #logo{
        float: left;
        margin-top: 0.1%;
        margin-left: 0.20%;
        margin-right: 2%;
        }

       #imagen{
        width: 150px;
        height: 120px;
        }
        #datosempresa{
        float: left;
        margin-top: 0%;
        margin-left: 0.1%;
        margin-right: 2%;
        /*text-align: justify;*/
        }
        #encabezado{
        text-align: center;
        margin-left: 0.10%;
        margin-right: 40%;
        font-size: 15px;
        }
        section{
        clear: left;
        }

        #datos-cliente {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 5px;
        font-size: 11px;
        } 
        #datos-cliente thead{
        padding: 20px;
        background: #9ea7af;
        border-bottom: 1px solid #FFFFFF;  
        font-size: 11px;
        }
        #fv {
        color: #FFFFFF;
        font-size: 11px;
        }
        #detalles-pedido {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 100px;
            font-size: 11px;

        }
        #detalles-pedido th, #detalles-pedido td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }
        #detalles-pedido th {
            background-color: #f14e4e;
            color: white;
        }
        #pie {
        position: fixed;
        width: 100%;
        height: 100px;
        top: auto;
        right: 0;
        bottom: 0px;
        left: 0;
        margin-left:2cm;margin-right:3cm;
        text-align: center;
      }
    </style>
</head>
<body>
    <header>
        <div id="logo">
            <img src="img/logomotopdf.jpg" alt="VRC" id="imagen">
        </div>
        <div id="datosempresa">
            <p id="encabezado">
                <b>VRC</b><br><b>Moto Parts Company</b><br>Lotificacion San Pedro, San Pablo Tacachico<br>Telefono: (+503) 6439-0329<br>Correo electrónico: vrctiressv@gmail.com
            </p>
        </div>
        <h1>Cotización</h1>
        <p><strong>Fecha / hora:</strong> {{$pedido->fecha_hora}}</p>
    </header>
    <section>
        <div>
            <h3>Datos del Cliente</h3>
            <table id="datos-cliente">
                <thead>
                    <tr id="fv">
                        <th style="text-align: left">Código</th>
                        <th style="text-align: left">Nombre</th>
                        <th style="text-align: left">Tipo de Cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left">{{$pedido->codigo_persona}}</td>
                        <td style="text-align: left">{{$pedido->nombre}}</td>
                        <td style="text-align: left">{{$pedido->tipo_cliente}}</td>
                    </tr>
                </tbody>
            </table>
            <table id="datos-cliente">
                <thead>
                    <tr id="fv">
                        <th style="text-align: left">Dirección</th>
                        <th style="text-align: left">Teléfono</th>
                        <th style="text-align: left">Correo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left">{{$pedido->direccion}}</td>
                        <td style="text-align: left">{{$pedido->telefono}}</td>
                        <td style="text-align: left">{{$pedido->email}}</td>
                    </tr>
                </tbody>
            </table>
            <table id="datos-cliente">
                <thead>
                    <tr id="fv">
                        <th style="text-align: left">NIT</th>
                        <th style="text-align: left">NRC</th>
                        <th style="text-align: left">Giro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left">{{$pedido->nit}}</td>
                        <td style="text-align: left">{{$pedido->nrc}}</td>
                        <td style="text-align: left">{{$pedido->giro}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <br>
    <hr>
    <main>
        <section>
            <h3>Detalles del Pedido</h3>
            <table id="detalles-pedido">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Tipo Producto</th>
                        <th>Medida</th>
                        <th>Descripción</th>
                        <th>Precio Unitario</th>
                        <th>Precio con Descuento</th>
                        <th>Cantidad</th>
                        <th colspan="2">Subtotal con Descuento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $detalle)
                        @php
                        //$porcentajeDescuento = $detalle->porcentaje_descuento ?? 0;
                        $precioConDescuento = $detalle->precio - ($detalle->precio * $porcentajeDescuento / 100);
                        $subtotalConDescuento = $precioConDescuento * $detalle->cantidad;
                        @endphp
                        <tr>
                            <td>{{$detalle->codigo}}</td>
                            <td>{{$detalle->tipoproducto}}</td>
                            <td>{{$detalle->medida}}</td>
                            <td>{{$detalle->descripcion}}</td>
                            <td>{{$detalle->precio}}</td>
                            <td>${{ number_format($precioConDescuento, 2) }}</td>
                            <td>{{$detalle->cantidad}}</td>
                            <td colspan="2">${{ number_format($subtotalConDescuento, 2) }}</td>
                        </tr>
                    @endforeach
                        
                    <tr style="background-color: #f5f5f0;">
                        <td colspan="8" style="text-align: right;"><strong>Descuento total:</strong></td>
                        <td>${{ number_format($descuento, 2) }}</td>
                    </tr>
                    <tr style="background-color: #f5f5f0;">
                        <td colspan="8" style="text-align: right;"><strong>Total a Pagar:</strong></td>
                        <td style="background-color: #f7ed17;" ><strong>${{ number_format($totalPagar, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
    <div id="pie">
        <hr>
        <img class="navbar-brand-full" src="img/piePagina.jpg" width="350" height="25" alt="encabezado" text-align:center >
    </div>

</body>

</html>
