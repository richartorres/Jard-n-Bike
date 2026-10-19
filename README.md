# Jardín Bike 🚲⚡ - Plataforma de Alquiler de Bicicletas Eléctricas

**Jardín Bike** es una solución web full-stack para el sistema de movilidad limpia y alquiler de bicicletas eléctricas (*e-bikes*) en el municipio de Jardín, Antioquia. Diseñada con arquitectura basada en micro-reglas de negocio, navegación tipo Uber/Rappi para el trazado de rutas entre estaciones estratégicas y un panel de administración en tiempo real para el control de la flota.

🔗 **Prueba la aplicación en vivo (Producción):** [https://jard-n-bike.onrender.com](https://jard-n-bike.onrender.com)

---

## 🚀 Características Principales y Reglas de Negocio

### 🚲 Módulo de Cliente / Usuario
- **Desbloqueo mediante Código QR:** Escaneo y validación de la unidad para iniciar el recorrido de forma inmediata.
- **Cobro Recurrente por Tiempo:** Cálculo automático de tarifas (tarifario base: $4.000 COP por cada bloque de 15 minutos).
- **Rutas e Integración de Mapas:** Navegación en tiempo real con trazado de ruta dinámico de estación a estación para el turismo local.
- **Validación Geo-Física para Cierre de Viaje:** Regla de negocio estricta que exige que la bicicleta esté físicamente anclada en una estación oficial para permitir la finalización del servicio y detener el cobro.
- **Seguridad y Cuentas:** Sistema completo de registro, inicio de sesión y módulo de **recuperación de contraseña vía correo electrónico** (verificación de existencia previa en base de datos e integración de correo transaccional).

### 🛠️ Módulo Administrador / Panel Operativo
- **Gestión de Flota:** Alta de bicicletas, monitoreo de batería (porcentaje en tiempo real) y control de estados (*Disponible, En uso, Mantenimiento*).
- **Control de Estaciones:** Registro y gestión de puntos estratégicos de anclaje, direcciones y capacidades de carga.
- **Centro de Alertas e Incidencias:** Sistema de detección de anomalías (batería crítica, bicicletas fuera de puesto, fallas reportadas) con opciones directas de resolución técnica.
- **Control de Roles:** Gestión de usuarios con permisos para promover cuentas de cliente estándar a perfil administrador.

---

## 🛠️ Tecnologías Utilizadas

- **Framework Backend:** PHP / Laravel
- **Base de Datos Relacional:** MySQL / MariaDB (alojada en Clever Cloud)
- **Frontend & Vistas:** Blade, JavaScript (Leaflet / OpenStreetMap para mapas y trazado de rutas)
- **Servicios Transaccionales:** API HTTP de correo (Brevo) para recuperación de contraseñas
- **Contenedorización & Despliegue:** Docker, Render (Hosting)
- **Control de Versiones:** Git & GitHub

---

## 📁 Estructura del Proyecto

```text
Jard-n-Bike/
├── app/                  # Controladores, Modelos y Reglas de Negocio (Core Laravel)
├── bootstrap/            # Configuración del entorno y proxies HTTPS
├── config/               # Archivos de configuración de servicios y DB
├── database/             # Migraciones, factories y seeds de estaciones/bicicletas
├── public/               # Assets públicos, JS de mapas y QR
├── resources/            # Vistas Blade (Interfaz cliente y Panel Operativo)
├── routes/               # Rutas de la API y Vistas de Autenticación
├── Dockerfile            # Configuración para contenedorización y despliegue
└── .env.example          # Plantilla de variables de entorno


```

👤 Autor
Desarrollado integralmente por Richar Torres

Desarrollador Backend & Soporte Técnico TI

GitHub: @richartorres
