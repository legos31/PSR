<?php


namespace Framework\console;



use Psr\Container\ContainerInterface;

class Kernel
{
    private $namespacePath = 'Framework\console\commands';

    public function __construct(
        private ContainerInterface $container,
        private Application $application
    )
    {
    }

    public function handle()
    {

        $this->registerCommand();

        $status = $this->application->run();

    }

    private function registerCommand()
    {
        $commandFiles = new \DirectoryIterator(__DIR__ . '/commands');

        foreach ($commandFiles as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $command = $this->namespacePath . '\\'. pathinfo($file, PATHINFO_FILENAME);

            if (is_subclass_of($command, CommandInterface::class)) {
                $name = (new \ReflectionClass($command))->getProperty('name')->getDefaultValue();
                $this->container->add($name, $command);

            } else {
                dd('bad');
            }

        }
        return $commandFiles;
    }
}