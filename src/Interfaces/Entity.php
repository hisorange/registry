<?php
namespace hisorange\Registry\Interfaces;

interface Entity
{
    /**
     * Accessor for the value parameter.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Accessor to set the value paramter.
     *
     * @param  mixed $value
     * @return void
     */
    public function setValue($value);
}