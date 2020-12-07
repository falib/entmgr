# entmgr

## Requirements 
+ Apache / Nginx, PHP 7, MySQL

## Installation Steps

1. After your apache installation extract the files you downloaded or, clone the repo into the web root

2. In the Setup directory, please find the entmgr.sql and createadmin.php files.

3. Import the sql file into MySQL either via the cli <code> mysqladmin << entmgr.sql </code> or using phpmyadmin or any other RDBMS.

4. Once the database is imported, open the createadmin.php file in your favorite editor. 
   You can change the default userid and password from admin/admin as desired, please make sure to only edit the text between the ""

5. Once changes are saved, in the command line run <code>php createadmin.php</code>

6. You can now browse to http://(your ip or domain)/index.php and you will be redirected to the login page.

## Setup Crud API
1. Copy the api folder and setup a separate webroot
2. In api.php edit the config array
   <code> $config = new Config([
        'username' => 'dbuser',
        'password' => 'dbpass',
        'database' => 'entmgr'
        'drive'    => 'mysql'
        'address'  => 'localhost'
    ]);
</code>

**** IMPORTANT ****
ONCE LOGGED IN DELETE OR REMOVE THE Setup DIRECTORY FROM THE WEBROOT
