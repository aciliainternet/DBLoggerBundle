<?php
namespace Acilia\Bundle\DBLoggerBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;

class ArchiveCommand extends Command
{
    protected static $defaultName = 'acilia:dblogger:archive';

    public const ARCHIVE_DAYS = 30;

    protected $connection = null;
    protected $em;
    protected $params;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params)
    {
        $this->em = $em;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Moves the log to it\'s archive')
            ->addOption(
                'days',
                'd',
                InputOption::VALUE_OPTIONAL,
                sprintf('Number of days to preserve, default %s days', self::ARCHIVE_DAYS),
                self::ARCHIVE_DAYS
            )
            ->addOption(
                'purge',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Number of days to preserve on the archive, purge older, default none'
            )
        ;
    }

    /**
     * If pdo data is set we use it, if not doctrine is.
     */
    private function getConnection(): \PDO
    {
        if ($this->connection === null) {
            $usePdo = false;
            if ($this->params->has('acilia_db_logger')) {
                $config =  $this->params->get('acilia_db_logger');
                if (isset($config['pdo'])) {
                    if (!isset($config['pdo']['url']) || !isset($config['pdo']['user']) || !isset($config['pdo']['password'])) {
                        throw new \Exception('pdo configuration missing or not completed, (url, user and password must be set).');
                    } else {
                        $usePdo = true;
                    }
                }
            }

            if ($usePdo) {
                $config =  $this->params->get('acilia_db_logger');
                $options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
                $this->connection = new \PDO($config['pdo']['url'], $config['pdo']['user'], $config['pdo']['password'], $options);
            } else {
                $this->connection = $this->em->getConnection();
            }
        }

        return $this->connection;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $days = $input->getOption('days');
            $purge = $input->getOption('purge');

            if (!is_numeric($days)) {
                throw new \Exception('Days is not a valid number');
            }

            if ($days > $purge) {
                throw new \Exception(sprintf('Purge days (%s) must be greater than Archive days (%s)', $purge, $days));
            }

            // Calculate now
            $now = new \DateTime();
            $now->setTime(0, 0, 0);
            $now->modify('-' . $days . ' days');

            // Get connection
            $connection = $this->getConnection();

            // Archive logs
            $output->write('Archiving logs... ');
            $stmt = $connection->prepare('INSERT INTO log_archive SELECT * FROM log WHERE log_datetime < ?');
            $stmt->bindValue(1, $now->format('Y-m-d'));
            $stmt->execute();
            $output->writeln('OK');

            // Delete logs
            $output->write('Deleting logs... ');
            $stmt = $connection->prepare('DELETE FROM log WHERE log_datetime < ?');
            $stmt->bindValue(1, $now->format('Y-m-d'));
            $stmt->execute();
            $output->writeln('OK');

            if (is_numeric($purge)) {
                $now = new \DateTime();
                $now->setTime(0, 0, 0);
                $now->modify('-' . $purge . ' days');

                // Delete Archived logs
                $output->write('Purge Archived logs... ');
                $stmt = $connection->prepare('DELETE FROM log_archive WHERE log_datetime < ?');
                $stmt->bindValue(1, $now->format('Y-m-d'));
                $stmt->execute();
                $output->writeln('OK');
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            $output->writeln(sprintf('ERROR: %s', $e->getMessage()));

            return self::ERROR;
        }
    }
}
