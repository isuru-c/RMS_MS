<?php

namespace AppBundle\Controller\Student;

use AppBundle\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        $em = $this->getDoctrine()->getManager();
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
            ->add('id', TextType::class)
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class, ['required' => false])
            ->add('faculty', ChoiceType::class, ['choices' => $faculty_list])
            ->add('department', ChoiceType::class, ['choices' => $department_list])
            ->add('gender', ChoiceType::class,
                    ['choices' => ['Male' => 'Male', 'Female'=>'Female'], 'choices_as_values' => true])
            ->add('birthday', DateType::class,
                    ['input'  => 'datetime', 'widget' => 'choice', 'years' => range(1980,2000)])
            ->add('contactNumber', TextType::class)
            ->add('eMail', TextType::class, ['required' => false])
            ->add('address', TextType::class, ['required' => false])
            ->add('save', SubmitType::class, ['label'=>'Add new student'])
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

    /**
     * @Route("/student/{id}", defaults={"id"=0}, name="student_single_view")
     */
    public function studentSingleViewAction(Request $request, $id){

        if($id == 0){
            return $this->redirectToRoute('student_view');
        }

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $query = "SELECT * FROM student WHERE id=$id";

        $statement = $connection->prepare($query);
        $statement->execute();
        $student = $statement->fetchAll();

        return $this->render('student/single.html.twig', array(
            'student' => $student,
        ));
    }

    /**
     * @Route("/student/view", name="student_view")
     */
    public function studentViewAction(Request $request, $id){


    }
}
