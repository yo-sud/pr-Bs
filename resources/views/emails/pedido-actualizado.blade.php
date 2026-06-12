<!DOCTYPE html>
<html lang="es">
<body style="font-family: Arial, sans-serif; color: #421605; line-height: 1.5;">
    <h1 style="font-size: 24px;">{{ $titulo }}</h1>
    <p>Hola {{ $pedido->usuario?->name }},</p>
    <p>El pedido <strong>#{{ $pedido->id }}</strong> ahora se encuentra en estado <strong>{{ $pedido->estado_pedido }}</strong>.</p>
    @if ($observacion)
        <p>{{ $observacion }}</p>
    @endif
    <p>Total: <strong>S/ {{ number_format((float) $pedido->total, 2) }}</strong></p>
    <p>Gracias por comprar en BookShop.</p>
</body>
</html>
