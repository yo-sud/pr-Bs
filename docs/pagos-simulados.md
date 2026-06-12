# Pasarela de pagos simulada

La fase 5 usa una pasarela local de prueba. No solicita datos bancarios y no
debe presentarse como un pago real.

## Flujo del cliente

Desde el detalle de un pedido pendiente, el cliente puede simular tres
resultados:

- `aprobado`: cambia el pago y el pedido a `pagado`.
- `rechazado`: registra el intento y deja el pedido pendiente.
- `pendiente`: registra el intento sin avanzar el pedido.

El importe siempre se obtiene del campo `total` del pedido. El navegador no
envia ni decide el monto.

## Webhook

Endpoint:

```text
POST /webhooks/pagos/falso
```

El cuerpo JSON debe contener `evento_id`, `referencia`, `monto`, `moneda` y
`estado`. La cabecera `X-BookShop-Signature` contiene el HMAC SHA-256 del cuerpo
sin modificar, usando `FAKE_PAYMENT_WEBHOOK_SECRET`.

Cada `evento_id` es unico. Reenviar un evento ya procesado devuelve una respuesta
exitosa sin duplicar la transaccion ni el cambio de estado.

El cliente administra su compra y pago. El administrador controla la preparacion,
el envio y la entrega desde el panel de pedidos. No existe un rol separado para
el despacho.

Este contrato permite sustituir la simulacion por una pasarela real sin cambiar
el modelo de pedidos, la trazabilidad o las reglas de despacho.
