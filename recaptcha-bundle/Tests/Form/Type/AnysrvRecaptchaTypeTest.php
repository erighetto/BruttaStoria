<?php

namespace Anysrv\RecaptchaBundle\Tests\Form\Type;

use Anysrv\RecaptchaBundle\Form\Type\AnysrvRecaptchaType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class AnysrvRecaptchaTypeTest
 * @package Anysrv\RecaptchaBundle\Tests\Form\Type
 */
class AnysrvRecaptchaTypeTest extends TypeTestCase
{
    const TEST_SITEKEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    const TEST_SECRET = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

    /**
     * @var AnysrvRecaptchaType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->type = new AnysrvRecaptchaType(true, self::TEST_SITEKEY, array(), 'de');

        parent::setUp();
    }

    /**
     * @covers \Anysrv\RecaptchaBundle\Form\Type\AnysrvRecaptchaType::getName()
     */
    public function testGetName()
    {
        $this->assertEquals('anysrv_recaptcha', $this->type->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        unset($this->type);

        parent::tearDown();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        return array(new PreloadedExtension(array(
            $this->type->getName() => $this->type,
        ), array()));
    }
}