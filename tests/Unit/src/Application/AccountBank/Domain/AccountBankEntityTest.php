<?php

namespace Tests\Unit\src\Application\AccountBank\Domain;

use Core\Application\AccountBank\Domain\AccountBankEntity as Entity;
use Core\Application\AccountBank\Events\{AddValueEvent, RemoveValueEvent};
use Core\Application\AccountBank\ValueObjects\BankObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class AccountBankEntityTest extends TestCase
{
    public function testCreate()
    {
        $objAccountBank = Entity::create(name: 'teste', value: 0, tenant: Uuid::uuid4(),bankCode: 341);

        $this->assertNotEmpty($objAccountBank->id());
        $this->assertNotEmpty($objAccountBank->createdAt());
        $this->assertNull($objAccountBank->bank);
    }

    public function testCreateWithBank()
    {
        $objAccountBank = Entity::create(
            name: 'teste', 
            value: 0,
            tenant: Uuid::uuid4(), 
            bankCode: 341,
            agency: '66556',
            agencyDigit: '4',
            account: '36633',
            accountDigit: 5,
        );

        $this->assertNotEmpty($objAccountBank->id());
        $this->assertNotEmpty($objAccountBank->createdAt());
        $this->assertEquals(341, $objAccountBank->bank->code);
        $this->assertEquals('66556', $objAccountBank->bank->agency->account);
        $this->assertEquals('4', $objAccountBank->bank->agency->digit);
        $this->assertEquals('36633', $objAccountBank->bank->account->account);
        $this->assertEquals('5', $objAccountBank->bank->account->digit);
    }

    public function testUpdate()
    {
        $objAccountBank = Entity::create(
            name: 'teste',
            value: 0,
            tenant: Uuid::uuid4(),
            id: $id = Uuid::uuid4()
        );

        $objAccountBank->update(
            name: 'teste 2',
            value: 0,
            bankCode: 341,
            agency: '66556',
            agencyDigit: '4',
            account: '36633',
            accountDigit: 5,
        );

        $this->assertEquals($id, $objAccountBank->id());
        $this->assertEquals('teste 2', $objAccountBank->name->value);
        $this->assertEquals(341, $objAccountBank->bank->code);
        $this->assertEquals('66556', $objAccountBank->bank->agency->account);
        $this->assertEquals('4', $objAccountBank->bank->agency->digit);
        $this->assertEquals('36633', $objAccountBank->bank->account->account);
        $this->assertEquals('5', $objAccountBank->bank->account->digit);
    }

    public function testCreateWithBankWithoutDigit()
    {
        $objAccountBank = Entity::create(
            name: 'teste',
            value: 0,
            tenant: Uuid::uuid4(),
            bankCode: 341,
            agency: '12366',
            account: '66655',
            id: Uuid::uuid4(),
        );

        $this->assertInstanceOf(BankObject::class, $objAccountBank->bank);
        $this->assertEquals('341', $objAccountBank->bank->code);
        $this->assertEquals('12366', $objAccountBank->bank->agency->account);
        $this->assertNull($objAccountBank->bank->agency->digit);
        $this->assertEquals('66655', $objAccountBank->bank->account->account);
        $this->assertNull($objAccountBank->bank->account->digit);
    }

    public function testCreateWithBankWithDigit()
    {
        $objAccountBank = Entity::create(
            name: 'teste',
            value: 0,
            tenant: Uuid::uuid4(),
            bankCode: 341,
            agency: '12366',
            agencyDigit: '1',
            account: '66655',
            accountDigit: '2',
            id: Uuid::uuid4(),
        );

        $this->assertInstanceOf(BankObject::class, $objAccountBank->bank);
        $this->assertEquals('341', $objAccountBank->bank->code);
        $this->assertEquals('1', $objAccountBank->bank->agency->digit);
        $this->assertEquals('12366', $objAccountBank->bank->agency->account);
        $this->assertEquals('66655', $objAccountBank->bank->account->account);
        $this->assertEquals('2', $objAccountBank->bank->account->digit);
    }

    public function testUpdateAccountWithBank(){
        $this->expectExceptionMessage('Bank details cannot be changed, please create a new bank account');
        $objAccountBank = Entity::create(
            name: 'teste',
            value: 0,
            tenant: Uuid::uuid4(),
            bankCode: 341,
            agency: '12366',
            agencyDigit: '1',
            account: '66655',
            accountDigit: '2',
            id: Uuid::uuid4(),
        );

        $objAccountBank->update('teste2', 400, '6556', '6', '6998', '4');
    }

    public function testAddValue()
    {
        $objAccountBank = Entity::create(Uuid::uuid4(), 'teste', 0);
        $objAccountBank->addValue(100, $idPayment = Uuid::uuid4());
        $this->assertEquals(100, $objAccountBank->value);
        $this->assertCount(1, $objAccountBank->events);
        $this->assertInstanceOf(AddValueEvent::class, $objAccountBank->events[0]);
        $this->assertEquals('bank.value.add.' . $objAccountBank->id(), $objAccountBank->events[0]->name());
        $this->assertEquals([
            'id' => $objAccountBank->id(),
            'value' => $objAccountBank->value,
            'payment' => (string) $idPayment,
        ], $objAccountBank->events[0]->payload());
    }

    public function testRemoveValue()
    {
        $objAccountBank = Entity::create(Uuid::uuid4(), 'teste', 0);
        $objAccountBank->removeValue(100, $idPayment = Uuid::uuid4());
        $this->assertEquals(-100, $objAccountBank->value);
        $this->assertCount(1, $objAccountBank->events);
        $this->assertInstanceOf(RemoveValueEvent::class, $objAccountBank->events[0]);
        $this->assertEquals('bank.value.remove.' . $objAccountBank->id(), $objAccountBank->events[0]->name());
        $this->assertEquals([
            'id' => $objAccountBank->id(),
            'value' => 100,
            'payment' => (string) $idPayment,
        ], $objAccountBank->events[0]->payload());
    }
}
