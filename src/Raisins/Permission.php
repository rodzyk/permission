<?php

namespace Raisins;

class Permission implements \JsonSerializable
{
    /**
     * Permission name
     *
     * @var string
     */
    private $name;

    /**
     * Permission state
     * 0: default, 1: allowed, -1: forbidden
     *
     * @var int
     */
    private $state;


    public function __construct(string $name, int $state = 0)
    {
        $this->setName($name);
        $this->setState($state);
    }

    /**
     * Merge permissions
     *
     * @param Permission $permission
     * @return Permission
     */
    public function merge(Permission $permission): Permission
    {
        $name = $permission->getName();

        if ($name != $this->name) throw new EqualsNameException("Name should be equals", 1);

        $newPermission = new Permission($name, $this->getState());

        $state = $permission->getState();

        if ($state != 0)
            $newPermission->setState($state);

        return $newPermission;
    }

    /**
     * Return name of permission
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set permission name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Return permission state
     *
     * @return integer
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * Set permission state
     *
     * @param integer $state
     * @return void
     */
    public function setState(int $state)
    {
        $this->state = $state;
    }


    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
