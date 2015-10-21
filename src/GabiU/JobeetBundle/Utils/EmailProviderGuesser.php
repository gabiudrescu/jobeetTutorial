<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 21.10.2015
 * Time: 23:28
 */

namespace GabiU\JobeetBundle\Utils;


use GabiU\JobeetBundle\Utils\Exceptions\EmailNotValidException;
use GabiU\JobeetBundle\Utils\Exceptions\UnexpectedValueException;


/**
 * Class EmailProviderGuesser
 *
 * Based on this idea: http://stackoverflow.com/questions/6917198/php-check-domain-of-email-being-registered-is-a-school-edu-address
 *
 * @package GabiU\JobeetBundle\Utils
 */
class EmailProviderGuesser {

    /**
     * @param $email
     *
     * @return bool
     * @throws EmailNotValidException
     */
    public function guess($email)
    {
        $emailComponents = $this->getComponents($this->ensureValidEmail($email));

        if (array_key_exists($emailComponents[1], $this->getProviderDomains()))
        {
            return $this->getWebmail($emailComponents[1]);
        }

        return false;
    }

    private function ensureValidEmail($email)
    {
        if (false === filter_var((string) $email, FILTER_VALIDATE_EMAIL))
        {
            throw new EmailNotValidException(sprintf('Email %s is not valid', $email));
        }

        return $email;
    }

    private function getComponents($email)
    {
        return explode('@',$email);
    }

    private function getProviderDomains()
    {
        return array(
            'gmail.com' => 'Gmail',
            'googlemail.com' => 'Gmail',
            'yahoo.com' => 'Yahoo',
            'outlook.com' => 'Microsoft'
        );
    }

    private function getProvidersWebmails()
    {
        return array(
            'Gmail' => 'https://mail.google.com/',
            'Yahoo' => 'https://mail.yahoo.com',
            'Microsoft' => 'https://login.live.com/'
        );
    }

    private function getWebmail($provider)
    {
        if (false === array_key_exists($provider, $this->getProvidersWebmails()))
        {
            throw new UnexpectedValueException(sprintf('Provider %s is not defined in method getProvidersWebmails', $provider));
        }

        return $this->getProvidersWebmails()[$provider];
    }
}