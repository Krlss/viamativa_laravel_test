# API de ejemplo en Laravel

Esta es una API de ejemplo desarrollada en Laravel 10. La API proporciona endpoints para gestionar salas de cine y películas.

## Instalación

1. Clona este repositorio en tu máquina local.
2. Ejecuta el comando `composer install` para instalar las dependencias de Laravel.
3. Crea una copia del archivo `.env.example` y renómbrala como `.env`.
4. Genera una nueva clave de aplicación ejecutando el comando `php artisan key:generate`.
5. Configura la conexión de base de datos en el archivo `.env` según tus preferencias.
6. Ejecuta las migraciones de la base de datos con el comando `php artisan migrate`

## Documentación de la API

La documentación de la API está generada automáticamente mediante Swagger. Para generar la documentación, utiliza el siguiente comando:
`   php artisan l5-swagger:generate
  `
Una vez generada, puedes acceder a la documentación de la API en la siguiente URL: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

La documentación proporciona detalles sobre los endpoints disponibles, los parámetros requeridos y las respuestas esperadas.

## Endpoints

A continuación se muestran los principales endpoints disponibles en la API:

### Películas

-   `GET /api/films`: Obtiene la lista de todas las películas.
-   `GET /api/films/{id}`: Obtiene los detalles de una película específica.
-   `POST /api/films`: Crea una nueva película.
-   `PUT /api/films/{id}`: Actualiza los datos de una película existente.
-   `DELETE /api/films/{id}`: Elimina una película existente.
-   `GET /api/films/count/{date_published}`: Obtiene la cantidad de películas por fecha de publicación.

### Salas de cine

-   `GET /api/halls`: Obtiene la lista de todas las salas de cine.
-   `GET /api/halls/{id}`: Obtiene los detalles de una sala de cine específica.
-   `POST /api/halls`: Crea una nueva sala de cine.
-   `PUT /api/halls/{id}`: Actualiza los datos de una sala de cine existente.
-   `DELETE /api/halls/{id}`: Elimina una sala de cine existente.
-   `GET /api/halls/review/{name}`: Verifica si una sala de cine está llena o no según su nombre.
