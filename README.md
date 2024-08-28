
# Prex Giphy

## Ejecutar el Proyecto con Docker Compose

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

He creado algunos tests unitarios para asegurarme de que todo funcione como debe. Aquí te dejamos una lista de los tests que hemos generado:

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
También ver achivo: [diagrama_casos_uso.png](diagrama_casos_uso.png)
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
