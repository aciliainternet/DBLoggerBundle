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
     * @ORM\Column(name="log_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="log_channel", type="string", length=255, nullable=true)
     */
    private $channel;

    /**
     * @ORM\Column(name="log_level", type="string", length=255, nullable=true)
     */
    private $level;

    /**
     * @ORM\Column(name="log_message", type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(name="log_datetime", type="datetime", nullable=false)
     */
    private $datetime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getDatetime(): \DateTimeInterface
    {
        return $this->datetime;
    }
}
