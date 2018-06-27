<?php
// src/Okeanos/UserBundle/Controller/SecurityController.php

namespace Okeanos\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('okeanos_core_home');
        }

        // Le service authentification_utils permet de récupérer le nom d'utilisateur et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide (mauvais mdp par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('UserBundle:Security:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ));
    }
}