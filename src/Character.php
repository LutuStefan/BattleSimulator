<?php

namespace App;

class Character {

    protected $name;

    /**
     * @var Status $status
     */
    protected $status;

    protected function initStatuses($health, $strength, $defence, $speed, $luck): Character
    {
        $this->status = new Status();
        $this->status->setHealth($health);
        $this->status->setStrength($strength);
        $this->status->setDefence($defence);
        $this->status->setSpeed($speed);
        $this->status->setLuck($luck);

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status  $status): Character
    {
        $this->status = $status;
        return $this;
    }

    public function displayStatus()
    {
        echo PHP_EOL . $this->name . ': ' . PHP_EOL;
        echo 'Health: ' . $this->status->getHealth() . PHP_EOL;
        echo 'Strength: ' . $this->status->getStrength() . PHP_EOL;
        echo 'Defence: ' . $this->status->getDefence() . PHP_EOL;
        echo 'Speed: ' . $this->status->getSpeed() . PHP_EOL;
        echo 'Luck: ' . $this->status->getLuck() . PHP_EOL;
    }
}