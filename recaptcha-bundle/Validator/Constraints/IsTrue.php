<?php

namespace Anysrv\RecaptchaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsTrue
 * @package Anysrv\RecaptchaBundle\Validator\Constraints
 */
class IsTrue extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is not a valid captcha.';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'anysrv_recaptcha.true';
    }
}