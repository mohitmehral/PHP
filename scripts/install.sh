#!/bin/bash

#
# install.sh
#
# Installation script for PHP application "ECCP Policies and Measures"
#
# Author: Hanno Fietz <hanno.fietz@econemon.com>
#
#
# This script interactively installs the above application. It will
#     - insert configuration options in the appropriate files
#     - create a database
#     - create a database user
#     - create all database tables
#     - optionally fill the tables with data from a dump file
#     - place all PHP and image files int a folder visible to the web server
#
# The script will prompt you for
#     - target folder for the application
#     - password for the MySQL root user (to set up the user account and database)
#     - user name and password for the database that shall be used by the application
#     - name of the database where the application shall store the data
#     - optionally, a dump file containing data to import
#
# Run this script as a user with sufficient privileges to write to the target folder
# and chmod / chown the files therein.
#

#
# default setup variables
#
DB_HOST='localhost';
DB_USER_HOST="$DB_HOST";

DB_ROOT_USER='root';
DB_ROOT_PASSWD='';

DB_USER='pam_user';
DB_PASSWD='';

DB_DATABASE='pam_db';

WEB_TARGET_DIR='/var/www/html/eccp_pam';
WEB_USER='apache';
WEB_GROUP='apache';

#
# function declarations
#
function check_sed
{
  echo "checking for sed ...";
  if which sed > /dev/null 2>&1; then
    echo "sed found";
    HAVE_SED=1;
  else
    echo "
    Could not find stream editor sed, won't be able to manipulate files.
    After the installation is complete, you will have to manually edit
    some files and put them in the proper place or run them against the
    database. You will be presented with instructions later.
    ";
    HAVE_SED=0;
  fi
}

function bailout
{
  echo "quitting on error ...";
  if [ "x$1" != "x" ]; then
    cd "$1";
  fi
  exit 254;
}

#
# sort out directories
#

# we want the current directory to be able to interpret relative paths entered by the user ...
USER_WD=`pwd`;

# ... but we execute from the base directory to simplify things
BASE_DIR=`dirname "$0"`;
cd "$BASE_DIR/../";
# get the absolute pathname
BASE_DIR=`pwd`;

#
# check preliminaries
#

check_sed;

#
# prompt for setup variables
#

echo "
  You will now be asked for some local configuration options.
  The default values are given in square brackets. If you want
  to keep the default values, just hit <Enter> after the prompt.
  ";

echo "
  To create a new database user and the database itself, we need
  an existing database user with administrative privileges.
  ";
read -p "Please enter the username of a db administrator [$DB_ROOT_USER]: " tmp;
DB_ROOT_USER=${tmp:-$DB_ROOT_USER};
read -s -p "Please enter the password for db user '$DB_ROOT_USER' (will not be shown): " tmp;
DB_ROOT_PASSWD=${tmp:-$DB_ROOT_PASSWD};
echo "";

read -p "Please enter the username for the new db account used by the application [$DB_USER]: " tmp;
DB_USER=${tmp:-$DB_USER};
if [ "$DB_ROOT_USER" != "$DB_USER" ]; then
  read -s -p "Please enter the password for db user '$DB_USER' (will not be shown): " tmp;
  DB_PASSWD=${tmp:-$DB_PASSWD};
  echo "";
else
  echo "
  You set the administrator account '$DB_USER' to be used by the application.
  This is generally not recommended, but might be OK in your setup. You can
  configure a different account later in the file config.inc.php from the
  application's directory.
  ";
  DB_PASSWD=$DB_ROOT_PASSWD;
fi

read -p "Please enter the name of the database [$DB_DATABASE]: " tmp;
DB_DATABASE=${tmp:-$DB_DATABASE};
read -p "Please enter the name of the database host [$DB_HOST]: " tmp;
DB_HOST=${tmp:-$DB_HOST};
if [ "localhost" != "$DB_HOST" -a "127.0.0.1" != "$DB_HOST" ]; then
  echo "
  If you're not running the database on this machine, the database
  user must be set up with this machine's hostname, as seen from the
  database server. You can use a hostname of '%' to grant access for
  the new account from any host, although this is not recommended.
  ";
  DB_USER_HOST="`hostname 2>/dev/null`";
  read -p "Please enter the hostname where the application is going to run [$DB_USER_HOST]: " tmp;
  DB_USER_HOST=${tmp:-$DB_USER_HOST};
else
  DB_USER_HOST="$DB_HOST";
fi

if [ $HAVE_SED -eq 1 ]; then
  SED_CMD="";
  SED_CMD+="s/<<DB_USER>>/$DB_USER/;";
  SED_CMD+="s/<<DB_PASSWD>>/$DB_PASSWD/;";
  SED_CMD+="s/<<DB_HOST>>/$DB_HOST/;";
  SED_CMD+="s/<<DB_USER_HOST>>/$DB_USER_HOST/;";
  SED_CMD+="s/<<DB_DATABASE>>/$DB_DATABASE/;";

  echo "Initializing database ...";
  sed "$SED_CMD" scripts/db_init.mysql.sql.tmpl | mysql -u $DB_ROOT_USER -h $DB_HOST -p"$DB_ROOT_PASSWD" --default-character-set=utf8 --force;
  echo "Setting up tables ...";
  mysql -u $DB_USER -h $DB_HOST -p"$DB_PASSWD" -D "$DB_DATABASE" --default-character-set=utf8 < sql/tables.mysql.sql;
  echo "Setting up configuration ...";
  sed "$SED_CMD" scripts/config.inc.php.tmpl > htdocs/config.inc.php;
