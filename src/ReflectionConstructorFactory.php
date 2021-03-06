<?php

namespace ScayTrase\Api\Cruds;

final class ReflectionConstructorFactory implements EntityFactoryInterface
{
    /** @var  string */
    private $className;
    /** @var  array */
    private $args = [];

    /**
     * ReflectionConstructorFactory constructor.
     *
     * @param string $className
     * @param array  $args
     */
    public function __construct($className, array $args = [])
    {
        $this->className = $className;
        $this->args      = $args;
    }

    /** {@inheritdoc} */
    public function createEntity($data)
    {
        $reflection = new \ReflectionClass($this->className);

        return $reflection->newInstanceArgs($this->args);
    }
}
