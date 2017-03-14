<?php
use PHPUnit\Framework\TestCase;

// Resources.
use hisorange\Registry\Pool;
use hisorange\Registry\Manager;
use hisorange\Registry\Entity;
// Exceptions.
use hisorange\Registry\Exceptions\NamespaceMissing;

class PoolTest extends TestCase
{
    /**
     * Test automatic global namespace creation.
     */
    public function testGlobalCreation()
    {
        $this->assertInstanceOf(Manager::class, Pool::get());
        $this->assertTrue(Pool::has('global'));
    }

    /**
     * Test the has checker.
     */
    public function testNotHas()
    {
        $this->assertFalse(Pool::has('nooope'));
        Pool::set('nooope');
        $this->assertTrue(Pool::has('nooope'));
    }

    /**
     * @expectedException \hisorange\Registry\Exceptions\NamespaceMissing
     */
    public function testEmptyNamespace()
    {
        Pool::get('_random_fake_');
    }

    /**
     * Test named registration.
     */
    public function testRegisteredNamespace()
    {
        $manager = new Manager;
        Pool::set('test_registered', $manager);

        $this->assertSame($manager, Pool::get('test_registered'));
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Error
     */
    public function testBadNamespaceSet()
    {
        Pool::set(new Manager);
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Error
     */
    public function testBadNamespaceGet()
    {
        Pool::get(new Manager);
    }

    /**
     * Integers as namespace.
     */
    public function testIntegerNamespace()
    {
        $manager = new Manager;
        Pool::set(PHP_INT_MAX, $manager);
        Pool::set(-PHP_INT_MAX, $manager);
        Pool::set(INF, $manager);
        Pool::set(-INF, $manager);

        $this->assertSame($manager, Pool::get(PHP_INT_MAX));
        $this->assertSame($manager, Pool::get(-PHP_INT_MAX));
        $this->assertSame($manager, Pool::get(INF));
        $this->assertSame($manager, Pool::get(-INF));
    }

    /**
     * Namespace is four space, but not a null, so it not going to be globalised
     */
    public function testSpacedNamespace()
    {
        Pool::set('    ');
        $this->assertInstanceOf(Manager::class, Pool::get('    '));
    }

    /**
     * Use a tab as a namespace.
     */
    public function testTabNamespace()
    {
        Pool::set("\t");
        $this->assertInstanceOf(Manager::class, Pool::get("\t"));
    }

    /**
     * Empty like namespace is not going to be the global namespace.
     */
    public function testGlobalAgainstTab()
    {
        Pool::set("\t", new Manager);
        $this->assertNotSame(Pool::get("\t"), Pool::has('global'));
    }

    /**
     * Set and delete a simple key.
     */
    public function testDeleteExisting()
    {
        Pool::set('test_delete');
        $this->assertNull(Pool::delete('test_delete'));
    }

    /**
     * @depends testDeleteExisting
     * @expectedException \hisorange\Registry\Exceptions\NamespaceMissing
     */
    public function testDeleteMissing()
    {
        Pool::delete('test_delete');
    }
}