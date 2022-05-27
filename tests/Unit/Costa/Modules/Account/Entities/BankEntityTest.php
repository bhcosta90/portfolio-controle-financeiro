<?php

namespace Tests\Unit\Costa\Modules\Account\Entities;

use PHPUnit\Framework\TestCase;
use Costa\Modules\Account\Entities\BankEntity as Entity;
use Costa\Modules\Account\ValueObjects\BankObject;
use Costa\Shareds\Enums\DocumentEnum;
use Costa\Shareds\ValueObjects\DocumentObject;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;
use stdClass;

class BankEntityTest extends TestCase
{
    public function testBasicEntity()
    {
        /** @var InputNameObject */
        $mockInputNameObject = Mockery::mock(stdClass::class, InputNameObject::class, 'teste');
        
        $obj = new Entity(
            name: $mockInputNameObject
        );

        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testEntityWithBank()
    {
        /** @var BankObject */
        $mockBankObject = Mockery::mock(stdClass::class, BankObject::class, [
            1,
            2,
            3,
            null,
            4,
            5
        ]);

        /** @var InputNameObject */
        $mockInputNameObject = Mockery::mock(stdClass::class, InputNameObject::class, 'teste');

        $obj = new Entity(
            name: $mockInputNameObject,
            bank: $mockBankObject,
            id: $id = UuidObject::random(),
            createdAt: $date = new DateTime()
        );

        $this->assertEquals($id, $obj->id());
        $this->assertEquals($date->format('Y-m-d H:i:s'), $obj->createdAt());
        $this->assertEquals($obj->bank->code, '1');
        $this->assertEquals($obj->bank->agency, '2');
        $this->assertEquals($obj->bank->account, '3');
        $this->assertNull($obj->bank->document);
        $this->assertEquals($obj->bank->agencyDigit, '4');
        $this->assertEquals($obj->bank->accountDigit, '5');
    }

    public function testEntityWithBankAndDocument()
    {
        /** @var DocumentObject */
        $mockDocument = Mockery::mock(stdClass::class, DocumentObject::class, [
            DocumentEnum::PASSPORT,
            '1326516511561'
        ]);

        /** @var BankObject */
        $mockBankObject = Mockery::mock(stdClass::class, BankObject::class, [
            1,
            2,
            3,
            $mockDocument,
            4,
            5
        ]);

        /** @var InputNameObject */
        $mockInputNameObject = Mockery::mock(stdClass::class, InputNameObject::class, 'teste');

        $obj = new Entity(
            name: $mockInputNameObject,
            bank: $mockBankObject,
            id: UuidObject::random(),
            createdAt: new DateTime()
        );

        $this->assertInstanceOf(DocumentObject::class, $obj->bank->document);
        $this->assertEquals(DocumentEnum::PASSPORT, $obj->bank->document->type);
        $this->assertEquals('1326516511561', $obj->bank->document->document);
        $this->assertEquals($obj->bank->agencyDigit, '4');
        $this->assertEquals($obj->bank->accountDigit, '5');
    }

    public function testUpdate()
    {
        /** @var InputNameObject */
        $mockInputNameObject = Mockery::mock(stdClass::class, InputNameObject::class, ['teste']);

        $obj = new Entity(
            name: $mockInputNameObject
        );

        $obj->update(
            name: $mockInputNameObject
        );

        $this->assertEquals('teste', $obj->name->value);
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testUpdateWithBank()
    {
        /** @var BankObject */
        $mockBankObject = Mockery::mock(stdClass::class, BankObject::class, [
            1,
            2,
            3,
            null,
            4,
            5
        ]);

        /** @var InputNameObject */
        $mockInputNameObject = Mockery::mock(stdClass::class, InputNameObject::class, 'teste');

        $obj = new Entity(
            name: $mockInputNameObject
        );

        $obj->update(
            name: $mockInputNameObject,
            bank: $mockBankObject,
        );

        $this->assertEquals($obj->bank->code, '1');
        $this->assertEquals($obj->bank->agency, '2');
        $this->assertEquals($obj->bank->account, '3');
        $this->assertNull($obj->bank->document);
        $this->assertEquals($obj->bank->agencyDigit, '4');
        $this->assertEquals($obj->bank->accountDigit, '5');
    }

    public function testUpdateWithBankAndDocument()
    {
        /** @var DocumentObject */
        $mockDocument = Mockery::mock(stdClass::class, DocumentObject::class, [
            DocumentEnum::PASSPORT,
            '1326516511561'
        ]);

        /** @var BankObject */
        $mockBankObject = Mockery::mock(stdClass::class, BankObject::class, [
            1,
            2,
            3,
            $mockDocument,
            4,
            5
        ]);

        /** @var InputNameObject */
        $mockInputNameObject = Mockery::mock(stdClass::class, InputNameObject::class, 'teste');

        $obj = new Entity(
            name: $mockInputNameObject
        );

        $obj->update(
            name: $mockInputNameObject,
            bank: $mockBankObject,
        );

        $this->assertInstanceOf(DocumentObject::class, $obj->bank->document);
        $this->assertEquals(DocumentEnum::PASSPORT, $obj->bank->document->type);
        $this->assertEquals('1326516511561', $obj->bank->document->document);
        $this->assertEquals($obj->bank->agencyDigit, '4');
        $this->assertEquals($obj->bank->accountDigit, '5');
    }
}
