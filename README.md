# Sistema de Control de Asistencia de Transporte Escolar

Sistema web integral diseñado para la gestión y monitoreo de asistencia de estudiantes en rutas de transporte escolar mediante tecnología de códigos QR. La aplicación permite el registro de entradas y salidas en tiempo real, administración de flotas y gestión de información estudiantil.

## Descripción General

Este proyecto fue desarrollado para solucionar la necesidad de un control eficiente y seguro en el transporte estudiantil. Permite a los conductores escanear las credenciales de los alumnos utilizando la cámara de un dispositivo móvil o tablet, sincronizando la información inmediatamente con una base de datos centralizada para que la administración pueda supervisar los movimientos.

El sistema implementa una arquitectura MVC simplificada utilizando PHP nativo, garantizando un despliegue ligero y compatible con la mayoría de los servidores estándar sin dependencias de frameworks pesados.

## Características Principales

### Módulo Administrativo
* **Panel de Control (Dashboard):** Visualización de estadísticas en tiempo real (total de alumnos, asistencias del día, unidades activas) y tabla de actividad reciente.
* **Gestión de Estudiantes:** Alta, baja y modificación de registros de alumnos.
* **Generación de Credenciales:** Creación automática de códigos QR únicos vinculados al ID del estudiante, listos para impresión.
* **Gestión de Flota:** Administración de autobuses y asignación de conductores.
* **Directorio y Contacto:** Acceso rápido a información de emergencia y asignación de rutas.
* **Reportes:** Filtrado histórico de asistencias por fecha.

### Módulo Operativo (Chofer)
* **Escáner Web:** Interfaz optimizada para móviles que utiliza la cámara del dispositivo para la lectura de códigos QR.
* **Lógica de Entrada/Salida:** Capacidad de alternar manualmente entre modo "Recogida" (Entrada) y "Entrega" (Salida).
* **Validación de Registros:** Prevención de lecturas duplicadas consecutivas y retroalimentación visual/auditiva de éxito o error.

### Seguridad y Arquitectura
* **Control de Sesiones:** Manejo estricto de roles (Admin vs. Chofer) y destrucción segura de sesiones.
* **Protección de Historial:** Implementación de cabeceras HTTP anti-caché para prevenir el acceso no autorizado mediante la navegación "Atrás" del navegador.
* **Diseño Centralizado:** Uso de Tailwind CSS con configuración personalizada para mantener una identidad visual consistente y escalable.

## Requisitos del Sistema

* **Servidor Web:** Apache o Nginx.
* **Lenguaje:** PHP 8.0 o superior.
* **Base de Datos:** MySQL / MariaDB.
* **Extensiones PHP:** PDO, GD (para generación de imágenes).

## Instalación y Despliegue

Siga estos pasos para desplegar el proyecto en un entorno local (Laragon/XAMPP):

1.  **Clonado del Repositorio**
    Descargue o clone el código fuente en el directorio público de su servidor web.
    ```bash
    git clone [https://github.com/TU_USUARIO/transporte_app.git](https://github.com/TU_USUARIO/transporte_app.git)
    ```

2.  **Configuración de la Base de Datos**
    * Cree una nueva base de datos en MySQL llamada `transporte_app`.
    * Importe el archivo `database.sql` ubicado en la raíz del proyecto. Este archivo contiene la estructura de las tablas y datos semilla iniciales.

3.  **Configuración de Entorno**
    * Verifique el archivo `includes/conexion.php` y ajuste las credenciales de base de datos si es necesario (por defecto configurado para root sin contraseña en local).
    * Verifique el archivo `includes/config.php` para ajustar la zona horaria (`date_default_timezone_set`) según su región.

4.  **Ejecución**
    Acceda a través del navegador a la URL local (ej. `http://localhost/transporte_app`).

## Credenciales de Acceso (Entorno de Pruebas)

El sistema viene preconfigurado con los siguientes usuarios para pruebas:

| Rol | Usuario | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | tavo | tavo1234 |
| **Chofer** | juan | 12345 |

## Tecnologías Utilizadas

* **Backend:** PHP (PDO).
* **Frontend:** HTML5, Tailwind CSS (Compilado vía CLI).
* **Base de Datos:** MySQL.
* **Librerías:**
    * `phpqrcode`: Generación de imágenes QR server-side.
    * `html5-qrcode`: Lectura de códigos QR client-side.

## Estado del Proyecto

Versión 1.0.0 - Estable.
El sistema se encuentra en fase de producción con funcionalidades completas de CRUD y reporte.

---
Desarrollado por Gustavo Rojas
