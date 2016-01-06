<?php

namespace AppBundle\Controller\Equipment;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EquipmentController extends Controller
{
    /**
     * @Route("/equipment", name="equipment_homepage")
     */
    public function equipmentHomeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('equipment/home.html.twig', array());
    }
}
