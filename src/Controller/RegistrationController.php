<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Handler\RegistrationHandler;
use Twig\Environment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationController
{
    /**
     * @Route("/inscription", name="registration")
     */
    public function __invoke(
        Request $request, 
        RegistrationHandler $registrationHandler,
        Environment $twig,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $user = new User();
        if($registrationHandler->handle($request, $user)){
            return new RedirectResponse($urlGenerator->generate("security_login"));
        }
        return new Response($twig->render("registration.html.twig", [
            "form" => $registrationHandler->createView()
        ]));
    }
}
