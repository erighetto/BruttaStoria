AnysrvRecaptchaBundle
=====================

[![Build Status](https://travis-ci.org/exii88/AnysrvRecaptchaBundle.svg?branch=master)](https://travis-ci.org/exii88/AnysrvRecaptchaBundle)
[![Latest Stable Version](https://poser.pugx.org/anysrv/recaptcha-bundle/v/stable)](https://packagist.org/packages/anysrv/recaptcha-bundle)
[![Total Downloads](https://poser.pugx.org/anysrv/recaptcha-bundle/downloads)](https://packagist.org/packages/anysrv/recaptcha-bundle)
[![License](https://poser.pugx.org/anysrv/recaptcha-bundle/license)](https://packagist.org/packages/anysrv/recaptcha-bundle)
[![Code Climate](https://codeclimate.com/github/exii88/AnysrvRecaptchaBundle/badges/gpa.svg)](https://codeclimate.com/github/exii88/AnysrvRecaptchaBundle)
[![Test Coverage](https://codeclimate.com/github/exii88/AnysrvRecaptchaBundle/badges/coverage.svg)](https://codeclimate.com/github/exii88/AnysrvRecaptchaBundle/coverage)


reCAPTCHA v2 form field integration for Symfony 3

## Installation

### Step 1: Use composer and enable the bundle

To install AnysrvRecaptchaBundle with Composer just type in your terminal:

```bash
php composer.phar require anysrv/recaptcha-bundle
```

Now, Composer will automatically download all required files, and install them
for you. All that is left to do is to update your ``AppKernel.php`` file and
register the new bundle:

```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Anysrv\RecaptchaBundle\AnysrvRecaptchaBundle(),
    // ...
);
```

### Step 2: Configure the bundle

Add the following settings to your config file:

```yaml
# app/config/config.yml

anysrv_recaptcha:
    sitekey: enter_your_sitekey_from_google
    secret: enter_your_secret_from_google
```

If you want to overwrite the locale you must set the locale in your config:

```yaml
# app/config/config.yml

anysrv_recaptcha:
    // ...
    overwrite_locale: cn
```

## Basic usage

When creating a new form class add the following line to create the field:

```php
use Anysrv\RecaptchaBundle\Form\Type\AnysrvRecaptchaType;
use Anysrv\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

public function buildForm(FormBuilder $builder, array $options)
{
    // ...
    $builder->add('recaptcha', AnysrvRecaptchaType::class, array(
        'mapped' => false,
        'constraints' => array(
            new RecaptchaTrue(),
        ),
    ));
    // ...
}
```
