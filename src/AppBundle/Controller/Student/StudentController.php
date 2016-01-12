<?php

namespace AppBundle\Controller\Student;

use AppBundle\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class StudentController extends Controller
{
    /**
     * @Route("/student", name="student_homepage")
     */
    public function studentHomeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('student/home.html.twig', array());
    }

    /**
     * @Route("/student/new", name="student_new")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function studentNewAction(Request $request){

        $student = new Student();

        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

        $query = "SELECT DISTINCT faculty FROM fac_dep";
        $statement = $connection->query($query);
        $statement->execute();
        $result = $statement->fetchAll();

        $faculty_list = array();

        foreach($result as $fac){
            $faculty_list[$fac['faculty']] = $fac['faculty'];
        }

        $query = "SELECT DISTINCT department FROM fac_dep";
        $statement = $connection->query($query);
        $statement->execute();
        $result = $statement->fetchAll();

        $department_list = array();

        foreach($result as $fac){
            $department_list[$fac['department']] = $fac['department'];
        }

        $form = $this->createFormBuilder($student)
            ->add('id', 'text')
            ->add('firstName', 'text')
            ->add('secondName', 'text', array('required' => false))
            ->add('faculty', 'choice', array('choices' => $faculty_list))
            ->add('department', 'choice', array('choices' => $department_list))
            ->add('gender', 'choice', array('choices' => array('Male' => 'Male', 'Female'=>'Female')))
            ->add('birthday', 'date', array('input'  => 'datetime', 'widget' => 'choice', 'years' => range(1980,2000)))
            ->add('contactNumber', 'text')
            ->add('eMail', 'text', array('required' => false))
            ->add('address', 'text', array('required' => false))
            ->add('save', 'submit', array('label'=>'Add new student'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            return $this->render('student/new.html.twig');
        }

        return $this->render('student/new.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
