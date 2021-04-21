# Metabiblioteca 
##METABIBLIOTECA - PRUEBA LARAVEL-REST 

###📄 Desccripcion 
Objetivo General
Crear un proyecto del tipo CRUD en Laravel   y Mysql (ORM Eloquent) en el cual se deben crear cuatro (4) endpoints del tipo API REST:
Los Endpoint a crear SON:

```route
POST (CREAR): /api/books/create/{isbn}
```
```route
POST (ELIMINAR): /api/books/delete/{isbn}
```
````route
GET (LISTAR): /api/books/
````
````route
GET (VER DETALLE): /api/books/{isbn}
````
### 📖 📋 Especificaciones:

###1. Crear ( /api/books/create/{isbn}):**
A partir del parámetro enviado {ISBN - OBLIGATORIO}, se debe consumir un servicio API-REST provisto por el portal openlibrary (https://openlibrary.org/)  y guardarlo en una base de datos MYSQL (guardar en la base de datos solamente los siguientes datos: Título del libro, Autor/Autores (uno a muchos) y Carátula (Cover Large)).
Se debe dar una respuesta en JSON (“Éxito”, “Error”, “ISBN  no encontrado”, etc...).
El Endpoint del servicio de OpenLibrary, se encuentra en la URL (Metodo GET): https://openlibrary.org/api/books

Ejemplo de uso del Servicio:
curl 'https://openlibrary.org/api/books?bibkeys=ISBN:1878058517&jscmd=data&format=json'
La base de datos, su modelo y su estructura se deben definir previamente usando migraciones.
Adjuntamos un listado de ISBN de algunos libros para que prueben el funcionamiento del Servicio Web :
**0120121123, 0760054487, 0760034400, 0619101857**

###2. Listar (/api/books/):

Listar los ítems (en JSON) paginados de 2 en 2. Ver documentación e implementar Resources y Collections (Requerido):


-Ayuda sobre cómo devolver resultados paginados en JSON: https://laravel.com/docs/8.x/eloquent-resources



###3.Eliminar (/api/books/delete/{isbn}):

Eliminar dado un ISBN como parámetro. Validar el parámetro (Obligatorio) y dar respuesta en JSON.


###4.Ver Detalle (/api/books/{isbn}):

Dado un ISBN como parámetro, ver el detalle de este (Título del libro, Isbn, Autor/Autores (uno a muchos) y Cover Large). Validar el parámetro (Obligatorio) y dar respuesta en XML.


####Ejemplo:


````Xml
<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<Libros>

  <Libro>

      <Titulo>Don Quijote de la Mancha</Titulo>

      <Isbn>Don Quijote de la Mancha</Isbn>

      <Autores>

         <Autor>Miguel de Cervantes</Autor>

      </Autor>

      <Caratula>https://example.org/caratula.png</Caratula>

  </Libro>

</Libros>
````
####Error XML
````xml
<?xml version="1.0" encoding="utf-8" standalone="yes" ?>

<Libros>

Error Libro no encontrado

</Libros>

````
**Se evaluarán:**

- Migraciones correspondientes (debe funcionar el comando php artisan migrate) que creen las tablas necesarias para completar los requerimientos.

- Código limpio y sin errores.

- Buenas prácticas (escribir clases, métodos y campos en inglés usando convenciones)

**Tener en cuenta (IMPORTANTE):**

- Mostrar en un entorno web las vistas de listar (paginadas) y la posibilidad de eliminar los libros con un botón en el listado.

- El proyecto será probado con POSTMAN, las peticiones serán a las url que se construyan en el api, probar las peticiones antes.

- Se requiere desarrollar específicamente un “API REST”, no un proyecto WEB, las rutas deben estar en “routes/api.”

- Al terminar, subir el código a un repositorio en github con acceso libre. Luego dar respuesta en correo con el enlace de este repositorio y solicitar entrevista personalizada.


### Copyright
Creado por Sebastian Carvajal 2021 - Laravel [API]
