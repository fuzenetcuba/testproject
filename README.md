Plaza Test Project (Fuzenet)
==================

Requirements
------------

  * PHP 5.3 or higher;
  * and the [usual Symfony application requirements](http://symfony.com/doc/current/reference/requirements.html).

For checking the requirements is enough to execute the command:

```bash
php app/check.php
```

If the output contains the following:

```
[OK]
Your system is ready to run Symfony2 projects
```

You're good to go!

If unsure about meeting these requirements, install the application and browse the `http://localhost:8000/check.php` script to get more detailed information.

Installation
------------

Once the repository has been cloned all you need to do is:

  * Install all the dependencies of the system with the command `composer install` (this requires a working installation of composer).

  * Configure the database connection, copy the `app/config/parameters.yml.dis` file into `app/config/parameters.yml` and edit according to your needs, pay special attention to: `database_driver`, `database_host`, `database_name`, `database_user`, `database_password`.

  * Create the table structure, using the following command: `php app/console doctrine:schema:create`. This requires that the database has been created in the database.
  
  IMPORTANT
  *composer require knplabs/knp-paginator-bundle*
  * Publish assets with the command: `php app/console assets:install`

Usage
-----

If you have PHP 5.4 or higher, there is no need to configure a virtual host
in your web server to access the application. Just use the built-in web server:

```bash
$ cd folder/
$ php app/console server:run
```

This command will start a web server for the Symfony application. Now you can
access the application in your browser at <http://localhost:8000>. You can
stop the built-in web server by pressing `Ctrl + C` while you're in the
terminal.

Of course this is best suited for development and/or quick testing of the app.
The actual URL you need to point your browser is: <http://127.0.0.1:8000/fragance>

Virtual Host
------------

If you're using apache2 for publishing the web app use the following virtual host as an starting point:

```
<VirtualHost *:80>
    VirtualDocumentRoot "/Users/jorge/Sites/dior.dev/web"
    ServerName dior.dev

    <Directory "/Users/jorge/Sites/dior.dev/web">
        AllowOverride All
        Allow from All
        Options +FollowSymLinks
    </Directory>
</VirtualHost>
```
Check that the rewrite module is installed and enabled to get the Symfon2 routing system working as expected.

Create a new admin user
-----------------------

To create a new admin user use the following command on a terminal:

```
php app/console fos:user:create
```

The script will ask for the relevant information

Then is mandatory to promote the user to the `ROLE_ADMIN` role, using the following command:

```
php app/console fos:user:promote <username> ROLE_ADMIN
```

