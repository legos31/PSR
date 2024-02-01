<?php


namespace Framework\container;

use Framework\container\exceptions\ContainerException;


class Container implements \Psr\Container\ContainerInterface
{
    private array $services = [];

    /**
     * @inheritDoc
     */
    public function get(string $id)
    {
        if(!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException($id . ' could not be resolve');
            }
            $this->add($id);
        }

        return $this->resolve($this->services[$id]);
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    public function add(string $id, string|object $service = null)
    {
        if (is_null($service)) {
            if (!class_exists($id)) {
                throw new ContainerException($id . ' is not a service');
            }
            $service = $id;
        }
        $this->services[$id] = $service;

    }

    private function resolve($class)
    {
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            return $reflection->newInstance();
        }
        $constructorParams = $constructor->getParameters();
        $classDependencies = $this->resolveClassDependencies($constructorParams);
        $instance = $reflection->newInstanceArgs($classDependencies);
        return $instance;
    }

    private function resolveClassDependencies($constructorParams)
    {
        $classDependencies = [];

        /** @var \ReflectionParameter $param */
        foreach ($constructorParams as $param) {
            $serviceType = $param->getType();
            $service = $this->get($serviceType->getName());
            $classDependencies[] = $service;
        }

        return $classDependencies;
    }
}