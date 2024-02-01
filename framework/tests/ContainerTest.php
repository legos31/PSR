<?php
namespace Framework\Tests;

//use Framework\container\Container;
use League\Container\Container;
use Framework\container\exceptions\ContainerException;
use PHPUnit\Framework\TestCase;

/**
 * @group disable
 */
class ContainerTest extends TestCase
{

    public function test_getting_service_from_container ()
    {
        $container = new Container();
        $container->add(Telegram::class);
        $container->add(YouTube::class);
        $container->add(Depend::class)->addArguments([Telegram::class, YouTube::class]);
        $container->add('legos', Legos::class)->addArgument(Depend::class);
        $this->assertInstanceOf(Legos::class, $container->get('legos'));
    }

//    public function test_container_has_exception_add_service_wrong () {
//        $container = new Container();
//        $this->expectException(ContainerException::class);
//        $container->add('legos');
//    }

    public function test_has_method ()
    {
        $container = new Container();
        $container->add(Telegram::class);
        $container->add(YouTube::class);
        $container->add(Depend::class)->addArguments([Telegram::class, YouTube::class]);
        $container->add('legos', Legos::class)->addArgument(Depend::class);
        $this->assertTrue($container->has('legos'));
        $this->assertFalse($container->has('legosNo'));
    }

    public function test_autowiring()
    {
        $container = new Container();
        $container->add(Telegram::class);
        $container->add(YouTube::class);
        $container->add(Depend::class)->addArguments([Telegram::class, YouTube::class]);
        $container->add('legos', Legos::class)->addArgument(Depend::class);

        /** @var Legos $legos */
        $legos = $container->get('legos');
        $depend = $legos->getDepend();
        $this->assertInstanceOf(Depend::class, $legos->getDepend());
        $this->assertInstanceOf(Telegram::class, $depend->getTelegram());
        $this->assertInstanceOf(YouTube::class, $depend->getYouTube());
    }
}