# Proyecto Integrador - Teleasistencia

## Descripción

Este proyecto es una aplicación web diseñada para la gestión de usuarios de un servicio de teleasistencia. Permite registrar y administrar pacientes, operadores, llamadas y alarmas con el objetivo de mejorar la atención y seguimiento de personas en situación de dependencia.

La aplicación está diseñada para ser accesible desde PC, tablets y móviles, garantizando compatibilidad con diferentes dispositivos.

## Tabla de Contenidos

1. [Tecnologías utilizadas](#tecnologias-utilizadas)
2. [Puesta en marcha](#puesta-en-marcha)
3. [Lista de Contribuidores](#lista-de-contribuidores)

## Tecnologías utilizadas

Este proyecto ha sido desarrollado utilizando las siguientes tecnologías:

- **Backend**: Laravel
- **Frontend**: Vue.js 
- **Base de Datos**: MySQL 
- **Contenedores**: Docker para la gestión y despliegue de backend
- **Integración Continua**: Workflows personalizados en GitHub Actions
- **Despliegue en la Nube**: AWS (EC2)
- **Control de Versiones**: GitHub

## Puesta en Marcha

Para ejecutar el proyecto localmente, sigue estos pasos:

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/Proyecto-Integrador-Teleasistencia/Backend.git
   ```
2. Instalar dependencias del backend:
   ```bash
   cd backend
   npm install
   ```
3. Iniciar el servidor backend:
   ```bash
   sudo docker exec laravel-app bash -c '
       composer install && 
       composer dump-autoload &&
       npm install && 
       npm run build && 
       sed -i "s#^APP_URL=.*#APP_URL=https://back.projecte2.ddaw.es#" .env
   '
   
   sudo docker compose up -d
   sudo docker compose exec laravel.test php artisan migrate:fresh --seed --force

   sudo chown -R $USER:$USER $DEPLOY_DIR
   sudo chmod -R 777 /var/www/back/ 
   ```

## Lista de Contribuidores

| Nombre         | Rol           | 
|---------------|--------------|
| Carlos Pérez      | Desarrollador Backend | 
| Samuel Pastor     | Desarrollador Frontend | 
| Daniel Cortés     | Desarrollador Frontend | 
| Samuel Carbonell     | Desarrollador UX/UI & DevOps | 


