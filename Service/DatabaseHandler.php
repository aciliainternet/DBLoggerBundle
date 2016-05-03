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

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    protected function write(array $record)
    {
        if ($this->doctrine) {
            $connection = $this->doctrine->getManager()->getConnection();

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
