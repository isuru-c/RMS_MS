<?php

namespace AppBundle\Controller\Event;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/event", name="event_homepage")
     */
    public function eventHomeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('event/home.html.twig', array());
    }
}
