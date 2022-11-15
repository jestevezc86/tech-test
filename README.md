# Bildia Test

---

Symfony version: 5.4 <br>

---

La prueba consiste en crear una API REST con una serie de endpoints y una aplicación en front que consuma dicha API

Requisitos obligatorios:

- Refactor del controlador DefaultController
- Creación de los siguientes enpoints en back:
  - getMunicipalities
  - saveMunicipality
  - deleteMunicipality
  - Endpoint que dando (X>0) IDs de provincias, sume todos sus habitantes y devuelva que % es del total de población
- Creación (en twig o cualquier framework de front) de una aplicación que consuma los siguientes endpoints via Ajax:
  - getMunicipalities
  - saveMunicipality
  - deleteMunicipality

Requisitos opcionales:

- Crear una caché en Redis para los endpoints
- Documentación de la API (por ejemplo con Swagger)

## Instalación
Clonar el repositorio
````shell
git clone git@github.com:crackencode/bildia-tech-test.git
````

Crear el archivo .env.local copiando .env (dejar las variables igual)
````shell
cp app/.env app/.env.local
````

Levantar los contenedores Docker
````shell
docker-compose --env-file=app/.env.local up -d --build
````

Instalar las librerías a través de Composer
````shell
docker exec php composer install
````

Correr las migraciones
````shell
docker exec php php bin/console doctrine:migrations:migrate
````

You can now access the server at http://localhost:8080

# Routes

    +----------+---------------------------------------------------+------------------------------------+
    | Method   | URI                                               | Params                             |
    +----------+---------------------------------------------------+------------------------------------+
    | GET      | //municipality/{cardinal}                         | ?municipalities=[{id},{id},{...}]  |
    +----------+---------------------------------------------------+------------------------------------+
