<?php 
namespace Config;

use CodeIgniter\Config\BaseConfig;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------------
| This file will contain the settings needed to access your Mongo database.
|
|
| ------------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| ------------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['write_concerns'] Default is 1: acknowledge write operations.  ref(http://php.net/manual/en/mongo.writeconcerns.php)
|	['journal'] Default is TRUE : journal flushed to disk. ref(http://php.net/manual/en/mongo.writeconcerns.php)
|	['read_preference'] Set the read preference for this connection. ref (http://php.net/manual/en/mongoclient.setreadpreference.php)
|	['read_preference_tags'] Set the read preference for this connection.  ref (http://php.net/manual/en/mongoclient.setreadpreference.php)
|
| The $config['mongo_db']['active'] variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
*/
class MongodbConfig extends BaseConfig
{
    public $config=[];
    public function getMongodbConfig(){
        $this->config['mongo_db']['default']['no_auth'] = true;
        $this->config['mongo_db']['default']['hostname'] = 'localhost';
        $this->config['mongo_db']['default']['port'] = '27017';
        $this->config['mongo_db']['default']['username'] = '';
        $this->config['mongo_db']['default']['password'] = '';
        $this->config['mongo_db']['default']['database'] = 'appeal';
        $this->config['mongo_db']['default']['db_debug'] = TRUE;
        $this->config['mongo_db']['default']['return_as'] = 'object';
        $this->config['mongo_db']['default']['write_concerns'] = (int)1;
        $this->config['mongo_db']['default']['journal'] = TRUE;
        $this->config['mongo_db']['default']['read_preference'] = 'primary';
        $this->config['mongo_db']['default']['read_concern'] = 'local'; //'local', 'majority' or 'linearizable'
        $this->config['mongo_db']['default']['legacy_support'] = TRUE;
        return $this->config;
    }
}

