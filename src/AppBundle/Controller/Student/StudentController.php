<?php

namespace AppBundle\Controller\Student;

use AppBundle\Entity\Play;
use AppBundle\Entity\Student;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\Date;

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
        $statement = $connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        $faculty_list = array();

        foreach($result as $fac){
            $faculty_list[$fac['faculty']] = $fac['faculty'];
        }

        $query = "SELECT DISTINCT department FROM fac_dep";
        $statement = $connection->prepare($query);
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

            $query_student = "INSERT INTO student ";
            $query_student .= "(id, first_name, second_name, faculty, department, gender, ";
            $query_student .= "birthday, contact_number, e_mail, address)";
            $query_student .= "VALUES ";
            $query_student .= "('" . $student->getId() . "', '" . $student->getFirstName() . "', '" . $student->getSecondName() . "', ";
            $query_student .= "'" . $student->getFaculty() . "', '" . $student->getDepartment() . "', '" . $student->getGender() . "', ";
            $query_student .= "'" . $student->getBirthday()->format('y/m/d') . "', '" . $student->getContactNumber() . "', ";
            $query_student .= "'" . $student->getEMail() . "', '" . $student->getAddress() . "' )";

            $user = new User();
            $initial_password = "pass@" . $student->getId();
            $encoder = $this->container->get('security.password_encoder');
            $encoded_password = $encoder->encodePassword($user, $initial_password);

            $user->setUsername($student->getId());
            $user->setPassword($encoded_password);
            $user->setRole('ROLE_STUDENT');

            $query_user = "INSERT INTO user (username, password, role) VALUES ";
            $query_user .= "('" . $user->getUsername() . "', '" . $user->getPassword() . "', '" . $user->getRole() . "' )";

            $statement = $connection->prepare($query_student);
            $statement->execute();

            $statement = $connection->prepare($query_user);
            $statement->execute();

            return $this->render('student/new.html.twig', array(
                'student' => $student,
            ));
        }

        return $this->render('student/new.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/student/profile/{id}", defaults={"id"=0}, name="student_single_view")
     */
    public function studentSingleViewAction(Request $request, $id){

        if($id == 0){
            return $this->redirectToRoute('student_view');
        }

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $query = "SELECT * FROM student WHERE id='$id'";

        $statement = $connection->prepare($query);
        $statement->execute();
        $student = $statement->fetchAll();

        return $this->render('student/single.html.twig', array(
            'student' => $student[0],
        ));
    }

    /**
     * @Route("/student/view", name="student_view")
     */
    public function studentViewAction(Request $request, $id){


    }

    /**
     * @Route("student/assign", name="assign_sport")
     */
    public function assignSport(Request $request){

        if($request->getMethod() == 'POST'){

            $form = $request->request->get('form');
            $student_id = $form['student'];
            $sport_id = $form['sport'] + 1;
            $sd = $form['start_date'];
            $start_date = $sd['year'] . '/' . $sd['month'] . '/' . $sd['day'];

            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();

            $query1 = "SELECT first_name, second_name, gender, birthday FROM student WHERE id='$student_id'";
            $statement = $connection->prepare($query1);
            $statement->execute();
            $result1 = $statement->fetchAll();

            //student validation for existance

            $query2 = "SELECT name, gender, minimum_age FROM sport WHERE id='$sport_id'";
            $statement = $connection->prepare($query2);
            $statement->execute();
            $result2 = $statement->fetchAll();

            //student validation for sport

            $query3 = "INSERT INTO play (student_id, sport_id, start_date) VALUES ";
            $query3 .= "('" . $student_id . "', '" . $sport_id . "', '" . $start_date . "')";
            $statement = $connection->prepare($query3);
            $statement->execute();

            return $this->render('student/assign.html.twig', array(
                'msg' => "Student added to the sport ",
            ));
        }


        $em = $this->getDoctrine()->getManager();
        $sports = $em->getRepository('AppBundle:Sport')->findAll();

        $play = new Play();

        $form = $this->createFormBuilder($play)
            ->add('student', TextType::class)
            ->add('sport', ChoiceType::class, [
                'choices' => $sports,
                'choice_label' => function($sport, $key, $index) {
                    return $sport->getName() . ' - ' . $sport->getGender();
                },
                'choice_attr' => function($sport, $key, $index) {
                    return ['value' => $sport->getId()];
                },
                'choices_as_values' => true,
                'placeholder' => 'Choose a sport',
            ])
            ->add('start_date', DateType::class, [
                'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
            ])
            ->add('save', SubmitType::class, ['label' => 'Add student to sport'])
            ->getForm();

        return $this->render('student/assign.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
