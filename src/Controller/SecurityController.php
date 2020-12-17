<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;
use App\DataTransferObject\Credentials;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; 
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;

class SecurityController
{
    
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(
        AuthenticationUtils $authenticationUtils, 
        FormFactoryInterface $formFactory,
        Environment $twig
    ): Response {
        $form = $formFactory->create(LoginType::class, new Credentials($authenticationUtils->getLastUsername()));
        $authenticationError = $authenticationUtils->getLastAuthenticationError();
        if($authenticationError !== null){
            $errorMessage = $authenticationError->getMessage();
            $form->addError(new FormError($errorMessage));
        }
        return new Response($twig->render("security/login.html.twig", [
            "form" => $form->createView()
        ]));
    }
    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){
        
    }
}
