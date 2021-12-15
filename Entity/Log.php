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
 * Log
 *
 * Entity for storing the log registries.
 *
 * @author Andrés Montañez <andres@acilia.es>
 *
 * @ORM\Entity()
 * @ORM\Table(name="log", options={"collate"="utf8_unicode_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Log
{
    /**
     * @ORM\Column(name="log_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="log_channel", type="string", length=255, nullable=true)
     */
    private string $channel;

    /**
     * @ORM\Column(name="log_level", type="string", length=255, nullable=true)
     */
    private string $level;

    /**
     * @ORM\Column(name="log_message", type="text", nullable=true)
     */
    private string $message;

    /**
     * @ORM\Column(name="log_datetime", type="datetime", nullable=false)
     */
    private \DateTimeInterface $datetime;

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
