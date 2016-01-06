<?php

namespace AppBundle\Controller\Coach;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoachController extends Controller
{
    /**
     * @Route("/coach", name="coach_homepage")
     */
    public function coachHomeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('coach/home.html.twig', array());
    }
}
