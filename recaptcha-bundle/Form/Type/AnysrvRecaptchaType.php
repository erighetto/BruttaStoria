<?php

namespace Anysrv\RecaptchaBundle\Form\Type;

use Anysrv\RecaptchaBundle\Utils\Locale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AnysrvRecaptchaType
 * @package Anysrv\Form\Type
 */
class AnysrvRecaptchaType extends AbstractType
{
    const API_SERVER = 'https://www.google.com/recaptcha/api.js?hl=';

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $sitekey;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string|Locale
     */
    protected $locale;

    /**
     * AnysrvRecaptchaType constructor
     *
     * @param boolean $enabled
     * @param string $sitekey
     * @param array $options
     * @param string|Locale $locale
     */
    public function __construct($enabled, $sitekey, array $options, $locale)
    {
        $this->enabled = $enabled;
        $this->sitekey = $sitekey;
        $this->options = $options;
        $this->locale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'anysrv_recaptcha_enabled' => $this->enabled,
        ));

        if (!$this->enabled) {
            return;
        }

        if (!isset($options['language'])) {
            if ($this->locale instanceof Locale) {
                $options['language'] = $this->locale->resolve();
            } elseif (is_string($this->locale)) {
                $options['language'] = $this->locale;
            }
        }

        $view->vars = array_replace($view->vars, array(
            'anysrv_recaptcha_api' => self::API_SERVER . $options['language'],
            'anysrv_recaptcha_sitekey' => $this->sitekey,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'language' => $this->locale->resolve(),
            'sitekey' => null,
            'attr' => [
                'options' => [
                    'theme' => $this->options['theme'],
                    'type' => $this->options['type'],
                    'size' => $this->options['size'],
                    'tabindex' => $this->options['tabindex'],
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'anysrv_recaptcha';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'anysrv_recaptcha';
    }
}