<?php

namespace Okeanos\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OkeanosCoreBundle:Default:index.html.twig');
    }

    public function newsAction()
    {
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:news.html.twig');

        return new Response($content);
    }

    public function newsViewAction($id)
    {
        $content = $this
            ->get('templating')
            ->render('OkeanosCoreBundle:Default:newsView.html.twig');

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
