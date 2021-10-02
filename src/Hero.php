<?php

namespace App;

use App\Skill;

class Hero extends Character
{
    protected $name = 'Orderus';

    protected $status;

    protected $skills;

    /**
     * Hero constructor.
     */
    public function __construct($health, $strength, $defence, $speed, $luck, $skills)
    {
        $this->status = $this->initStatuses($health, $strength, $defence, $speed, $luck)->getStatus();
        $this->skills = $skills;
    }

    /**
     * @return Status
     */
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

    /**
     * @return array
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    /**
     * @param Skill $skill
     */
    public function addSkill(Skill $skill)
    {
        array_push($this->skills, $skill);
    }

    public function addSkills(array $skills)
    {
        foreach ($skills as $skill) {
            $this->addSkill($skill);
        }
    }

    /**
     * @param \App\Skill $skill
     * @param Character $opponent
     * @return array
     */
    public function useSkill(Skill $skill, Character $opponent): array
    {
        $heroStats = $this->status->calculateNewStatus($skill->getSkillEffect()->getHeroStatusEffect());
        $opponentStatus = $opponent->getStatus()->calculateNewStatus($skill->getSkillEffect()->getOpponentStatusEffect());

        return [$heroStats, $opponentStatus];
    }
}
