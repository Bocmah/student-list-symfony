<?php
namespace StudentList\Database;

class Connection
{
    protected $config;

    /**
     * Establishes PDO connection via data passed through $config
     *
     * @return \PDO
     */
    public function make(): \PDO
    {
        try {
            return new \PDO(
                $this->config["database"]["connection"].";dbname=".$this->config["database"]["name"].
                ";charset=".$this->config["database"]["encoding"],
                $this->config["database"]["username"],
                $this->config["database"]["password"],
                $this->config["database"]["options"]
            );
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}