
# Prex Giphy

## Ejecutar el proyecto con Docker Compose

Para poner en marcha este proyecto, necesitas tener Docker y Docker Compose instalados en tu máquina. Sigue estos pasos:

1. **Clona el repositorio**:
   ```bash
   git clone https://github.com/tu-usuario/tu-repositorio.git
   cd tu-repositorio
   ```

2. **Crea los contenedores y levanta el proyecto**:
   ```bash
   docker-compose up -d --build
   ```

   Esto levantará los contenedores necesarios (como la aplicación y la base de datos) y el proyecto estará disponible en `http://localhost:8000`.


3. **Migraciones**:
No es necesario ejecutar las migraciones, ya que las mismas se generan junto con los seed al iniciar el proyecto con Docker Compose. _Ver archivo **.docker/run.sh**_.
 De ser necesario, puede ejecutarse el siguiente comando:
   ```bash
   docker-compose exec app php artisan migrate
   ```

---

## Tests Unitarios

He creado algunos tests unitarios para asegurarme de que todo funcione como debe.
Aqui se listan los test unitarios generados:

- `AuthControllerTest`
  - `testSigninValidatesRequest`
  - `testSigninReturnsUnauthorized`
  - `testSigninReturnsTokenOnSuccess`

- `GiphyControllerTest`
  - `testSearchValidatesRequest`
  - `testSearchReturnsGifs`
  - `testFindByIDReturnsGif`
  - `testFindByIDReturns404WhenGifNotFound`

- `UserControllerTest`
  - `testAddFavoriteValidatesRequest`
  - `testAddFavoriteReturnsDuplicateEntryError`
  - `testAddFavoriteReturnsOtherError`
  - `testAddFavoriteReturnsSuccess`

- `GiphyClientTest`
  - `testSearchReturnsGifs`
  - `testSearchReturnsEmptyGifsWhenNoResults`
  - `testFindByIDReturnsGif`
  - `testFindByIDReturnsNullWhenGifNotFound`

## Tests Feature

He creado algunos tests feature para asegurarme de que todos los controladores responden correctamente para cada caso.
Aqui dejo una lista de todos los test feature generados:

### `AuthControllerTest`
- `testSigninReturnsTokenForValidCredentials`
- `testSigninReturnsUnauthorizedForInvalidCredentials`
- `testSigninReturnsBadRequestForInvalidData`

### `GiphyControllerTest`
- `testSearchReturnsGifsSuccessfully`
- `testSearchReturnsBadRequestForInvalidData`
- `testFindByIDReturnsGifSuccessfully`
- `testFindByIDReturnsNotFoundForInvalidID`

### `UserControllerTest`
- `testAddFavoriteSuccessfully`
- `testAddFavoriteReturnsConflictForDuplicateEntry`
- `testAddFavoriteReturnsBadRequestForInvalidData`

### Cómo Ejecutar los Tests

Se puede ejecutar todos los tests unitarios con el siguiente comando:

```bash
docker-compose exec app php artisan test
```

Este comando correrá todos los tests y mostrará los resultados en la terminal.

---

## Listado de rutas de endpoints en la API

- **POST /v1/auth/signin** - Iniciar sesión y obtener un token de acceso.
- **GET /v1/gif/search** - Buscar GIFs en Giphy (requiere autenticación).
- **GET /v1/gif/{id}** - Obtener un GIF por su ID (requiere autenticación).
- **POST /v1/user/favorite** - Agregar un GIF a los favoritos del usuario (requiere autenticación).

---

## Diagrama de casos de uso

Acceder desde: https://excalidraw.com/#room=7b9f0759b0eefb68690c,Ms9R2CFfxF9_nJXieat41A
También ver achivo: [diagrama_casos_uso.png](diagrama_casos_uso.png) o [diagrama_casos_uso.pdf](diagrama_casos_uso.pdf)


## Diagrama entidad relación (DER)

```
Users (users)
- * id (PK): big int unsigned
- email: varchar(255)
- name: varchar(255)
- email_verified_at: timestamp
- password: varchar(255)
- remember_token: varchar(100)
- created_at: timestamp
- updated_at: timestamp


User favorites (user_favorites)
- * id (PK): big int unsigned
- user_id (FK, users): big int unsigned
- gif_id: varchar(255)
- alias: varchar(255)
- created_at: timestamp
- updated_at: timestamp
                               
```

---

## Registro continuo de actividades

En el archivo que se genera [storage/logs/laravel.log](storage/logs/laravel.log), se registran todas los REQUEST y RESPONSE que se generan via API Rest.
Estos registros cuentan con la siguiente información:

- Usuario que realizo la petición
- Servicio consultado
- Cuerpo de la petición
- Código HTTP de la respuesta.
- Cuerpo de la respuesta.
- IP de origen de la consulta.

Ejemplo:
```logs
[2024-08-29 22:28:12] local.INFO: incoming request {"USER_ID":1,"REQUEST_BODY":{"query":"cat","limit":"10","offset":"0"},"STATUS":200,"RESPONSE":"{\"gifs\":{\"gifs\":[{\"id\":\"CjmvTCZf2U3p09Cn0h\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/leroypatterson-cat-glasses-CjmvTCZf2U3p09Cn0h\",\"title\":\"Im Ready Lets Go GIF by Leroy Patterson\"},{\"id\":\"MDJ9IbxxvDUQM\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/cat-kisses-hugs-MDJ9IbxxvDUQM\",\"title\":\"In Love Cat GIF\"},{\"id\":\"mlvseq9yvZhba\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/funny-cat-mlvseq9yvZhba\",\"title\":\"Bored Cat GIF\"},{\"id\":\"GeimqsH0TLDt4tScGw\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/vibes-vibing-vibin-GeimqsH0TLDt4tScGw\",\"title\":\"Vibing White Cat GIF\"},{\"id\":\"8vQSQ3cNXuDGo\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/cat-moment-remember-8vQSQ3cNXuDGo\",\"title\":\"Cat Remember GIF\"},{\"id\":\"l0ExdMHUDKteztyfe\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/smoke-smoking-chibi-l0ExdMHUDKteztyfe\",\"title\":\"cat smoking GIF by sheepfilms\"},{\"id\":\"C9x8gX02SnMIoAClXa\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/C9x8gX02SnMIoAClXa\",\"title\":\"Cat Sunglasses GIF\"},{\"id\":\"lJNoBCvQYp7nq\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/reddit-doing-lJNoBCvQYp7nq\",\"title\":\"Cat Working GIF\"},{\"id\":\"vFKqnCdLPNOKc\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/cat-lol-vFKqnCdLPNOKc\",\"title\":\"White Cat Hello GIF\"},{\"id\":\"IzQySOB4vcL7ttdqqN\",\"url\":\"https:\\/\\/giphy.com\\/gifs\\/cat-crazy-blue-IzQySOB4vcL7ttdqqN\",\"title\":\"Cat Yes GIF by happydog\"}],\"pagination\":{\"total_count\":500,\"limit\":10,\"offset\":0}}}","IP":"172.19.0.1"} 
```

---

## Colección de POSTMAN

Collection: [Prex.postman_collection.json](postman_collection/Prex.postman_collection.json)

Environment: [postman_collection/](storage/logs/laravel.log)[local.postman_environment.json](postman_collection/local.postman_environment.json)
