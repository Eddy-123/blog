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

class SecurityController extends AbstractController{
    
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class, new Credentials($authenticationUtils->getLastUsername()));
        $authenticationError = $authenticationUtils->getLastAuthenticationError();
        if($authenticationError !== null){
            $errorMessage = $authenticationError->getMessage();
            $form->addError(new FormError($errorMessage));
        }
        return $this->render("security/login.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
