<?php

namespace Tests\Unit\src\Application\BankAccount\Modules\Bank\Domain;

use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity as Entity;
use Core\Application\BankAccount\Modules\Bank\Events\{AddValueEvent, RemoveValueEvent};
use Tests\UnitCase as TestCase;

class BankEntityTest extends TestCase
{
    public function testCreate()
    {
        $objEntity = Entity::create($this->id(), "test", 50, true);
        $this->assertNotEmpty($objEntity->id());
        $this->assertNotEmpty($objEntity->createdAt());

        $this->assertNotEmpty($objEntity->accountEntity);
        $this->assertNotEmpty($objEntity->accountEntity->entity);
        $this->assertEquals(50, $objEntity->value);
        $this->assertEquals(50, $objEntity->accountEntity->value);
        $this->assertEquals($objEntity->id(), $objEntity->accountEntity->entity->id);
        $this->assertEquals(Entity::class, $objEntity->accountEntity->entity->type);
    }

    public function testEdit()
    {
        $objEntity = Entity::create(
            $this->id(),
            "test",
            50,
            true,
            null,
            null,
            null,
            null,
            null,
            null,
            $id = $this->id(),
            "2022-01-01 00:00:00"
        );
        $objEntity->update("update");
        $this->assertEquals("update", $objEntity->name->value);
        $this->assertEquals($id, $objEntity->id());
        $this->assertEquals("2022-01-01 00:00:00", $objEntity->createdAt());
        $this->assertNotEmpty($objEntity->accountEntity);
        $this->assertNotEmpty($objEntity->accountEntity->entity);
        $this->assertEquals(50, $objEntity->value);
        $this->assertEquals(50, $objEntity->accountEntity->value);
        $this->assertEquals($id, $objEntity->accountEntity->entity->id);
        $this->assertEquals(Entity::class, $objEntity->accountEntity->entity->type);
    }

    public function testCreateWithBankWithoutAgencyAndAccount()
    {
        $objEntity = Entity::create(
            $this->id(),
            "test",
            0,
            '1',
            null,
            null,
            null,
            null,
        );
        $this->assertEmpty($objEntity->bank);

        $objEntity = Entity::create(
            $this->id(),
            "test",
            0,
            '1',
            '2',
            null,
            null,
            null,
        );
        $this->assertEmpty($objEntity->bank);

        $objEntity = Entity::create(
            $this->id(),
            "test",
            0,
            '1',
            null,
            null,
            '2',
            null,
        );
        $this->assertEmpty($objEntity->bank);
    }

    public function testCreateWithBank()
    {
        $objEntity = Entity::create(
            $this->id(),
            "test",
            0,
            true,
            '1',
            '2',
            null,
            '4',
            null,
        );
        $this->assertNotEmpty($objEntity->bank);
        $this->assertEquals('1', $objEntity->bank->code);
        $this->assertEquals('2', $objEntity->bank->agency->account);
        $this->assertEquals(null, $objEntity->bank->agency->digit);
        $this->assertEquals('4', $objEntity->bank->account->account);
        $this->assertEquals(null, $objEntity->bank->account->digit);

        $objEntity = Entity::create(
            $this->id(),
            "test",
            0,
            true,
            '1',
            '2',
            '3',
            '4',
            '5',
        );
        $this->assertNotEmpty($objEntity->bank);
        $this->assertEquals('1', $objEntity->bank->code);
        $this->assertEquals('2', $objEntity->bank->agency->account);
        $this->assertEquals('3', $objEntity->bank->agency->digit);
        $this->assertEquals('4', $objEntity->bank->account->account);
        $this->assertEquals('5', $objEntity->bank->account->digit);
    }

    public function testExceptionCreateBankWithAccountAndAgency()
    {
        $this->expectErrorMessage('Bank details cannot be changed, please create a new bank account');
        $objEntity = Entity::create($this->id(), "test", 0, "1", "2", "3", "4", "5");
        $objEntity->update("update", "1", "2", "3", "4", "5");
    }

    public function testExceptionUpdateBankWithAccountAndAgency()
    {
        $this->expectErrorMessage('Bank details cannot be changed, please create a new bank account');
        $objEntity = Entity::create($this->id(), "test", 0, true);
        $objEntity->update("update", "1", "2", "3", "4", "5");
        $objEntity->update("update2", "1", "2", "3", "4", "5");
    }

    public function testUpdateInsertBank()
    {
        $objEntity = Entity::create($this->id(), "test", 0, true);
        $objEntity->update("update", "1", "2", "3", "4", "5");
        $this->assertNotEmpty($objEntity->bank);
        $this->assertEquals('1', $objEntity->bank->code);
        $this->assertEquals('2', $objEntity->bank->agency->account);
        $this->assertEquals('3', $objEntity->bank->agency->digit);
        $this->assertEquals('4', $objEntity->bank->account->account);
        $this->assertEquals('5', $objEntity->bank->account->digit);
    }

    public function testUpdateWithBankWithoutAgencyAndAccount()
    {
        $objEntity = Entity::create($this->id(), "test", 0, true);
        $objEntity->update("update", "1");
        $this->assertEmpty($objEntity->bank);

        $objEntity->update("update", "1", "2");
        $this->assertEmpty($objEntity->bank);

        $objEntity->update("update", "1", null, null, "2");
        $this->assertEmpty($objEntity->bank);
    }
}
