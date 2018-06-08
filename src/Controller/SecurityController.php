<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @Method({ "GET", "POST" })
     */
    public function login(Request $request,
                          AuthenticationUtils $au): JsonResponse
    {
        if($request->getMethod('GET')) {
            $lastUsername = $au->getLastUsername();
            $error        = $au->getLastAuthenticationError();
            $template = $this->renderView('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error
            ]);

            return new JsonResponse(['template' => $template]);
        }

        // Symfony Security bundle magic happens if the method is POST
    }

    /**
     * @Route("/status", name="auth_status")
     * @Method({ "POST" })
     */
    public function authStatus(Request $request): Response
    {
        $response = new Response();
        $authenticated = $this->isGranted('IS_AUTHENTICATED_FULLY') ||
                         $this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if($authenticated) {
            $response->setStatusCode(Response::HTTP_OK);
        }
        else {
            $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $response;
    }
}
