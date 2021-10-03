<?php

namespace App\Service;

use App\Beast;
use App\Hero;
use App\Skill;
use App\SkillEffect;

class TurnService
{
    public static function initBattle(): array
    {
        $hero = new Hero(
            rand(70, 100),
            rand(70, 80),
            rand(45, 55),
            rand(40, 50),
            rand(10, 30),
            []
        );

        $beast = new Beast(
            rand(60, 90),
            rand(60, 90),
            rand(40, 60),
            rand(40, 60),
            rand(25, 40)
        );

        $rapidStrikeEffect = new SkillEffect(
            [0, '*2', 0, 0, 0],
            [0, 0, '*2', 0, 0]
        );

        $magicShieldEffect = new SkillEffect(
            [0, 0, '/2', 0, 0],
            [0, '/2', 0, 0, 0]
        );

        $skills = [
            new Skill(
                'Rapid strike',
                'Strike twice while it’s his turn to attack.',
                $rapidStrikeEffect,
                false,
                Skill::RAPID_STRIKE_LUCK),
            new Skill(
                'Magic shield',
                'Takes only half of the usual damage when an enemy attacks.',
                $magicShieldEffect,
                true,
                Skill::MAGIC_SHIELD_LUCK)
        ];

        $hero->addSkills($skills);

        return [$hero, $beast];
    }
}