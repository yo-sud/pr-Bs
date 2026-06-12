# Carrito de compras

## Decision

BookShop usa un carrito almacenado en la sesion. No se crean las tablas
`carritos` ni `carrito_detalles`.

## Alcance

- La sesion guardara solamente los identificadores de libro y sus cantidades.
- El servidor volvera a consultar precio, estado y stock antes de mostrar el
  resumen y antes de confirmar una compra.
- Los importes recibidos desde el navegador nunca seran considerados
  autoritativos.
- Al confirmar, el carrito se convertira en un `pedido` y sus lineas se
  copiaran a `pedido_detalles` dentro de una transaccion.
- `pedido_detalles` conserva ISBN, titulo y precio unitario como datos
  historicos, aunque el catalogo cambie despues.

Esta decision mantiene simple el carrito para la primera version. La sesion ya
se almacena en base de datos mediante `SESSION_DRIVER=database`, por lo que el
contenido sobrevive entre solicitudes sin introducir un segundo modelo de
persistencia antes del checkout.
