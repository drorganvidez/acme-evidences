## Acerca de Acme-Evidences

Acme-Evidences es una app desarrollada en curso 2019/2020 para la asignatura Evolución y Gestión de la Configuración, 4º de Ingeniería del Software, Universidad de Sevilla

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## ¿Cómo desplegar?

- Clonar repositorio
- Aplicar "composer" para descargar todas las dependencias
- Crear archivo ".env" en la raíz con los siguientes datos:

APP_NAME=Acme-Evidences
APP_ENV=local
APP_KEY=base64:/t7CQJS0/JMHTq372jA9Rt8inXUKzDLLOCJolJ0vmng=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nombredelabasededatos
DB_USERNAME=nombredelusuario
DB_PASSWORD=contraseña

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

## Licencia

Este proyecto es software de código abierto (open-source) bajo licencia Mit [MIT license](https://opensource.org/licenses/MIT).
