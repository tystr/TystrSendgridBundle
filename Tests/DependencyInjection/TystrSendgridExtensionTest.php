<?php

namespace Tystr\Bundle\SendgridBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tystr\Bundle\SendgridBundle\DependencyInjection\TystrSendgridExtension;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class TystrSendgridExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TystrSendgridExtension
     */
    protected $extension;

    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function setUp()
    {
        $this->extension = new TystrSendgridExtension();
        $this->container = new ContainerBuilder();
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testConfigWithoutPasswordThrowsException()
    {
        $config = array(
            'username' => 'tyler',
        );
        $this->extension->load(array($config), $this->container);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testConfigWithoutUsernameThrowsException()
    {
        $config = array(
            'password' => 'pa$$w0rd',
        );
        $this->extension->load(array($config), $this->container);
    }

    public function testConfigWithEnableShortAliasIsFalse()
    {
        $config = array(
            'username' => 'tyler',
            'password' => 'pa$$w0rd',
            'enable_short_alias' => false,
        );
        $this->extension->load(array($config), $this->container);
        $this->assertFalse($this->container->hasAlias('sendgrid'));
    }

    public function testConfigWithDefaults()
    {
        $this->loadDefaults();
        $this->assertTrue($this->container->hasAlias('sendgrid'));
    }

    public function assertParameters()
    {
        $this->loadDefaults();
        $this->assertEquals('tyler', $this->container->get('tystr_sendgrid.username'));
        $this->assertEquals('pa$$w0rd', $this->container->get('tystr_sendgrid.password'));
    }

    public function testSendgridClassParameterExists()
    {
        $this->loadDefaults();
        $this->assertTrue($this->container->hasParameter('tystr_sendgrid.sendgrid.class'));
    }

    public function testSendgridServiceDefinition()
    {
        $this->loadDefaults();
        $this->assertTrue($this->container->hasDefinition('tystr_sendgrid.sendgrid'));
    }

    public function testSendgridServiceInstantiation()
    {
        $this->loadDefaults();
        $this->assertInstanceOf(
            $this->container->getParameter('tystr_sendgrid.sendgrid.class'),
            $this->container->get('tystr_sendgrid.sendgrid')
        );
    }

    protected function loadDefaults()
    {
        $config = array(
            'username' => 'tyler',
            'password' => 'pass',
        );
        $this->extension->load(array($config), $this->container);
    }
}
