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
     * @Route("/sport/view/{id}", defaults={"id" = 0}, name="sport_view")
     *
     */
    public function sportViewAction(Request $request, $id)
    {
        if($id == 0) {
            $sports = $this->getDoctrine()
                ->getRepository('AppBundle:Sport')->findAll();

            return $this->render('sport/view.html.twig', array(
                'sports' => $sports, 'coaches' => $this->getAllCoaches(),
            ));
        }

        $sport = $this->getDoctrine()
            ->getRepository('AppBundle:Sport')->find($id);

        if(!$sport){
            throw new \Doctrine\ORM\NoResultException;
        }
        else{
            return $this->render('sport/view1.html.twig', array(
                'sport' => $sport,
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


    private function getAllCoaches(){
        return 'not yet implemented';
    }
}
