# PHP Template Project Technical Documentation

This README was automatically generated by PHPProjectInitializer.
If this paragraph is still present it probably means nobody's
bothered to write any project-specific documentation, yet.

See also:

- [PHP Application Architecture](PHP-APP-ARCHITECTURE.md)
- [Tutorials](tut/)
  - [Make a simple page](tut/pages.md)


## Folder structure

- ```composer.json``` - PHP package and dependency information, used by Composer.
- ```package.json``` - Node package and dependency information, used by NPM.
- ```init-*.php``` - Scripts to initialize global state in PHP.
- ```src/``` - Source code for this project.
  - ```main/``` - Primary application code, broken down by language
  - ```test/``` - Unit tests
  - ```views/``` - View templates
- ```target/``` - Compiled stuff.  Not checked in.
- ```node_modules/``` - External node libraries, managed my NPM.
- ```vendor/``` - External PHP libraries, managed by Composer.
- ```config/``` - Deployment configuration.
- ```www/``` - Serves as the 'document root' for your project.
  Contains files to be served directly and a ```.htaccess``` and ```bootstrap.php```
  to handle paths that don't correspond to existing files.
- ```Vagrantfile``` - configuration file for Vagrant.
- ```vm-files/``` - files to be imported into the vagrant Virtual machine.
- ```provision.sh``` - Virtual machine provisioning script.

Note that the ```src/<component>/<language>``` directory is a Maven
convention.  It's verbose, but gives enough information to keep
projects with multiple languages and components (unit tests, build
system, runtime, etc) organized.


## Database
### Create the database

If you're using Vagrant, don't do this.  Just ```vagrant up``` and you
should be good to go.  The following only applies if you want to run
outside of Vagrant.


```make create-database``` will attempt to create the database for you
based on configuration in ```config/dbc.json```.

If that fails (due to e.g. your system is not set up in a way for
which that script is designed) you can create the database yourself:

Set up a new postgres database by logging in as root
(```sudo -u postgres psql``` often does the trick)
and running:

```sql
CREATE DATABASE phptemplateprojectdatabase;
CREATE USER phptemplateprojectdatabaseuser WITH PASSWORD 'phptemplateprojectdatabasepassword';
GRANT ALL PRIVILEGES ON DATABASE phptemplateprojectdatabase TO phptemplateprojectdatabaseuser;
```

If you've changed the name of the database or user in
```config/dbc.json```, make the corresponding changes to the above
SQL.

### Initialize the database

Once a database exists and permissions are set up for our user, you
should be able to run ```make rebuild-database```, which will empty
the database and rebuild it from the upgrade scripts.


## Web serving

If you're using Vagrant,
you can just HTTP to whatever IP address you told Vagrant to use.  e.g.

  http://192.168.250.250/

If you accepted the default.

If you are running Apache (and Postgres, and other required programs)
on your development machine,
and this directory is checked out somewhere under the DocumentRoot or an Alias,
you may be able to just browse to the www directory and have things work.
For me, that's

  http://my-local-machine/~my-username/proj/PHPTemplateProject/www/

Most people probably need to run Vagrant though.
