# Roadmap de BookShop

Actualizado: 7 de junio de 2026

## Estado actual

El proyecto tiene una base visual avanzada para la tienda, autenticacion de Laravel
Breeze, modelos iniciales de libros, categorias y proveedores, y migraciones que
pueden crear la base de datos.

Sin embargo, todavia es un prototipo funcional parcial:

- El catalogo, novedades, populares, inicio y carrito usan datos escritos
  directamente en las vistas.
- Los controladores de libros, categorias y proveedores estan vacios.
- No existen rutas CRUD para administrar el catalogo.
- El carrito solo es visual: no agrega, actualiza ni elimina productos.
- No existen pedidos, detalle de pedido, pagos, direcciones ni entregas.
- El dashboard administrativo existe como vista, pero no tiene ruta, permisos ni
  datos reales.
- No hay roles definidos de admin y usuario.
- No existe un flujo de compra que valide y reduzca el stock de los libros.
- No existe una base de datos de compras que guarde pedidos, productos, cantidades
  y precios historicos.
- La busqueda y el filtro del catalogo no procesan los parametros enviados.
- Las pruebas actuales cubren autenticacion y perfil, no el negocio de la tienda.

## Bloqueos inmediatos

Completar antes de agregar nuevas funcionalidades:

1. Corregir el `down()` de la migracion de proveedores: crea `proveedores`, pero
   intenta eliminar `proveedors`.
2. Eliminar el flujo de autenticacion manual de `AuthController` y conservar
   solamente Breeze. Actualmente contiene credenciales fijas y rutas duplicadas.
3. Regenerar las dependencias frontend en cada sistema operativo. El
   `node_modules` actual no tiene permisos de ejecucion y le falta el paquete
   nativo `@rollup/rollup-darwin-arm64`.
4. Ejecutar Laravel Pint y corregir los 9 archivos reportados.
5. Reemplazar el README generico de Laravel con requisitos, instalacion,
   configuracion, usuarios de prueba y comandos del proyecto.

Comandos sugeridos para reconstruir el frontend:

```bash
rm -rf node_modules
npm ci
npm run build
```

## Fase 1: Catalogo real - completada

Objetivo: que la tienda lea y administre libros desde la base de datos.

- Ampliar `libros` con ISBN, descripcion, editorial, fecha de publicacion,
  portada, estado, destacado y contador de ventas.
- Definir reglas de borrado seguras. No usar eliminacion en cascada de libros al
  borrar una categoria o proveedor con historial.
- Crear factories y seeders para categorias, proveedores, libros y usuarios.
- Implementar `LibroController@index` y `show` con paginacion.
- Implementar busqueda por titulo, autor o ISBN.
- Implementar filtros por categoria, precio, novedad y popularidad.
- Convertir las tarjetas repetidas en un componente Blade reutilizable.
- Cargar inicio, catalogo, novedades y populares con consultas reales.
- Agregar almacenamiento local de portadas y una imagen predeterminada.

Criterio de aceptacion: `php artisan migrate:fresh --seed` deja una tienda
navegable, con busqueda, filtros, detalle y paginacion usando solo datos de BD.

Estado al 7 de junio de 2026: implementada y verificada con pruebas automatizadas,
seeders, build de produccion y respuestas HTTP del servidor local.

## Fase 2: Roles y panel administrativo - completada

Objetivo: permitir que el administrador mantenga el catalogo y el stock que
estara disponible para las compras.

- Agregar un campo o sistema de roles para definir `admin` y `usuario`.
- Crear un usuario administrador inicial desde el seeder, sin guardar una
  contrasena fija en los controladores.
- Crear middleware o policies para proteger cada area.
- Crear ruta y controlador del dashboard administrativo.
- Implementar CRUD de libros, categorias y proveedores con Form Requests.
- Permitir que el administrador registre un libro con titulo, autor, ISBN,
  categoria, proveedor, precio, portada y stock inicial.
- Permitir que el administrador aumente o corrija el stock de un libro.
- Incorporar validacion de stock no negativo, precio, ISBN unico e imagen.
- Registrar movimientos de inventario con libro, cantidad anterior, cantidad
  nueva, motivo, usuario y fecha.
- Mostrar alertas de stock bajo y movimientos de inventario.
- Reemplazar todos los enlaces `href="#"` del panel por rutas reales.
- Obtener las tarjetas y graficos del dashboard desde consultas reales.

Criterio de aceptacion: un administrador puede crear libros y administrar su
stock; un usuario recibe `403` al intentar entrar a esas rutas.

Estado al 7 de junio de 2026: implementada con rol administrador, middleware,
dashboard real, CRUD de catalogo, portadas, ajustes de stock auditados, alertas
de inventario y pruebas de permisos.

## Fase 3: Base de datos de compras

Estado: COMPLETADA.

Objetivo: guardar de forma consistente toda la informacion de una compra.

- Usar carrito de sesion segun la decision documentada en
  `docs/decisiones/carrito-sesion.md`.
- Crear tabla `pedidos` con usuario, direccion, subtotal, envio, total, estado de
  pago, estado del pedido y fechas.
- Crear tabla `pedido_detalles` con pedido, libro, cantidad, precio unitario y
  subtotal.
- Guardar el precio del libro en `pedido_detalles` para conservar el valor
  historico aunque el administrador cambie el precio despues.
- Crear tabla `movimientos_inventario` para registrar entradas, correcciones,
  ventas y devoluciones.
- Definir relaciones Eloquent entre usuarios, pedidos, detalles y libros.
- Crear factories y seeders para probar pedidos con distintos estados.

Criterio de aceptacion: una compra conserva sus productos, cantidades, precios y
totales aunque posteriormente cambien los datos del catalogo.

