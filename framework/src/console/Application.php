<?php


namespace Framework\console;


use Psr\Container\ContainerInterface;

class Application
{

    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function run(): int
    {
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;
        if (!$commandName) {
            throw new ConsoleException('Invalid console command');
        }

        /** @var CommandInterface $command */

        $command = $this->container->get($commandName);

        $args = array_slice($argv,2);
        $options = $this->parceOptions($args);
        $status = $command->execute($options);
        return $status;
    }

    private function parceOptions(array $args): array
    {
        $options = [];
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                $option = explode('=',substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }
        return $options;
    }
}