else
  cp scripts/config.inc.php.tmpl htdocs/config.inc.php;
  cp scripts/db_init.mysql.sql.tmpl sql/db_init.mysql.sql;
  
  echo "
  The stream editor 'sed' seems to not be available. Please open
  another shell and perform the following actions manually:

  In the files 'htdocs/config.inc.php' and 'sql/db_init.mysql.sql'
  from the installation base directory ($BASE_DIR),
  replace the following placeholders with the respective value
  (omit the quotes, of course):

      <<DB_USER>> with \"$DB_USER\"
      <<DB_PASSWD>> with \"$DB_PASSWD\"
      <<DB_HOST>> with \"$DB_HOST\"
      <<DB_USER_HOST>> with \"$DB_USER_HOST\"
      <<DB_DATABASE>> with \"$DB_DATABASE\"

  ";
  read -p "Press <Enter> when you're done, <Ctrl>-C to abort installation" foo;
  mysql -u $DB_ROOT_USER -h $DB_HOST -p"$DB_ROOT_PASSWD" --default-character-set=utf8 --force < sql/db_init.mysql.sql;
fi

rgDumps=(sql/data/data_*mysql.sql);
i=0;
iMax=${#rgDumps[*]};
urlDump="";
if [ $iMax -gt 0 ]; then
  echo "The following dump files are available for data import:";
  while [ $i -lt $iMax ];
  do
    echo "  [`expr $i + 1`] `basename ${rgDumps[$i]}`";
    i=`expr $i + 1`;
  done
  echo "";
  read -p "Enter the number of the file you would like to import, or <Enter> for none: " iSel;
  if [ "x$iSel" != "x" ]; then
    if [ $iSel -gt $iMax -o $iSel -lt 0 ]; then
      echo "Invalid selection.";
    else
      iSel=`expr $iSel - 1`;
      urlDump="${rgDumps[$iSel]}";
    fi
  fi
fi

if [ -z "$urlDump" ]; then
  read -p "If you want to provide your own dump file, enter a pathname. To skip data import, press <Enter>.
The file should be UTF-8 encoded or contain only ASCII characters: " urlDump;
  if [ "/" != `expr substr "$urlDump" 1 1` ]; then
    urlDump="$USER_WD/$urlDump";
  fi
fi

if [ -n "$urlDump" -a -r "$urlDump" ]; then
  echo "importing data from '$urlDump' ...";
  mysql -u $DB_USER -p"$DB_PASSWD" -h $DB_HOST -D $DB_DATABASE --default-character-set=utf8 < "$urlDump";
else
  echo "No dumpfile given or invalid pathname ('$urlDump'), skipping data import";
fi

echo "
  The application can now be deployed. Deployment consists of creating
  a folder inside the document root of your web server, copying all
  PHP and image files into that folder, and setting file ownership and
  access privileges appropriately.
  ";

read -p "Please enter the target folder to deploy the application to [$WEB_TARGET_DIR]: " tmp;
WEB_TARGET_DIR=${tmp:-$WEB_TARGET_DIR};
read -p "Please enter the name of the user who should own the target directory [$WEB_USER]: " tmp;
WEB_USER=${tmp:-$WEB_USER};
read -p "Please enter the name of the group who should own the target directory.
User '$WEB_USER' has to be a member of that group. [$WEB_GROUP]: " tmp;
WEB_GROUP=${tmp:-$WEB_GROUP};

echo "Creating target directory ...";
if [ "/" != `expr substr "$WEB_TARGET_DIR" 1 1` ]; then
  mkdir -p "$USER_WD/$WEB_TARGET_DIR" || bailout;
  cd "$USER_WD/$WEB_TARGET_DIR";
  WEB_TARGET_DIR=`pwd`;
  cd "$BASE_DIR";
else
  mkdir -p "$WEB_TARGET_DIR" || bailout;
fi

echo "Copying files ...";
cp -a htdocs/* "$WEB_TARGET_DIR/" || bailout;

echo "Changing file ownership ...";
chown -R ${WEB_USER}:${WEB_GROUP} "$WEB_TARGET_DIR" || bailout;

echo "Changing file permissions ...":
chmod -R og+rX "$WEB_TARGET_DIR"/* || bailout;
chmod -R og-w "$WEB_TARGET_DIR"/* || bailout;
chmod -R u+rwX "$WEB_TARGET_DIR"/* || bailout;
chmod 0755 "$WEB_TARGET_DIR" || bailout;

echo "Installation complete.";

exit 0;
