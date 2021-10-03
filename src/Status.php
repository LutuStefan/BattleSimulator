<?php

namespace App;

class Status {

    protected $health;

    protected $strength;

    protected $defence;

    protected $speed;

    protected $luck;

    /**
     * Status constructor.
     * @param float $health
     * @param float $strength
     * @param float $defence
     * @param int $speed
     * @param int $luck
     */
    public function __construct(
        float $health = 0,
        float $strength = 0,
        float $defence = 0,
        int $speed = 0,
        int $luck = 0
    )
    {
        $this->health = $health;
        $this->strength = $strength;
        $this->defence = $defence;
        $this->speed = $speed;
        $this->luck = $luck;
    }

    /**
     * @return Status
     */
    public function copyStatus(): Status
    {
        return new Status(
            $this->health,
            $this->strength,
            $this->defence,
            $this->speed,
            $this->luck
        );
    }

    /**
     * @param Status $status
     * @return $this
     */
    public function setStatus(Status $status): Status
    {
        $this->health = $status->getHealth();
        $this->strength = $status->getStrength();
        $this->defence = $status->getDefence();
        $this->speed = $status->getSpeed();
        $this->luck = $status->getLuck();

        return $this;
    }

    /**
     * @return float
     */
    public function getHealth(): float
    {
        return $this->health;
    }


    /**
     * @return float
     */
    public function getStrength(): float
    {
        return $this->strength;
    }

    /**
     * @return float
     */
    public function getDefence(): float
    {
        return $this->defence;
    }

    /**
     * @return int
     */
    public function getSpeed(): int
    {
        return $this->speed;
    }

    /**
     * @return int
     */
    public function getLuck(): int
    {
        return $this->luck;
    }


    /**
     * @param float $health
     */
    public function setHealth(float $health)
    {
        $this->health = $health;
    }

    /**
     * @param float $strength
     */
    public function setStrength(float $strength)
    {
        $this->strength = $strength;
    }

    /**
     * @param float $defence
     */
    public function setDefence(float $defence)
    {
        $this->defence = $defence;
    }

    /**
     * @param int $speed
     */
    public function setSpeed(int $speed)
    {
        $this->speed = $speed;
    }

    /**
     * @param int $luck
     */
    public function setLuck(int $luck)
    {
        $this->luck = $luck;
    }

    /**
     * @param array $skillEffect
     * @return $this
     */
    public function calculateNewStatus(array $skillEffect): Status
    {
        $this->health = StatusHelper::calculateNewStatus($this->health, $skillEffect[0]);
        $this->strength = StatusHelper::calculateNewStatus($this->strength, $skillEffect[1]);
        $this->defence = StatusHelper::calculateNewStatus($this->defence, $skillEffect[2]);
        $this->speed = StatusHelper::calculateNewStatus($this->speed, $skillEffect[3]);
        $this->luck = StatusHelper::calculateNewStatus($this->luck, $skillEffect[4]);

        return $this;
    }
}