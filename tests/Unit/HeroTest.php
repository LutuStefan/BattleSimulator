<?php declare(strict_types=1);

use App\Beast;
use App\Hero;
use App\Skill;
use App\SkillEffect;
use PHPUnit\Framework\TestCase;

final class HeroTest extends TestCase
{
    private $hero;
    private $beast;
    private $skill;
    private $rapidStrikeEffect;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = new Hero(
            rand(70, 100),
            rand(70, 80),
            rand(45, 55),
            rand(40, 50),
            rand(10, 30),
            []
        );

        $this->rapidStrikeEffect = new SkillEffect(
            [0, '*2', 0, 0, 0],
            [0, 0, '*2', 0, 0]
        );

        $this->skill = new Skill(
            'Rapid strike',
            'Strike twice while it’s his turn to attack.',
            $this->rapidStrikeEffect,
            false,
            Skill::RAPID_STRIKE_LUCK);

    }

    public function testAddSkill()
    {
        $this->assertEquals([], $this->hero->getSkills());

        $this->hero->addSkill($this->skill);
        $this->assertContains($this->skill, $this->hero->getSkills());
    }

    public function testAddSkills()
    {
        $this->assertEquals([], $this->hero->getSkills());

        $skills = [
            $this->skill,
            new Skill(
                'Test skill 2',
                'Some test ability 1',
                $this->rapidStrikeEffect,
                true,
                10
            )
        ];

        $this->hero->addSkills($skills);
        foreach ($skills as $skill) {
            $this->assertContains($skill, $this->hero->getSkills());
        }
    }

    public function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}