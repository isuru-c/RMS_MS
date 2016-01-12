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

    /**
     * @Route("/equipment/{sport_id}", defaults={"sport_id" = 0 }, name="equipment_sport_all")
     */
    public function equipmentSportCategoryAction(Request $request, $sport_id){

        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

        $query = "SELECT * FROM sport WHERE 'id'=$sport_id";
        $statement = $connection->query($query);
        $statement->execute();
        $result = $statement->fetchAll();

        return $this->render('equipment/viewall.html.twig', array(
            'sport' => $result,
        ));
    }
}
