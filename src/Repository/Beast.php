<?php

namespace App;

class Beast extends Character
{
    protected $name = 'Beast';

    protected $status;

    public function __construct($health, $strength, $defence, $speed, $luck)
    {
        $this->status  = $this->initStatuses($health, $strength, $defence, $speed, $luck)->getStatus();
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
