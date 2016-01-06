<?php

namespace AppBundle\Controller\Sport;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SportController extends Controller
{
    /**
     * @Route("/sport", name="sport_homepage")
     */
    public function sportHomeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('sport/home.html.twig', array());
    }
}
