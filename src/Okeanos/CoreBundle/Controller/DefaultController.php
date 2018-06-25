<?php

namespace Okeanos\CoreBundle\Controller;

use Okeanos\CoreBundle\Entity\News;
use Okeanos\CoreBundle\Entity\Actions;
use Okeanos\CoreBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entité Advert
        $news = new News();
        $news->setTitle('News 4');
        $news->setDescript("Description de l'article 4");
        $news->setImg("#");
        $news->setDate(new \Datetime());
        
        $user = new Users();
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OkeanosCoreBundle:Users');
        $user = $repository->find(1);

        $news->setUser($user);

        $em->persist($news);

        // On déclenche l'enregistrement
        $em->flush();

        // La gestion d'un formulaire est particulière mais l'idée est la suivante :

        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        if($request->isMethod('POST')){
            // Ici, on s'occupera de la création et de la gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'News bien enregistrée');

            // Puis on redirige vers la page de visualisation de cette annonce
            return $this->redirectToRoute('okeanos_core_newsView', array('id' => $news->getId()));
        }

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:newsAdd.html.twig');
        
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
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entité Advert
        $action = new Actions();
        $action->setName('Ocean cleanup');
        $action->setDescript("Ocean Cleanup is a huge project created by Boyan Slat. The Ocean Cleanup develops advanced technologies to rid the world's oceans of plastic. A full-scale deployment of this systems is estimated to clean up 50% of the Great Pacific Garbage Patch in 5 years and this system could also be used in the Atlantic coast in Britanny.");
        $action->setImg("#");
        $action->setGoal(5000000);
        $action->setStatus(false);

        $em->persist($action);

        // On déclenche l'enregistrement
        $em->flush();

        // La gestion d'un formulaire est particulière mais l'idée est la suivante :

        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        if($request->isMethod('POST')){
            // Ici, on s'occupera de la création et de la gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'Action bien enregistrée');

            // Puis on redirige vers la page de visualisation de cette annonce
            return $this->redirectToRoute('okeanos_core_actionsView', array('id' => $action->getId()));
        }

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:actionsAdd.html.twig');
        
        return new Response($content);
    }

    public function donateAction($id)
    {
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:donate.html.twig');

        return new Response($content);
    }

    public function loginAction()
    {
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:login.html.twig');

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
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entité Advert
        $user = new Users();
        $user->setLogin('pfischer');
        $user->setPwd('d1ae23208f68bee3c6a70528e95bad743ae6261b');
        $user->setMail('fischer.pascal@wanadoo.fr');
        $user->setPermission('admin');

        $em->persist($user);

        // On déclenche l'enregistrement
        $em->flush();

        // La gestion d'un formulaire est particulière mais l'idée est la suivante :

        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        if($request->isMethod('POST')){
            // Ici, on s'occupera de la création et de la gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'User bien enregistré');

            // Puis on redirige vers la page de visualisation de cette annonce
            return $this->redirectToRoute('okeanos_core_usersView', array('id' => $user->getId()));
        }

        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:usersAdd.html.twig');
        
        return new Response($content);
    }
}
