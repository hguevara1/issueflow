# IssueFlow

Proyecto desarrollado durante el curso **IFCT092PO — Programación Web con Software Libre**.

IssueFlow será una aplicación web para la gestión de incidencias. El proyecto irá creciendo progresivamente durante el curso mientras aprendemos PHP y Symfony.

## Tecnologías

- PHP 8.4+
- Symfony 8.1
- Composer
- Git

Más adelante incorporaremos Twig, Doctrine, MySQL, testing, APIs y otras herramientas.

## Instalación

Crear el proyecto:

```bash
composer create-project symfony/skeleton:"8.1.*" issueflow
```

Entrar en la carpeta:

```bash
cd issueflow
```

Instalar los componentes para una aplicación web:

```bash
composer require webapp
```

## Ejecutar el proyecto

```bash
php -S localhost:8000 -t public
```

Abrir en el navegador:

```text
http://localhost:8000
```

## Comandos útiles

Información del proyecto:

```bash
php bin/console about
```

Ver las rutas:

```bash
php bin/console debug:router
```

Ver los comandos disponibles:

```bash
php bin/console
```

## Estado del proyecto

Proyecto iniciado en la **Clase 08**.

Primer objetivo:

- Crear la página principal.
- Crear las primeras rutas.
- Crear los primeros controllers.
- Comenzar la estructura de IssueFlow.