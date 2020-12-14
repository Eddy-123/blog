<?php

namespace App\Form;

use App\DataTransferObject\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class CommentType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    
    public function __construct(AuthorizationCheckerInterface $authorizationChecker) {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content')
        ;
        
        if(!$this->authorizationChecker->isGranted("ROLE_USER"))
        {
            $builder->add('author');
        }
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
