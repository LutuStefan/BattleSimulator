<?php

namespace App\Tests\Unit;

use App\Beast;
use App\GameManager;
use App\Hero;
use App\Skill;
use App\SkillEffect;
use App\Status;
use App\Turn;
use Mockery;
use PHPUnit\Framework\TestCase;

final class TurnTest extends TestCase
{
    private $hero;
    private $beast;
    private $skill;
    private $rapidStrikeEffect;
    private $turn;

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

        $this->beast = new Beast(
            rand(70, 80),
            rand(70, 70),
            rand(45, 45),
            rand(40, 40),
            rand(10, 20)
        );

        $this->rapidStrikeEffect = new SkillEffect(
            [0, '*2', 0, 0, 0],
            [0, 0, '*2', 0, 0]
        );

        $mockedSkill = Mockery::mock(Skill::class, ['Rapid strike',
            'Strike twice while it’s his turn to attack.',
            $this->rapidStrikeEffect,
            false,
            Skill::RAPID_STRIKE_LUCK])->makePartial();

        $this->skill = $mockedSkill;
        $this->hero->addSkill($this->skill);
        $this->turn = new Turn($this->hero, $this->beast);
    }

    public function testCalcOrderMethod()
    {
        if ($this->hero->getStatus()->getSpeed() > $this->beast->getStatus()->getSpeed()) {
            echo 1;
            $this->assertEquals(true, $this->turn->calcOrder());
        } elseif ($this->hero->getStatus()->getSpeed() < $this->beast->getStatus()->getSpeed()) {
            echo 2;
            $this->assertEquals(false, $this->turn->calcOrder());
        } elseif ($this->hero->getStatus()->getLuck() - $this->beast->getStatus()->getLuck() > 0) {
            echo 3;
            $this->assertEquals(true, $this->turn->calcOrder());
        } else {
            echo 4;
            $this->assertEquals(false, $this->turn->calcOrder());
        }
    }

    public function testCalculateIfSkillOccursWhenProbabilityIs100()
    {
        $this->skill
            ->shouldReceive('getLuck')
            ->andReturns(100);
        $this->skill
            ->shouldReceive('getDefenceSkillProp')
            ->andReturns(false);

        $this->assertEquals($this->skill, $this->turn->calculateIfSkillOccurs($this->hero));
    }

    public function testCalculateIfSkillOccursForABeast()
    {
        $this->assertEquals(null, $this->turn->calculateIfSkillOccurs($this->beast));
    }

    public function testCalculateDamagePerMoveWhenNoSkillOccurs()
    {
        $mockedTurn = Mockery::mock(Turn::class, [
            $this->hero,
            $this->beast
        ])->makePartial();

        $mockedTurn
            ->shouldReceive('calculateIfSkillOccurs')
            ->andReturns(null);
        $this->assertEquals(
            $this->hero->getStatus()->getStrength() - $this->beast->getStatus()->getDefence(),
            $mockedTurn->calculateDamagePerMove($this->hero, $this->beast)
        );
    }

    public function testCalculateDamagePerMoveWhenSkillOccurs()
    {
        $mockedTurn = Mockery::mock(Turn::class, [
            $this->hero,
            $this->beast
        ])->makePartial();
        $mockedTurn
            ->shouldReceive('calculateIfSkillOccurs')
            ->with($this->hero)
            ->andReturns($this->skill);

        $this->assertEquals(
            $this->hero->getStatus()->getStrength() * 2 - $this->beast->getStatus()->getDefence() * 2,
            $mockedTurn->calculateDamagePerMove($this->hero, $this->beast)
        );
    }

    public function testExecuteMoveWhenAttackerMiss()
    {
        $mockedTurn = Mockery::mock(Turn::class, [
            $this->hero,
            $this->beast
        ])->makePartial();

        $this->beast->getStatus()->setLuck(100);

        $mockedTurn->executeMove($this->hero, $this->beast);
        $this->expectOutputString(
            PHP_EOL . $this->hero->getName() . ' Turn:' .
            PHP_EOL . $this->hero->getName() . ' missed his attack!'
        );
    }

    public function testExecuteMoveAndBattleContinue()
    {
        $mockedTurn = Mockery::mock(Turn::class, [
            $this->hero,
            $this->beast
        ])->makePartial();

        /**
         * set 0% beast luck. This way the hero cant miss his attack this test.
         */
        $this->beast->getStatus()->setLuck(0);

        $heroDmg = rand(1, $this->beast->getStatus()->getHealth() - 1);

        $mockedTurn
            ->shouldReceive('calculateDamagePerMove')
            ->andReturns($heroDmg);
        $this->assertEquals(false, $mockedTurn->executeMove($this->hero, $this->beast));
        $this->expectOutputString(
            PHP_EOL . $this->hero->getName() . ' Turn:' .
            PHP_EOL . $this->hero->getName() . ' damage: ' . $heroDmg .
            PHP_EOL . $this->beast->getName() . ' health: ' . $this->beast->getStatus()->getHealth()
        );

    }

    public function testExecuteMoveAndBattleIsFinished()
    {
        $mockedTurn = Mockery::mock(Turn::class, [
            $this->hero,
            $this->beast
        ])->makePartial();

        /**
         * set 0% beast luck. This way the hero cant miss his attack this test.
         */
        $this->beast->getStatus()->setLuck(0);

        $heroDmg = $this->beast->getStatus()->getHealth() + 1;

        $mockedTurn
            ->shouldReceive('calculateDamagePerMove')
            ->andReturns($heroDmg);
        $this->assertEquals(true, $mockedTurn->executeMove($this->hero, $this->beast));
        $this->expectOutputString(
            PHP_EOL . $this->hero->getName() . ' Turn:' .
            PHP_EOL . $this->hero->getName() . ' damage: ' . $heroDmg .
            PHP_EOL . $this->beast->getName() . ' health: ' . ($this->beast->getStatus()->getHealth() - $heroDmg) .
            PHP_EOL . $this->beast->getName() . ' lost!'
        );
    }

    public function testExecuteTurnsStopTheBattleOnTime()
    {
        $mockedTurn = Mockery::mock(Turn::class, [
            $this->hero,
            $this->beast
        ])->makePartial();

        $mockedTurn->shouldReceive('executeTurn')
            ->andReturns(true);
        $mockedTurn->executeTurns();

        $this->expectOutputString(PHP_EOL . 'Battle is over!');
    }

    public function testExecuteTurnsExceedsMaximumNumbersOfTurns()
    {
        $mockedTurn = Mockery::mock(Turn::class, [
            $this->hero,
            $this->beast
        ])->makePartial();

        $mockedTurn->shouldReceive('executeTurn')
            ->andReturns(false);

        $mockedTurn->executeTurns();

        $this->assertStringContainsString('Battle is over. There were more then '. GameManager::MAX_NUMBER_OF_TURNS . ' of turns!', $this->getActualOutput());
    }

    public function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}