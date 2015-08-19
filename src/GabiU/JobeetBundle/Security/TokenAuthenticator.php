<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 19.08.2015
 * Time: 21:40
 */

namespace GabiU\JobeetBundle\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator implements SimplePreAuthenticatorInterface {

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof TokenUserProvider)
        {
            throw new InvalidArgumentException(
                sprintf("The user provider must be instance of TokenUserProvider; was given: %s", get_class($userProvider))
            );
        }

        $apikey = $token->getCredentials();
        $username = $userProvider->getUsernameForToken($apikey);

        if (!$username)
        {
            throw new AuthenticationException(sprintf(
                "API Key %s does not exist", $apikey
            ));
        }

        $user = $userProvider->loadUserByUsername($apikey);

        return new PreAuthenticatedToken($user, $apikey, $providerKey, $user->getRoles());
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $providerKey)
    {
        $apikey = $request->get('token');

        if (!$apikey)
        {
            throw new BadCredentialsException('No API Key found!');
        }

        return new PreAuthenticatedToken('anon.', $apikey, $providerKey);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response("Authentication failed", 403);
    }

}