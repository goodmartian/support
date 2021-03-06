<?php

namespace Tests\Facades\Helpers;

use Helldar\Support\Facades\Helpers\Instance;
use Tests\Fixtures\Contracts\Contract;
use Tests\Fixtures\Instances\Bar;
use Tests\Fixtures\Instances\Baz;
use Tests\Fixtures\Instances\Foo;
use Tests\TestCase;

final class InstanceTest extends TestCase
{
    public function testOf()
    {
        // Foo
        $this->assertTrue(Instance::of(Foo::class, Foo::class));
        $this->assertFalse(Instance::of(Foo::class, Bar::class));
        $this->assertTrue(Instance::of(Foo::class, Contract::class));

        $this->assertTrue(Instance::of(new Foo(), Foo::class));
        $this->assertFalse(Instance::of(new Foo(), Bar::class));
        $this->assertTrue(Instance::of(new Foo(), Contract::class));

        // Bar
        $this->assertTrue(Instance::of(Bar::class, Bar::class));
        $this->assertFalse(Instance::of(Bar::class, Foo::class));
        $this->assertFalse(Instance::of(Bar::class, Contract::class));

        $this->assertTrue(Instance::of(new Bar(), Bar::class));
        $this->assertFalse(Instance::of(new Bar(), Foo::class));
        $this->assertFalse(Instance::of(new Bar(), Contract::class));
    }

    public function testClassname()
    {
        $this->assertSame('Tests\Fixtures\Instances\Foo', Instance::classname(Foo::class));
        $this->assertSame('Tests\Fixtures\Instances\Bar', Instance::classname(Bar::class));
        $this->assertSame('Tests\Fixtures\Instances\Baz', Instance::classname(Baz::class));

        $this->assertSame('Tests\Fixtures\Instances\Foo', Instance::classname(new Foo()));
        $this->assertSame('Tests\Fixtures\Instances\Bar', Instance::classname(new Bar()));
        $this->assertSame('Tests\Fixtures\Instances\Baz', Instance::classname(new Baz()));

        $this->assertSame('Tests\Fixtures\Contracts\Contract', Instance::classname(Contract::class));

        $this->assertNull(Instance::classname('foo'));
    }

    public function testBasename()
    {
        $this->assertSame('Foo', Instance::basename(Foo::class));
        $this->assertSame('Bar', Instance::basename(Bar::class));
        $this->assertSame('Baz', Instance::basename(Baz::class));

        $this->assertSame('Foo', Instance::basename(new Foo()));
        $this->assertSame('Bar', Instance::basename(new Bar()));
        $this->assertSame('Baz', Instance::basename(new Baz()));

        $this->assertNull(Instance::basename('foo'));
    }

    public function testCall()
    {
        $this->assertSame('ok', Instance::call(new Foo(), 'callDymamic'));
        $this->assertSame('foo', Instance::call(new Foo(), 'unknown', 'foo'));
        $this->assertSame('foo', Instance::call(Foo::class, 'unknown', 'foo'));

        $this->assertNull(Instance::call(Foo::class, 'unknown'));
    }

    public function testCallOf()
    {
        $this->assertSame('ok', Instance::callOf([
            Contract::class => 'callDymamic',
        ], new Foo()));

        $this->assertSame('ok', Instance::callOf([
            'Unknown'       => 'unknown',
            Contract::class => 'callDymamic',
        ], new Foo()));

        $this->assertSame('foo', Instance::callOf([
            'Unknown' => 'unknown',
        ], new Foo(), 'foo'));

        $this->assertNull(Instance::callOf([
            'Unknown' => 'unknown',
        ], new Foo()));

        $this->assertNull(Instance::callOf([
            'Unknown' => 'unknown',
        ], 'foo'));
    }

    public function testCallsWhenNotEmpty()
    {
        $this->assertSame('ok', Instance::callWhen(new Foo(), 'callDymamic'));
        $this->assertSame('ok', Instance::callWhen(new Foo(), ['unknown', 'callDymamic']));
        $this->assertSame('foo', Instance::callWhen(new Foo(), 'unknown', 'foo'));
        $this->assertSame('foo', Instance::callWhen(Foo::class, 'unknown', 'foo'));

        $this->assertNull(Instance::callWhen(Foo::class, 'unknown'));
    }

    public function testExists()
    {
        $this->assertTrue(Instance::exists(new Foo()));
        $this->assertTrue(Instance::exists(new Bar()));
        $this->assertTrue(Instance::exists(new Baz()));

        $this->assertTrue(Instance::exists(Foo::class));
        $this->assertTrue(Instance::exists(Bar::class));
        $this->assertTrue(Instance::exists(Baz::class));

        $this->assertTrue(Instance::exists(Contract::class));

        $this->assertFalse(Instance::exists('foo'));
    }
}
