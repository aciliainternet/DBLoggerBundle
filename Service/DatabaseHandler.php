<?php

/*
 * This file is part of the Acilia Component / DBLogger Bundle.
 *
 * (c) Acilia Internet S.L. <info@acilia.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acilia\Bundle\DBLoggerBundle\Service;

use Acilia\Bundle\DBLoggerBundle\Entity\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class DatabaseHandler extends AbstractProcessingHandler
{
    protected $doctrine;
    protected $connection;
    protected $config;

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * If pdo data is set we use it, if not doctrine is.
     */
    private function getConnection()
    {
        if ($this->connection === null) {
            $usePdo = false;
            if (isset($this->config['pdo'])) {
                if (!isset($this->config['pdo']['url']) || 
                    !isset($this->config['pdo']['user']) || 
                    !isset($this->config['pdo']['password'])) {
                    throw new Exception('pdo configuration missing or not completed, (url, user and password must be set).');
                } else {
                    $usePdo = true;
                }
            }
        
            if ($usePdo) {
                $options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
                $this->connection = new \PDO($this->config['pdo']['url'], $this->config['pdo']['user'], $this->config['pdo']['password'], $options);
            } else {
                $this->connection = $this->doctrine->getManager()->getConnection();
            }
        } 

        return $this->connection;
    }

    protected function write(array $record)
    {
        if ($connection = $this->getConnection()) {

            $sql = 'INSERT INTO log (log_id, log_channel, log_message, log_level, log_datetime) VALUES (NULL, ?, ?, ?, ?)';
            $stmt = $connection->prepare($sql);

            $stmt->bindValue(1, $record['channel']);
            $stmt->bindValue(2, $record['message']);
            $stmt->bindValue(3, $record['level_name']);
            $stmt->bindValue(4, $record['datetime']->format('Y-m-d H:i:s'));

            $stmt->execute();
        }
    }
}
