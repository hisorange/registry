<?php
use PHPUnit\Framework\TestCase;

// Resources.
use hisorange\Registry\Pool;
use hisorange\Registry\Manager;
use hisorange\Registry\Entity;
// Exceptions.
use hisorange\Registry\Exceptions\NamespaceMissing;
use hisorange\Registry\Exceptions\EntityMissing;

class ManagerTest extends TestCase
{
    /**
     * Self globalisaiton.
     */
    public function testGlobalisation()
    {
        $global = Pool::get();

        $custom = new Manager;
        $custom->registerAsGlobal();

        $this->assertNotSame($global, Pool::get());
        $this->assertSame($custom, Pool::get());
    }

    /**
     * Test the has checker.
     */
    public function testNotHas()
    {
        $manager = new Manager;

        $this->assertFalse($manager->has('nooope'));
        $manager->set('nooope', 123);
        $this->assertTrue($manager->has('nooope'));
    }

    /**
     * Test type setting.
     */
    public function testSetTypes()
    {
        $manager = new Manager;
        $manager->set('true', true);
        $this->assertTrue($manager->get('true'));

        $manager->set('false', false);
        $this->assertFalse($manager->get('false'));

        $manager->set('string', 'theory');
        $this->assertSame($manager->get('string'), 'theory');
        $this->assertInternalType('string', $manager->get('string'));

        $manager->set('int', 42);
        $this->assertSame($manager->get('int'), 42);
        $this->assertInternalType('integer', $manager->get('int'));

        $manager->set('null', null);
        $this->assertNull($manager->get('null'));
        $this->assertNull($manager->get('nope'));

        $this->assertSame($manager->get('fallback', 'fallback'), 'fallback');
        $this->assertInternalType('string', $manager->get('fallback', 'fallback'));

        $manager->set('array', [1,2,3]);
        $this->assertSame($manager->get('array'), [1,2,3]);
        $this->assertInternalType('array', $manager->get('array'));

        $manager->set('object', new stdClass);
        $this->assertInstanceOf(stdClass::class, $manager->get('object'));
        $this->assertInternalType('object', $manager->get('object'));
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Error
     */
    public function testSetBadKey()
    {
        (new Manager)->set(new stdClass, 123);
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Error
     */
    public function testGetBadKey()
    {
        (new Manager)->get(new stdClass);
    }

    /**
     * @expectedException \hisorange\Registry\Exceptions\EntityMissing
     */
    public function testSetDeleteMissingKey()
    {
        (new Manager)->delete('missing_key');
    }

    /**
     * Access the entity in entity form.
     */
    public function testGetAsEntity()
    {
        $manager = new Manager;
        $manager->set('asentity', 123);

        $this->assertSame($manager->get('asentity'), 123);
        $this->assertInternalType('integer', $manager->get('asentity'));
        $this->assertInstanceOf(Entity::class, $manager->getEntity('asentity'));
    }
}