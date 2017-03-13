<?php
namespace hisorange\Registry;

// Interfaces.
use hisorange\Registry\Interfaces\Entity         as EntityInterface;
use hisorange\Registry\Interfaces\Manager        as ManagerInterface;
// Execptions.
use hisorange\Registry\Exceptions\EntityRevision as EntityRevisionException;

class Entity implements EntityInterface
{
    /**
     * Store the entity's value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Store the value's changes.
     *
     * @var array
     */
    protected $revisions = [];

    /**
     * Where the revision pointer is currenty.
     *
     * @var integer
     */
    protected $pointer   = 0;

    /**
     * {@inheritdocs}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdocs}
     */
    public function setValue($value)
    {
        $this->revisions[($this->pointer = sizeof($this->revisions))] = $this->value = $value;
    }

    /**
     * Rollback the value chaneges with the given step count.
     * @throws \hisorange\Registry\Exceptions\EntityRevisionException
     *
     * @param  int  $steps Default to 1 step back.
     * @return void
     */
    public function rollback($steps = 1)
    {
        // Cannot step less than 1, or negative step.
        if ($steps < 1) {
            throw new EntityRevisionException('Rollback cannot step less than 1.');
        }

        // Stepping below initialization.
        if ($this->pointer - $steps < 0) {
            throw new EntityRevisionException('Cannot rollback before initialization.');
        }

        $this->pointer -= $steps;

        $this->value = $this->revisions[$this->pointer];
    }

    /**
     * Check where the internal pointer is.
     *
     * @return int
     */
    public function getPointer()
    {
        return $this->pointer;
    }

    /**
     * This allows us to determine the max step count for rollbacks.
     *
     * @return int
     */
    public function getRevisionCount()
    {
        return sizeof($this->revisions);
    }
}