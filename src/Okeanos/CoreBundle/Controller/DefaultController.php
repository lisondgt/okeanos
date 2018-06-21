<?php

namespace Okeanos\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OkeanosCoreBundle:Default:index.html.twig');
    }
}
