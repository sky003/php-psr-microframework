<?php
declare(strict_types = 1);

namespace App\Component\Console\Command;

use Lcobucci\JWT\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The command to generate valid JWT which can be used to authenticate to API.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class GenerateJwtCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:generate-jwt')
            ->setAliases(['app:jwt:generate'])
            ->setDescription('Generate a valid JWT which can be used to authenticate to API.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = (new Builder())
            ->setId(\uniqid('jwt_', true))
            ->sign($this->getHelper('jwt')->getSigner(), $this->getHelper('jwt')->getKey())
            ->getToken();

        $output->writeln((string) $token);
    }
}