Estado al 7 de junio de 2026: implementada con pedidos y detalles historicos,
relaciones Eloquent, estados y fechas de seguimiento, factories, datos de prueba
y carrito de sesion documentado.

## Fase 4: Carrito y flujo de compra

Estado: COMPLETADA.

Objetivo: permitir una compra completa y reducir correctamente el stock.

- Elegir carrito de sesion para invitados o carrito persistente por usuario.
- Crear acciones para agregar, actualizar cantidad, eliminar y vaciar.
- Validar stock en cada cambio y recalcular precios en el servidor.
- Mostrar contador real en la cabecera.
- Crear direcciones de envio y formulario de checkout.
- Mostrar un resumen final antes de confirmar la compra.
- Al confirmar, volver a consultar y bloquear los libros involucrados para evitar
  que dos usuarios compren simultaneamente el mismo stock.
- Crear el pedido, sus detalles y descontar el stock dentro de una unica
  transaccion de base de datos.
- Impedir la compra cuando la cantidad solicitada supera el stock disponible.
- No permitir que el stock termine con valores negativos.
- Registrar cada descuento como movimiento de inventario de tipo `venta`.
- Restaurar el stock mediante un movimiento de tipo `devolucion` cuando un pedido
  sea cancelado y corresponda devolver las unidades.
- Definir estados: pendiente, pagado, preparando, enviado, entregado y cancelado.
- Crear confirmacion y pagina de historial/detalle de compras.

Criterio de aceptacion: un usuario puede agregar varios libros, cambiar
cantidades y confirmar un pedido; cada libro reduce su stock exactamente en la
cantidad comprada, el pedido queda guardado y no se producen ventas sin stock.

Estado al 7 de junio de 2026: implementada con carrito de sesion para invitados,
contador real, validacion de stock, checkout autenticado, resumen recalculado en
el servidor, bloqueo de libros, pedido transaccional, movimientos de venta,
historial y detalle de compras, y cancelacion con devolucion idempotente.

## Fase 5: Pago y despacho

Estado: COMPLETADA.

Objetivo: llevar el pedido desde el pago hasta la entrega.

- Implementar una pasarela de pago simulada para el usuario y definir pago
  aprobado, rechazado y
  pendiente.
- Implementar webhooks idempotentes y registro de transacciones.
- No confiar en montos enviados por el navegador.
- Crear asignacion de pedidos para el flujo de despacho.
- Crear vista operativa de despacho con cambio controlado de estado.
- Registrar fecha, usuario y observacion de cada cambio de estado.
- Enviar correo de confirmacion y actualizaciones del pedido.

Criterio de aceptacion: un pago confirmado actualiza un pedido una sola vez, y
el despacho conserva trazabilidad completa hasta la entrega.

Estado al 7 de junio de 2026: implementada con pasarela simulada de resultados
aprobado, rechazado y pendiente; transacciones y eventos webhook idempotentes
firmados; despacho gestionado por el administrador; transiciones controladas de
preparacion, envio y entrega; historial con usuario, fecha y observacion; y
correos en cola despues del commit. Los unicos roles son `user` y `admin`.

## Fase 6: Calidad y QA

Objetivo: asegurar que el proyecto tenga cobertura de pruebas solida, codigo limpio y criterios de calidad verificables.

- Definir una estrategia de pruebas por capas: Unit, Feature e integracion para
  carrito, checkout, pedidos e inventario.
- Cubrir escenarios criticos de negocio: compra exitosa, compra sin stock,
  cambios de estado del pedido y restricciones por rol.
- Probar concurrencia de stock con compras simultaneas para prevenir valores
  negativos y pedidos incompletos.
- Agregar pruebas de regresion para permisos administrativos y rutas protegidas.
- Incorporar pruebas de contrato para respuestas HTTP clave (codigos, estructura
  y validaciones).
- Implementar checklist de QA manual para responsive, accesibilidad basica,
  mensajes de error, estados vacios y navegacion critica.
- Estandarizar calidad estatica con Pint, analisis de codigo y convenciones de
  estilo en CI.
- Configurar pipeline de CI con puertas minimas: pruebas en verde, lint/formato
  y build de frontend exitoso.
- Registrar evidencias de QA por release (casos ejecutados, incidencias,
  severidad y estado de resolucion).

Criterio de aceptacion: una instalacion limpia pasa migraciones, seeders, pruebas,
formato y build; los casos criticos de negocio quedan en verde y QA valida el
flujo principal en movil y escritorio sin defectos bloqueantes.

## Orden recomendado de trabajo

1. Bloqueos inmediatos.
2. Catalogo real y seeders.
3. CRUD administrativo y roles.
4. Base de datos de compras e inventario.
5. Carrito funcional.
6. Confirmacion de compra y reduccion transaccional del stock.
7. Pago y despacho.
8. Cobertura de pruebas, QA y calidad continua.

## Proximo sprint sugerido

Duracion estimada: 1 semana.

- Corregir migracion y autenticacion duplicada.
- Normalizar formato con Pint.
- Crear factories y seeders de catalogo.
- Implementar listado, detalle, busqueda, filtros y paginacion.
- Reemplazar las vistas estaticas por datos reales.
- Crear el rol administrador y proteger el panel.
- Permitir al administrador crear libros con precio y stock inicial.
- Crear las migraciones de pedidos, detalles y movimientos de inventario.
- Agregar pruebas Feature del catalogo, permisos de administrador y stock.
- Dejar `composer test` y `npm run build` en verde.

Resultado esperado: una tienda conectada a base de datos, con un administrador
capaz de registrar libros y stock, y con la estructura de compras preparada para
implementar el carrito y el descuento de inventario.
