## Acerca de Acme-Evidences

Acme-Evidences es una app desarrollada en curso 2019/2020 para la asignatura Evolución y Gestión de la Configuración, 4º de Ingeniería del Software, Universidad de Sevilla

## ¿Cómo desplegar?

- Clonar repositorio
- Aplicar "composer" para descargar todas las dependencias
- Crear archivo ".env" en la raíz con los siguientes datos:

APP_NAME=Acme-Evidences<br>
APP_ENV=local<br>
APP_DEBUG=true<br>
APP_URL=http://localhost<br>
<br>
LOG_CHANNEL=stack<br>
<br>
DB_CONNECTION=mysql<br>
DB_HOST=localhost<br>
DB_PORT=3306<br>
DB_DATABASE=nombredelabasededatos<br>
DB_USERNAME=nombredelusuario<br>
DB_PASSWORD=contraseña<br>
<br>
BROADCAST_DRIVER=log<br>
CACHE_DRIVER=file<br>
QUEUE_CONNECTION=sync<br>
SESSION_DRIVER=file<br>
SESSION_LIFETIME=120<br>

## Generar APP Key

Es necesario generar una APP Key. En la raíz, introducir el siguiente comando por consola:

php artisan key:generate

## Licencia

Este proyecto es software de código abierto (open-source) bajo licencia Mit [MIT license](https://opensource.org/licenses/MIT).
