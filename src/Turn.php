<?php

namespace App;

use App\Repository\CharacterRepository;

class Turn
{
    /**
     * @var Character
     */
    private $hero;
    /**
     * @var Character
     */
    private $opponent;


    public function __construct(Character $hero, Character $opponent)
    {
        $this->hero = $hero;
        $this->opponent = $opponent;
    }

    /**
     * @param $determineWhoStartFirst
     * @return bool
     */
    public function executeTurn($determineWhoStartFirst): bool
    {
        if ($determineWhoStartFirst) {
            $isOver = $this->executeMove($this->hero, $this->opponent);
        } else {
            $isOver = $this->executeMove($this->opponent, $this->hero);
        }

        if ($isOver) {
            return true;
        }

        return false;

    }

    public function executeTurns()
    {

        $determineWhoStartFirst = $this->calcOrder();
        $numberOfTurns = 0;
        while (
            $numberOfTurns < GameManager::MAX_NUMBER_OF_TURNS &&
            $this->hero->getStatus()->getHealth() &&
            $this->opponent->getStatus()->getHealth() > 0
        ) {
            $isOver = $this->executeTurn($determineWhoStartFirst);

            echo PHP_EOL;
            if ($isOver) {
                echo 'Battle is over!';
                return;
            }
            $numberOfTurns++;
            $determineWhoStartFirst = !$determineWhoStartFirst;
        }

        echo 'Battle is over. There were more then '. GameManager::MAX_NUMBER_OF_TURNS . ' of turns!';
    }

    /**
     * @param Character $attacker
     * @param Character $defender
     * @return bool
     */
    public function executeMove(Character $attacker, Character &$defender): bool
    {
        echo PHP_EOL . $attacker->getName() . ' Turn:';

        if (rand(1, 100) <= $defender->getStatus()->getLuck()) {
            echo PHP_EOL . $attacker->getName() . ' missed his attack!';
            return false;
        }

        $damage = $this->calculateDamagePerMove($attacker, $defender);
        if ($damage > 0) {
            $defenderHealth = $defender->getStatus()->getHealth() - $damage;
            echo PHP_EOL . $attacker->getName() . ' damage: ' . $damage;
            echo PHP_EOL . $defender->getName() . ' health: ' . $defenderHealth;
            if ($defenderHealth <= 0) {
                echo PHP_EOL . $defender->getName() . ' lost!';
                return true;
            }
//            $defender->setStatus($defender->getStatus()->setHealth($defenderHealth));
            $defender->getStatus()->setHealth($defenderHealth);

        }
        return false;

    }

    /**
     * @param Character $attacker
     * @param Character $defender
     * @return float
     */
    public function calculateDamagePerMove(Character $attacker, Character $defender): float
    {
        /** decide which skill occurs */
        $attackerSkill = $this->calculateIfSkillOccurs($attacker);
        $defenderSkill = $this->calculateIfSkillOccurs($defender, true);

        if ($attackerSkill !== null) {
            echo PHP_EOL . $attacker->getName() . ' used ' . $attackerSkill->getName();
        }

        if ($defenderSkill !== null) {
            echo PHP_EOL . $defender->getName() . ' used ' . $defenderSkill->getName();
        }

        $attackerWithNewStatuses = $attacker->getStatus();
        $defenderWithNewStatuses = $defender->getStatus();
        $attackerOldStatus = $attackerWithNewStatuses->copyStatus();
        $defenderOldStatus = $defenderWithNewStatuses->copyStatus();

        if ($attackerSkill !== null) {
            /** @var Hero $attacker */
            [$attackerWithNewStatuses, $defenderWithNewStatuses] = $attacker->useSkill($attackerSkill, $defender);
            $attacker->setStatus($attackerWithNewStatuses);
            $defender->setStatus($defenderWithNewStatuses);
        }

        if ($defenderSkill !== null) {
            /** @var Hero $defender */
            /** @var Status $defenderWithNewStatuses */
            /** @var Status $attackerWithNewStatuses */
            [$defenderWithNewStatuses, $attackerWithNewStatuses] = $defender->useSkill($defenderSkill, $attacker);
        }

        $damage = $attackerWithNewStatuses->getStrength() - $defenderWithNewStatuses->getDefence();

        $attacker->setStatus($attackerOldStatus);
        $defender->setStatus($defenderOldStatus);

        return $damage > 0 ? $damage : 0;
    }

    /**
     * @param Character $character
     * @param false $defence
     * @return Skill|null
     */
    public function calculateIfSkillOccurs(Character $character, bool $defence = false): ?Skill
    {
        if (!($character instanceof Hero)) {
            return null;
        }

        /** @var Skill $skill */
        foreach ($character->getSkills() as $skill) {
            if ($skill->getDefenceSkillProp() === $defence && rand(1, 100) <= $skill->getLuck()) {
                return $skill;
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function calcOrder(): bool
    {
        $relativeSpeed = $this->hero->getStatus()->getSpeed() - $this->opponent->getStatus()->getSpeed();

        if ($relativeSpeed > 0) {
            return true;
        } elseif ($relativeSpeed < 0) {
            return false;
        } else {
            $relativeLuck = $this->hero->getStatus()->getLuck() - $this->opponent->getStatus()->getLuck();
            return $relativeLuck > 0 ? true : false;
        }
    }
}