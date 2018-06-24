<?php

namespace Okeanos\CoreBundle\Controller;

use Okeanos\CoreBundle\Entity\News;
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
        $news->setTitle('Recherche intégrateur AngularJS.');
        $news->setDescript("Nous recherchons un intégrateur sur Dijon. Blabla...");
        $news->setImg("#");
        $news->setDate(new \Datetime());

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
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:actions.html.twig');

        return new Response($content);
    }

    public function actionsViewAction($id)
    {
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:actionsView.html.twig',
                array('action' => $id));

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
}
