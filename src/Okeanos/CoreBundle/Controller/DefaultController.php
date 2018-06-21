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
        $content = $this->get('templating')->render('OkeanosCoreBundle:Default:news.html.twig');

        return new Response($content);
    }

    public function actionsAction()
    {
        $content = $this->get('templating')->render('OkeanosCoreBundle:Default:actions.html.twig');

        return new Response($content);
    }

    public function donateAction()
    {
        $content = $this->get('templating')->render('OkeanosCoreBundle:Default:donate.html.twig');

        return new Response($content);
    }

    public function loginAction()
    {
        $content = $this->get('templating')->render('OkeanosCoreBundle:Default:login.html.twig');

        return new Response($content);
    }
}
