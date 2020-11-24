<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder) {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $dadosJson = json_decode($request->getContent());

        if (is_null($dadosJson->usuario) || is_null($dadosJson->senha)) {
            return new JsonResponse([
                'erro' => 'Favor enviar usuário e senha'

            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findOneBy(['username' => $dadosJson->usuario]);
        
        if (!$this->encoder->isPasswordValid($user, $dadosJson->senha)) {
            return new JsonResponse([
                'erro' => 'Usuário ou senha inválidos'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = JWT::encode(['username' => $user->getUsername()], 'chaveTeste');
    
        return new JsonResponse([
            'token' => $token
        ]);
    }
}