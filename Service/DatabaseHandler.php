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

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * If pdo data is set we use it, if not doctrine is.
     */
    private function getConnection()
    {
        $usePdo = false;
        // Lógica para discriminar
        if ($this->connection === null) {
            if ($this->getContainer()->hasParameter('acilia_db_logger')) {
                $config =  $this->getContainer()->getParameter('acilia_db_logger');
                if (isset($config['pdo']) && isset($config['pdo']['url']) && isset($config['pdo']['user']) && isset($config['pdo']['password'])) {
                    $usePdo = true;
                } else {
                    throw new Exception('pdo configuration missing or not completed, (url, user and password must be set).');
                }
            }
        } 
        
        if ($usePdo) {
            $options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
            $this->connection = new \PDO($config['pdo']['url'], $config['pdo']['user'], $config['pdo']['password'], $options);
        } else {
            $this->connection = $this->doctrine->getManager()->getConnection();
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
