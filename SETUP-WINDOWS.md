# Cómo correr Bookshop en Windows (desde cero)

Guía paso a paso para levantar el proyecto usando **XAMPP** después de clonarlo.

---

## Requisitos previos

Necesitás tener instalado:

| Herramienta | Versión mínima | Descarga |
|---|---|---|
| XAMPP | 8.2 o superior | https://www.apachefriends.org/es/index.html |
| Composer | Última | https://getcomposer.org/Composer-Setup.exe |
| Node.js | 18 o superior | https://nodejs.org/ |
| Git | Cualquiera | https://git-scm.com/ |

> **Tip:** Durante la instalación de XAMPP, asegurate de marcar los componentes **Apache** y **MySQL**. PHP viene incluido en XAMPP.

---

## Paso 1 — Iniciar XAMPP

1. Abrí **XAMPP Control Panel** (buscalo en el menú inicio)
2. Hacé clic en **Start** en los módulos **Apache** y **MySQL**
3. Ambos deben quedar en verde antes de continuar

---

## Paso 2 — Agregar PHP de XAMPP al PATH

Para usar `php` en PowerShell, necesitás agregar XAMPP al PATH de Windows:

1. Buscá **"Variables de entorno"** en el menú inicio y abrilo
2. En "Variables del sistema", seleccioná `Path` y hacé clic en **Editar**
3. Hacé clic en **Nuevo** y agregá: `C:\xampp\php`
4. Aceptá todo y cerrá las ventanas

Verificá que funciona abriendo una nueva ventana de PowerShell:

```powershell
php -v
```

Deberías ver algo como `PHP 8.x.x ...`.

---

## Paso 3 — Habilitar extensiones de PHP en XAMPP

Abrí el archivo `C:\xampp\php\php.ini` (con el Bloc de notas) y asegurate de que estas líneas **no tengan el `;` al principio**:

```ini
extension=pdo_mysql
extension=mysqli
extension=openssl
extension=fileinfo
extension=mbstring
extension=tokenizer
extension=xml
extension=ctype
```

Guardá el archivo y reiniciá el módulo Apache desde el XAMPP Control Panel (Stop → Start).

---

## Paso 4 — Crear la base de datos en phpMyAdmin

1. Con XAMPP corriendo, abrí el navegador y entrá a: **http://localhost/phpmyadmin**
2. En el panel izquierdo, hacé clic en **Nueva** (o "New")
3. En el campo "Nombre de la base de datos", escribí exactamente:

```
bd_bookshop
```

4. Dejá el cotejamiento en `utf8mb4_unicode_ci` y hacé clic en **Crear**

---

## Paso 5 — Clonar el repositorio

Abrí PowerShell y ejecutá:

```powershell
git clone https://github.com/TU_USUARIO/bookshop.git
cd bookshop
```

> Reemplazá `TU_USUARIO` con el usuario de GitHub donde está el repo.

---

## Paso 6 — Instalar dependencias PHP

```powershell
composer install
```

Esto descarga todos los paquetes de Laravel. Puede tardar unos minutos la primera vez.

---

## Paso 7 — Crear y configurar el archivo `.env`

```powershell
copy .env.example .env
```

Abrí el archivo `.env` con cualquier editor de texto y reemplazá la sección de base de datos para que quede así:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bd_bookshop
DB_USERNAME=root
DB_PASSWORD=
```

> La contraseña se deja **vacía** porque XAMPP instala MySQL sin contraseña por defecto.

Luego generá la clave de la aplicación:

```powershell
php artisan key:generate
```

Deberías ver: `Application key set successfully.`

---

## Paso 8 — Correr las migraciones

```powershell
php artisan migrate
```

Esto crea todas las tablas necesarias en la base de datos `bd_bookshop`. Deberías ver una lista de migraciones ejecutadas exitosamente.

---

## Paso 9 — Instalar dependencias de Node.js

```powershell
npm install
```

---

## Paso 10 — Compilar los assets del frontend

```powershell
npm run build
```

---

## Paso 11 — Levantar el servidor

```powershell
php artisan serve
```

Abrí el navegador en: **http://localhost:8000**

---

## Resumen de comandos (pasos 5 al 11)

Una vez que XAMPP está corriendo y la base de datos `bd_bookshop` fue creada en phpMyAdmin:

```powershell
git clone https://github.com/TU_USUARIO/bookshop.git
cd bookshop
composer install
copy .env.example .env
# Editá el .env con los datos de MySQL (ver Paso 7)
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

---

## Problemas comunes

### ❌ `'php' is not recognized as an internal or external command`
PHP de XAMPP no está en el PATH. Seguí el Paso 2. Acordate de abrir una ventana **nueva** de PowerShell después de modificar el PATH.

### ❌ `SQLSTATE[HY000] [1045] Access denied for user 'root'`
La contraseña en el `.env` no coincide. Por defecto XAMPP no tiene contraseña, así que `DB_PASSWORD=` debe quedar vacío (sin nada después del `=`).

### ❌ `SQLSTATE[HY000] [1049] Unknown database 'bd_bookshop'`
La base de datos no fue creada. Seguí el Paso 4 y creala en phpMyAdmin antes de migrar.

### ❌ `Class "PDO" not found` o errores de MySQL
Las extensiones no están habilitadas. Revisá el Paso 3 y asegurate de descomentar `extension=pdo_mysql` en el `php.ini` de XAMPP, luego reiniciá Apache.

### ❌ MySQL no arranca en XAMPP (puerto 3306 ocupado)
Otro servicio está usando el puerto 3306 (puede ser una instalación separada de MySQL). Solución:
1. Abrí el Administrador de tareas
2. Buscá el proceso `mysqld.exe` y terminalo
3. Volvé a iniciar MySQL desde XAMPP

### ❌ La página carga pero sin estilos (CSS roto)
El frontend no fue compilado. Corré `npm run build` nuevamente.

### ❌ `Class "PDO" not found` o errores de SQLite
Las extensiones de PHP no están habilitadas. Revisá el Paso 1 y asegurate de descomentar `extension=pdo_sqlite` y `extension=sqlite3` en el `php.ini`.

### ❌ `composer: command not found`
Instalá Composer desde https://getcomposer.org/Composer-Setup.exe — el instalador agrega Composer al PATH automáticamente.

### ❌ Error de permisos en `storage/` o `bootstrap/cache/`
Corré esto en PowerShell como administrador:

```powershell
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T
```

### ❌ La página carga pero sin estilos (CSS roto)
El frontend no fue compilado. Corré `npm run build` nuevamente.
