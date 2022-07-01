<?php

require_once(__DIR__ . "./Base.php");
require_once(__DIR__ . "./Session.php");

class Database extends Base
{
    /**
     * Connect to DB, return PDO instance
     *
     * @return PDO
     */
    public function connect(): PDO
    {
        try {
            $pdo = new PDO($this->getDns(), $this->getEnvValue("db_username"), $this->getEnvValue("db_password"));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (Exception $exception) {
            return die("ERROR: " . $exception->getMessage());
        }
    }

    /**
     * Get DNS value
     *
     * @return string
     */
    public function getDns(): string
    {
        $env_values = $this->loadEnv();
        return $env_values['DB_DRIVER'] . ":dbname=" . $env_values['DB_DATABASE'] . ";host=" . $env_values['DB_HOST'];
    }

    /**
     * Create database if not exists
     *
     * @return void
     */
    public function createDatabase(): void
    {
        $_SQL = "CREATE DATABASE IF NOT EXISTS " . $this->getEnvValue("db_database");

        try {
            $pdo = $this->connect();
            $statement = $pdo->prepare($_SQL);
            $statement->execute();
        } catch (Exception $exception) {
            die("ERROR: " . $exception->getMessage());
        }
    }

    /**
     * Create database schemas
     *
     * @param string $table
     * @return void
     */
    public function prepareDatabaseSchema(string $table): void
    {
        $_SQL = "CREATE TABLE IF NOT EXISTS $table (
            id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
            title VARCHAR(1400) NOT NULL,
            description TEXT
        );";

        try {
            $pdo = $this->connect();
            $statement = $pdo->prepare($_SQL);
            $statement->execute();
        } catch (Exception $exception) {
            die("ERROR: " . $exception->getMessage());
        }
    }

    /**
     * Select all or specific columns from a given table
     *
     * @param string $table
     * @param array $columns
     * @return array
     */
    public function select($table, $columns): array
    {
        $_SQL = "SELECT " . implode(",", $columns) . " FROM $table";

        try {
            $pdo = $this->connect();
            $statement = $pdo->prepare($_SQL);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exception) {
            if ($exception->getCode() == "42S02") {
                $this->prepareDatabaseSchema($table);
                return [];
            } else {
                die("ERROR: " . $exception->getMessage());
            }
        }
    }

    /**
     * Insert New Data into a given table
     *
     * @param string $table
     * @param array $data
     * @return boolean
     */
    public function insert(string $table, array $data): bool
    {
        $prepared = $this->prepareData($data);
        $_SQL = "INSERT INTO $table (" . implode(",", $prepared['keys']) . ") VALUES (" . implode(",", $prepared['placeholders']) . ");";

        try {
            $pdo = $this->connect();
            $statement = $pdo->prepare($_SQL);

            for ($i = 0; $i < count($prepared['values']); $i++) {
                $statement->bindValue($prepared['placeholders'][$i], $prepared['values'][$i]);
            }

            $statement->execute();

            $session = new Session("success", "record created successfully");
            header("Location: ../index.php");
        } catch (Exception $exception) {
            if ($exception->getCode() == "42S02") {
                $this->prepareDatabaseSchema($table);
                $this->insert($table, $data);
            } else {
                die("ERROR: " . $exception->getMessage());
            }
        }

        return true;
    }

    /**
     * Update database table
     *
     * @param string $table
     * @param [type] $data
     * @return boolean
     */
    public function update(string $table, $data): bool
    {
        if (
            isset($data['title']) && !is_null($data['title']) ||
            isset($data['description']) && !is_null($data['description'])
        ) {
            $_SQL = "UPDATE $table SET ";

            if (isset($data['title']) && !is_null($data['title']))
                $_SQL .= " title = '" . $data['title'] . "'";

            if (isset($data['description']) && !is_null($data['description']) && isset($data['title']) && !is_null($data['title']))
                $_SQL .= " ,description = '" . $data['description'] . "'";

            if (isset($data['description']) && !is_null($data['description']) && !isset($data['title']) && is_null($data['title']))
                $_SQL .= " description = '" . $data['description'] . "'";

            $_SQL .= " WHERE id = " . $data["id"] . ";";


            try {
                $pdo = $this->connect();
                $statement = $pdo->prepare($_SQL);
                $statement->execute();
            } catch (Exception $exception) {
                die("ERROR: " . $exception->getMessage());
            }
        }

        return true;
    }

    /**
     * Prepare data for insert
     *
     * @param array $data
     * @return array
     */
    protected function prepareData(array $data): array
    {
        $prepared = [];

        foreach ($data as $key => $value) {
            $k = trim($key);
            $v = trim($value);

            $prepared['keys'][] = $k;
            $prepared['placeholders'][] = ":" . $k;
            $prepared['values'][] = $v;
        }

        return $prepared;
    }
}
