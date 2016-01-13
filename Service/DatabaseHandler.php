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
            $log = new Log();
            $log->setChannel($record['channel'])
                ->setMessage($record['message'])
                ->setLevel($record['level_name'])
                ->setDatetime($record['datetime']);

            $this->doctrine->getManager()->persist($log);
            $this->doctrine->getManager()->flush($log);
        }
    }
}