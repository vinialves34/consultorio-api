<?php

namespace App\Security;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * JwtAutenticador
 */
class JwtAutenticador extends AbstractGuardAuthenticator {    
       
    /**
     * Method __construct
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Method start
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return void
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        
    }
    
    /**
     * Method supports
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() != '/login';
    }
        
    /**
     * Method getCredentials
     * @param Request $request
     * @return void
     */
    public function getCredentials(Request $request)
    {
        $token = str_replace('Bearer ', '', $request->headers->get('Authorization'));

        try {
            return JWT::decode($token, 'chaveTeste', ['HS256']);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Method getUser
     * @param $credentials
     * @param UserInterface $user
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (!is_object($credentials) || !property_exists($credentials, 'username')) {
            return null;
        }

        $username = $credentials->username;
        return $this->repository->findOneBy(['username' => $username]);
    }
    
    /**
     * Method checkCredentials
     * @param $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return (is_object($credentials) && property_exists($credentials, 'username'));
    }

    /**
     * Method onAuthenticationFailure
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'erro' => 'Falha de autenticação'
        ], Response::HTTP_UNAUTHORIZED);   
    }
        
    /**
     * Method onAuthenticationSuccess
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }
    
    /**
     * Method supportsRememberMe
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}