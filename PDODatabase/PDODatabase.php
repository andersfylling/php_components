<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21/05/16
 * Time: 06:45
 */

namespace php_components;

/**
 * Class PDODatabase. To use the database instance, just extend a class from PDODatabase,
 *  after that you can use the $this->db instance.
 * @package php_components
 */
class PDODatabase extends \php_components_essentials\Events
{
    protected $db;

    public function __construct
    (
        array $config = [
            'host'      => 'localhost',
            'user'      => 'root',
            'password'  => ''
        ]
    )
    {
        /**
         * Establish a connection to your database server.
         */
        $this->db = new \PDO(
            'mysql:host=' . $config['host'], $config['user'], $config['password'],
            [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
            ]
        );
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->triggerEvent('success', 'Now using connected to server ' . $config['host']);
    }

    public function useDatabase
    (
        string $database = 'mydb'
    )
    {
        /**
         * If the database does not exist, it is created.
         */
        $name = "`".str_replace("`","``", $database)."`";
        $this->db->query('CREATE DATABASE IF NOT EXISTS ' . $name);
        $this->db->query('use ' . $name);

        $this->triggerEvent('useDatabase_success', 'Now using database `' . $database . '`');
    }

    public function createTablesIfNone
    (
        string $file = ''
    )
    {
        if ($file === '')
        {
            $this->triggerEvent('createTablesIfNone_error', 'No Database specified!');
        }

        /**
         *  Now check if the tables exists, if not they are created.
         */
        $exists = $this->db->query('SHOW TABLES');
        if ($exists->rowCount() === 0) {
            //create content
            $result = $this->db->exec( file_get_contents($file) );

            if ($result === false)
            {
                $this->triggerEvent('createTablesIfNone_error', $this->db->errorInfo());
            }
            else
            {
                $this->triggerEvent('createTablesIfNone_success', $result . ' rows were affected!');
            }
        }
        else
        {
            $this->triggerEvent('createTablesIfNone_success', 'There are existing tables.');
        }
    }

    /**
     * Sometimes you might just need the instance and don't have a class.
     *
     * @return \PDO instance
     */
    public function getConnection 
    ()
    {
        return $this->db;
    }
}