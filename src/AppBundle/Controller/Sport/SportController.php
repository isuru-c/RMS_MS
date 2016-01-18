<?php

namespace AppBundle\Controller\Sport;

use AppBundle\Entity\Sport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SportController extends Controller
{
    /**
     * @Route("/sport", name="sport_homepage")
     */
    public function sportHomeAction(Request $request)
    {
        return $this->render('sport/home.html.twig', array());
    }

    /**
     * @Route("/sport/new", name="sport_new")
     * @Security("has_role('ROLE_ADMIN')")
     *
     */
    public function sportNewAction(Request $request)
    {
        $sport = new Sport();

        $form = $this->createFormBuilder($sport)
            ->add('name', 'text')
            ->add('gender', 'choice', array('choices' => array('Male' => 'Male', 'Female'=>'Female')))
            ->add('minimumAge', 'integer')
            ->add('description', 'textarea')
            ->add('save', 'submit', array('label' => 'Add new Sport'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($sport);
            $em->flush();

            return $this->render('sport/new.html.twig');
        }

        return $this->render('sport/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/sport/view/{id}", defaults={"id" = 0}, name="sport_view")     *
     */
    public function sportViewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        if($id == 0) {

            $query = "SELECT * FROM sport";
            $statement = $connection->prepare($query);
            $statement->execute();
            $sports = $statement->fetchAll();

            return $this->render('sport/view.html.twig', array(
                'sports' => $sports, 'coaches' => 'need to get coach list',
            ));
        }

        $query = "SELECT * FROM sport WHERE id=" . $id ;
        $statement = $connection->prepare($query);
        $statement->execute();
        $sport = $statement->fetchAll();

        if(!$sport){
            throw new \Doctrine\ORM\NoResultException;
        }
        else{

            return $this->render('sport/view1.html.twig', array(
                'sport' => $sport[0], 'ach_sum' => $this->getAchievementSummary($id),
                'stu_sum' => $this->getStudentSummary($id), 'evn_sum' => $this->getEventSummary($id),
                'equ_sum' => $this->getEquipmentSummary($id),
            ));
        }
    }

    /**
     * @Route("/sport/edit/{id}", defaults={"id" = 0}, name="sport_edit")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function sportEditAction(Request $request, $id)
    {
        $sport = $this->getDoctrine()
            ->getRepository('AppBundle:Sport')->find($id);

        if(!$sport){
            throw new \Doctrine\ORM\NoResultException;
        }

        $form = $this->createFormBuilder($sport)
            ->add('name', 'text')
            ->add('gender', 'choice', array('choices' => array('Male' => 'Male', 'Female'=>'Female')))
            ->add('minimumAge', 'integer')
            ->add('description', 'textarea')
            ->add('save', 'submit', array('label' => 'Update details'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($sport);
            $em->flush();

            return $this->render('sport/edit.html.twig');
        }

        return $this->render('sport/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    function getStudentSummary($id){

        return "still have to implement";
    }

    public function getEventSummary($id){

        return "still have to implement";
    }

    public function getAchievementSummary($id){

        return "still have to implement";
    }

    public function getEquipmentSummary($id){

        return "still have to implement";
    }

}
