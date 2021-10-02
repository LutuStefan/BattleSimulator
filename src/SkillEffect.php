<?php

namespace App;

class SkillEffect
{
    /**
     * @var array
     */
    private $heroStatusEffect;
    private $opponentStatusEffect;

    /**
     * SkillEffect constructor.
     * @param array $heroStatusEffect
     * @param array $opponentStatusEffect
     */
    public function __construct(array $heroStatusEffect, array $opponentStatusEffect)
    {
        $this->heroStatusEffect = $heroStatusEffect;
        $this->opponentStatusEffect = $opponentStatusEffect;
    }

    /**
     * @return array
     */
    public function getHeroStatusEffect(): array
    {
        return $this->heroStatusEffect;
    }

    /**
     * @return array
     */
    public function getOpponentStatusEffect(): array
    {
        return $this->opponentStatusEffect;
    }


}
