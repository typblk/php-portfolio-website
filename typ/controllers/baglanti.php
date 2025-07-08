<?php

    class Db {
        private $host = "localhost";
        private $user = "root";
        private $password = "";
        private $dbName = "typ";

        protected function connect() {
            try {
                $dsn = "mysql:host=".$this->host.";dbname=".$this->dbName;
                $pdo = new PDO($dsn, $this->user, $this->password);

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

                return $pdo;
            }
            catch(PDOException $e) {
                // Log directory and file
                $logDir = __DIR__ . '/../logs'; // Assuming typ/logs/
                $logFile = $logDir . '/db_errors.log';

                // Ensure log directory exists and is writable
                if (!is_dir($logDir)) {
                    // Try to create the directory
                    if (!mkdir($logDir, 0755, true) && !is_dir($logDir)) {
                        // Directory creation failed, perhaps log to a default location or handle error
                        // For now, we'll proceed and file_put_contents might fail if directory isn't there
                    }
                }

                // Check if directory is writable
                if (is_dir($logDir) && !is_writable($logDir)) {
                    // Try to make it writable (this might fail due to permissions)
                    @chmod($logDir, 0755);
                }
                
                // Prepare log message
                $timestamp = date("Y-m-d H:i:s");
                $errorMessage = $timestamp . " - Error: " . $e->getMessage() . PHP_EOL;

                // Append to log file
                if (is_writable($logDir)) {
                    file_put_contents($logFile, $errorMessage, FILE_APPEND);
                } else {
                    // Fallback or error handling if log directory is not writable
                    // For example, error_log("Could not write to log file: " . $logFile);
                }

                // Display generic error message to the user
                echo "Veritabanı bağlantı hatası. Lütfen daha sonra tekrar deneyin.";
            }
        }
    }


?>