<?php

namespace App\Security\Guard;

use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\LoginType;
use App\DataTransferObject\Credentials;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class WebAuthenticator extends AbstractFormLoginAuthenticator{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenarator;
    
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
            
    function __construct(UrlGeneratorInterface $urlGenarator, FormFactoryInterface $formFactory, UserPasswordEncoderInterface $userPasswordEncoder) {
        $this->urlGenarator = $urlGenarator;
        $this->formFactory = $formFactory;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function getLoginUrl(){
        return $this->urlGenarator->generate("security_login");
    }
    
    public function supports(Request $request){
        return $request->isMethod(Request::METHOD_POST)
                && $request->attributes->get("_route") === "security_login";
    }
    
    public function getCredentials(Request $request) {
        $credentials = new Credentials();
        $form = $this->formFactory->create(LoginType::class, $credentials);
        $form->handleRequest($request);
        if(!$form->isValid()){
            return;
        }
        return $credentials;
    }
    
    public function getUser($credentials, UserProviderInterface $userProvider) {
        return $userProvider->loadUserByUsername($credentials->getUsername());
    }
    
    /**
     * @param Credentials $credentials
     * @param UserInterface $user
     * @return type
     */
    public function checkCredentials($credentials, UserInterface $user){
        return $this->userPasswordEncoder->isPasswordValid($user, $credentials->getPassword());
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        return new RedirectResponse($this->urlGenarator->generate("blog_home"));
    }
    
}
