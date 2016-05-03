<?php

/*
 * This file is part of the Acilia Component / DBLogger Bundle.
 *
 * (c) Acilia Internet S.L. <info@acilia.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acilia\Bundle\DBLoggerBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * LogArchive
 *
 * Entity for storing the log registries, in archive mode.
 *
 * @author Andrés Montañez <andres@acilia.es>
 *
 * @ORM\Entity()
 * @ORM\Table(name="log_archive", options={"collate"="utf8_unicode_ci", "charset"="utf8", "engine"="MyISAM"})
 */
class LogArchive
{
    /**
     * @var integer
     *
     * @ORM\Column(name="log_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="log_channel", type="string", length=255, nullable=true)
     */
    private $channel;

    /**
     * @var string
     *
     * @ORM\Column(name="log_level", type="string", length=255, nullable=true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="log_message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="log_datetime", type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set channel
     *
     * @param  string $channel
     * @return Log
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set level
     *
     * @param  string $level
     * @return Log
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set message
     *
     * @param  string $message
     * @return Log
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set datetime
     *
     * @param  \DateTime $datetime
     * @return Log
     */
    public function setDatetime(DateTime $datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }
}
