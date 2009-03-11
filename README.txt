To install the application, make sure the following preliminaries are met:
 
 - Apache 2
 - PHP 5
 - Mysql 5
 - a Linux system with GNU tar, bash and sed

Keep the following ready:
 
 * username and password of an administrative user for the database (typically MySQL root)
 * the database hostname, if not localhost
 * the name of the target directory beneath your Apache DocumentRoot where you want the app to be installed
 * username, password and database name that you want to be used by the application (there are default values you can use)
 * user and group that typically own files in your web directories (the files are chown'ed after installation)

To install:
 
 1. unpack the archive anywhere you like: `tar -xvzf pamdb.tar.gz`, this will create a temporary directory "pamdb" which you may delete afterwards
 2. run the install script as root: `sudo ./pamdb/scripts/install.sh`

The script explains everything while it is running. The operations are
fairly simple and the script is mainly for your convenience. It does
the following:
 
 * copy the files to a target directory where the web server can see them
 * create the database and a dedicated user that has privileges on that database
 * create the database tables
 * import the data exported from the current production server

I've tested everything on standard installations of openSuSE 10.3,
Ubuntu Server 8.04 and Ubuntu Server 8.10, but the installation should
work on almost any LAMP stack and the app itself should run on a WAMP
stack as well.

 
Hanno Fietz 
