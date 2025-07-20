 Proyecto Bienes Raíces MVC

![Imagen del proyecto](./assets/App-Salón.png)

## Indice
* [Descripción](#descripción-del-proyecto)
* [Funcionalidades](#funcionalidades-del-proyecto)
* [Tecnologías](#tecnologías-utilizadas)
* [Acceso](#acceso-al-proyecto)

## Descripción del proyecto
Este es un proyecto que sirve como aplicación para una barberia o un salónde belleza, la cual los clientes pueden agendar una cita en función del día y la hora que ellos soliciten. Cabe destacar, que dichas citas solo pueden realizarse a partir del día en cuestión, es decir, no pueden agendarse en dias o meses anteriores al actual. La misma fue realizada haciendo uso de la arquitectura MVC junto con PHP puro.

## Funcionalidades del proyecto
- `Login de usuario`: Formulario para validación de acceso del usuario.
- `Panel de control`: Panel encargado de controlar la información y las acciones presentes en la aplicación.
- `Creación de servicios`: Acción que tiene de tarea crear las servicios y guardarlas en la base de datos.
- `Actualización de servicios`: Acción que tiene de tarea actualizar las servicios y guardarlas en la base de datos.
- `Eliminación de servicios`: Acción que tiene de tarea borrar las servicios, incluyendo en la base de datos.
- `Comprobación/validacion de cuentas via emails`: Formulario para validacion de usuario mediante envio de emails.
- `Reseteo de password`: Envio de emails para restablecer password.
- `Creacion de citas`: Acción que tiene de tarea crear las citas para los usuarios.
- `Filtración de citas`: Se pueden filtrar las citas dependiendo de la fecha en que fueron solicitadas.

## Tecnologías utilizadas
- PHP
- Composer (PHPMailer)
- Javascript
- Sass
- Gulp
- SQL (MariaDB)

## Acceso al proyecto
Para poder hacer uso del proyecto, simplemente debes dirigirte mediante la terminal de comandos o mediante la interfaz gráfica del sistema de archivos de tu preferencia (windows, mac o linux) al directorio `/public` y posteriormente crear el servidor local con PHP

```bash
cd /public
```

```php
php -S localhost:puerto_de_preferencia
```

Con respecto al apartado de los estilos, simplemente debes poner en tu terminal el siguiente comando:

```js
npm install
npm run dev