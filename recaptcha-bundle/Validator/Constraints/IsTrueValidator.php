<?php

namespace Anysrv\RecaptchaBundle\Validator\Constraints;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class IsTrueValidator
 * @package Anysrv\RecaptchaBundle\Validator\Constraints
 */
class IsTrueValidator extends ConstraintValidator
{
    const VERIFY_SERVER = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * IsTrueValidator constructor
     *
     * @param boolean $enabled
     * @param boolean $secret
     * @param RequestStack $requestStack
     */
    public function __construct($enabled, $secret, $requestStack)
    {
        $this->enabled = $enabled;
        $this->secret = $secret;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$this->enabled) {
            return;
        }

        $request = $this->requestStack->getMasterRequest();

        if (!$this->validateResponse($request->getClientIp(), $request->get('g-recaptcha-response'))) {
            $this->context->addViolation($constraint->message);
        }
    }

    /**
     * Validate google result
     *
     * @param string $clientIp
     * @param string $response
     * @return bool
     */
    private function validateResponse($clientIp, $response)
    {
        if ($response === null || strlen($response) === 0) {
            return false;
        }

        $result = $this->doPostRequest([
            'secret' => $this->secret,
            'response' => $response,
            'remoteIp' => $clientIp,
        ]);

        $result = json_decode($result, true);

        if ($result['success'] === true) {
            return true;
        }

        return false;
    }

    /**
     * Send post request to google api
     *
     * @param array $data
     * @return mixed
     */
    private function doPostRequest(array $data)
    {
        if (function_exists('curl_init')) {
            $handle = curl_init(self::VERIFY_SERVER);

            curl_setopt($handle, CURLOPT_POST, count($data));
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($handle);

            curl_close($handle);
        } else {
            $options = [
                'http' => [
                    'method' => 'POST',
                    'content' => $data,
                ],
            ];

            $context = stream_context_create($options);
            $handle = fopen(self::VERIFY_SERVER, 'rb', false, $context);

            $result = stream_get_contents($handle);
        }

        return $result;
    }
}