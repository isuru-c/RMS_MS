<?php

namespace AppBundle\Controller\Achievement;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AchievementController extends Controller
{
    /**
     * @Route("/achievement", name="achievement_homepage")
     */
    public function achievementHomeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('achievement/home.html.twig', array());
    }
}
