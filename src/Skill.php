<?php

namespace App;

class Skill {
    const RAPID_STRIKE_LUCK = 10;
    const MAGIC_SHIELD_LUCK = 20;
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $ability
     */
    private $ability;

    /**
     * @var int $defenceSkill
     */
    private $defenceSkill;

    /**
     * @var int $luck;
     */
    private $luck;

    /**
     * @var SkillEffect $skillEffect;
     */
    private $skillEffect;

    /**
     * Skill constructor.
     */
    public function __construct(string $name, string $ability, SkillEffect $skillEffect, $defenceSkill = null, $luck = null)
    {
        $this->name = $name;
        $this->ability = $ability;
        $this->skillEffect = $skillEffect;
        $this->defenceSkill = $defenceSkill;
        $this->luck = $luck;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAbility(): string
    {
        return $this->ability;
    }

    /**
     * @return SkillEffect
     */
    public function getSkillEffect(): SkillEffect
    {
        return $this->skillEffect;
    }

    /**
     * @return mixed|null
     */
    public function getDefenceSkillProp()
    {
        return $this->defenceSkill;
    }

    /**
     * @return mixed|null
     */
    public function getLuck()
    {
        return $this->luck;
    }
}