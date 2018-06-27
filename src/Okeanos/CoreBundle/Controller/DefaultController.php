<?php

namespace Okeanos\CoreBundle\Controller;

use Okeanos\CoreBundle\Entity\News;
use Okeanos\CoreBundle\Entity\Actions;
use Okeanos\CoreBundle\Entity\Users;
use Okeanos\CoreBundle\Entity\Donates;
use Okeanos\CoreBundle\Form\NewsType;
use Okeanos\CoreBundle\Form\ActionsType;
use Okeanos\CoreBundle\Form\UsersType;
use Okeanos\CoreBundle\Form\DonatesType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OkeanosCoreBundle:Default:index.html.twig');
    }

    public function newsAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OkeanosCoreBundle:News');
        
        $news = $repository->findAll();

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:news.html.twig',
                array('news' => $news));

        return new Response($content);
    }

    public function newsViewAction($id)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OkeanosCoreBundle:News');

        $news = $repository->find($id);

        if(null === $news)
        {
            throw new NotFoundHttpException("Cette news n'existe pas");
        }

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:newsView.html.twig',
                array('news' => $news));

        return new Response($content);
    }

    public function newsAddAction(Request $request){
        $news = new News();
        $form = $this->get('form.factory')->create(NewsType::class, $news);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'News bien enregistrée');

            return $this->redirectToRoute('okeanos_core_news', array(
                'id' => $news->getId()
            ));
        }

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:newsAdd.html.twig', array(
                'form' => $form->createView(),
            ));
        
        return new Response($content);
    }

    public function actionsAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OkeanosCoreBundle:Actions');
        
        $actions = $repository->findAll();

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:actions.html.twig',
                array('actions' => $actions));

        return new Response($content);
    }

    public function actionsViewAction($id)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OkeanosCoreBundle:Actions');

        $action = $repository->find($id);

        if(null === $action)
        {
            throw new NotFoundHttpException("Cette action n'existe pas");
        }

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:actionsView.html.twig',
                array('action' => $action));

        return new Response($content);
    }

    public function actionsAddAction(Request $request){
        // On crée un objet Actions
        $action = new Actions();
        
        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $action);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('name', TextType::class)
            ->add('descript', TextareaType::class)
            ->add('content', TextareaType::class)
            ->add('img', TextareaType::class)
            ->add('goal', IntegerType::class)
            ->add('status', ChoiceType::class, array(
                'choices' => array(
                    'Unreached' => false,
                    'Reached' => true
                )
            ))
            ->add('save', SubmitType::class)
        ;
        
        // A partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        // Si la requête est en POST
        if($request->isMethod('POST'))
        {
            // On fait le lien Requête <-> Formulaire
            // A partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($action);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Action bien enregistrée');

                // On redirige vers la page du profil utilisateur
                return $this->redirectToRoute('okeanos_core_actions', array(
                    'id' => $action->getId()
                ));
            }
        }

        // On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher le formulaire toute seule
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:actionsAdd.html.twig', array(
                'form' => $form->createView(),
            ));
        
        return new Response($content);
    }

    public function donateAction(Request $request, $id)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $donate = new Donates();
            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $donate);

            $formBuilder
                ->add('action', EntityType::class, array(
                    'class' => 'OkeanosCoreBundle:Actions',
                    'choice_label' => 'name',
                    'multiple' => false,
                ))
                ->add('user', EntityType::class, array(
                    'class' => 'OkeanosCoreBundle:Users',
                    'choice_label' => 'id',
                    'multiple' => false,
                ))
                ->add('amount', IntegerType::class)
                ->add('save', SubmitType::class);

            $form = $formBuilder->getForm();

            if($request->isMethod('POST'))
            {
                $form->handleRequest($request);

                if($form->isValid())
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($donate);
                    $em->flush();
        
                    $request->getSession()->getFlashBag()->add('notice', 'Your donate has been registered');
        
                    return $this->redirectToRoute('okeanos_core_donate', array(
                        'id' => $donate->getId()
                    ));
                }
            }

            $content = $this
                ->get('templating')
                ->render('OkeanosCoreBundle:Default:donate.html.twig', array(
                    'form' => $form->createView(),
                ));
            
            return new Response($content);
        }

        // Le service authentification_utils permet de récupérer le nom d'utilisateur et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide (mauvais mdp par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('UserBundle:Security:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ));
    }

    public function signupAction(Request $request)
    {
        // On crée un objet Users
        $user = new Users();
        
        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('email', EmailType::class)
            /*->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'Visitor' => 'ROLE_USER',
                    'Administrator' => 'ROLE_ADMIN'
                )
            ))*/
            ->add('save', SubmitType::class)
        ;
        
        // A partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        // Si la requête est en POST
        if($request->isMethod('POST'))
        {
            // On fait le lien Requête <-> Formulaire
            // A partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien enregistré');

                // On redirige vers la page du profil utilisateur
                return $this->redirectToRoute('login');
            }
        }

        // On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher le formulaire toute seule
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:signup.html.twig', array(
                'form' => $form->createView(),
            ));
        
        return new Response($content);
    }

    public function profileAction()
    {
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:profile.html.twig');
        
        return new Response($content);
    }

    public function usersAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OkeanosCoreBundle:Users');
        
        $users = $repository->findAll();

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:users.html.twig',
                array('users' => $users));

        return new Response($content);
    }

    public function usersAddAction(Request $request){
        // On crée un objet Users
        $user = new Users();
        
        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('email', EmailType::class)
            /*->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'Visitor' => 'ROLE_USER',
                    'Administrator' => 'ROLE_ADMIN'
                )
            ))*/
            ->add('save', SubmitType::class)
        ;
        
        // A partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        // Si la requête est en POST
        if($request->isMethod('POST'))
        {
            // On fait le lien Requête <-> Formulaire
            // A partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien enregistré');

                // On redirige vers la page du profil utilisateur
                return $this->redirectToRoute('login');
            }
        }

        // On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher le formulaire toute seule
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:usersAdd.html.twig', array(
                'form' => $form->createView(),
            ));
        
        return new Response($content);
    }

    public function adminAction()
    {
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:admin.html.twig');

        return new Response($content);
    }
}
