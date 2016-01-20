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
            ->add('faculty', ChoiceType::class, ['choices' => $faculty_list, 'placeholder'=>'-SELECT-'])
            ->add('department', ChoiceType::class, ['choices' => $department_list, 'placeholder'=>'-SELECT-'])
            ->add('gender', ChoiceType::class,
                    ['choices' => ['Male' => 'Male', 'Female'=>'Female'], 'choices_as_values' => true, 'placeholder'=>'-SELECT-'])
            ->add('birthday', DateType::class,
                    ['input'  => 'datetime', 'widget' => 'choice', 'years' => range(1980,2000),'placeholder'=>'-SELECT-'])
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
     * @Route("/student/view", name="student_view")
     */
    public function studentViewAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM student");
        $statement->execute();
        $students=$statement->fetchAll();

        return $this->render('student/view.html.twig', array('Students'=>$students));

    }

    public function studentViewByStudents(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $query = 'SELECT *'
        $statement = $connection->prepare("SELECT * FROM student");
        $statement->execute();
        $students=$statement->fetchAll();

        return $this->render('student/view.html.twig', array('Students'=>$students));

    }

    /**
     * @Route("/student/my_view/{id}", defaults={"id"=0}, name="student_my_view")

     *
     */
    public function viewStudentsOfOneSport(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $user = $this->getUser();
        $id = $user->getUsername();



        $query = 'SELECT * ';
        $query .='FROM v ';                    // v here is the view
        $query .= 'WHERE sport_id IN (';
        $query .= 'SELECT sport_id ';
        $query .= 'FROM coach ';
        $query .= 'WHERE id = :id';
        $query .= ') ORDER BY id';

        $statement = $connection->prepare($query);
        $statement ->bindValue('id', $id);
        $statement->execute();
        $students = $statement->fetchAll();


        $query1 = 'SELECT s.name ';
        $query1 .= 'FROM sport AS s ';
        $query1 .= 'WHERE s.id IN (';
        $query1 .= 'SELECT c.sport_id ';
        $query1 .= 'FROM coach AS c ';
        $query1 .= 'WHERE c.id = :id';
        $query1 .= ')';

        $statement1 = $connection->prepare($query1);
        $statement1 ->bindValue('id', $id);
        $statement1->execute();
        $sport = $statement1->fetch();

        return $this->render('student/my_view.html.twig', array('Students'=>$students, 'Sport'=>$sport));
    }

    /**
     * @Route("/student/edit/{id}", defaults={"id"=0}, name="student_edit_profile")

     *
     */

    public  function editStudentProfile(Request $request, $id){

        $user = $this->getUser();
        $id = $user->getUsername();

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $query = 'SELECT * ';
        $query .= 'FROM student AS s ';
        $query .= 'WHERE s.id = :id';
        $statement = $connection->prepare($query);
        $statement->bindValue('id', $id);
        $statement->execute();
        $student_ = $statement->fetchAll();

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


        $student->setId($student_[0]['id']);
        $student->setFirstName($student_[0]['first_name']);
        $student->setSecondName($student_[0]['second_name']);
        $student->setFaculty(($student_[0]['faculty']));
        $student->setDepartment($student_[0]['department']);
        $student->setGender($student_[0]['gender']);

        // $birth_day = strtotime($student_[0]['birthday']);
        $birth__day = date('Y-m-d',strtotime($student_[0]['birthday']));//$birth_day);
        //var_dump($birth__day);

        $student->setBirthday(new \DateTime());//$birth__day);
        $student->setContactNumber($student_[0]['contact_number']);
        $student->setEMail($student_[0]['e_mail']);
        $student->setAddress($student_[0]['address']);

        $form = $this->createFormBuilder($student)
            ->add('id', TextType::class)
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class, ['required' => false])
            ->add('faculty', ChoiceType::class, ['choices' => $faculty_list, 'placeholder'=>'-SELECT-'])
            ->add('department', ChoiceType::class, ['choices' => $department_list, 'placeholder'=>'-SELECT-'])
            ->add('gender', ChoiceType::class,
                ['choices' => ['Male' => 'Male', 'Female'=>'Female'], 'choices_as_values' => true, 'placeholder'=>'-SELECT-'])
            ->add('birthday', DateType::class,
                ['input'  => 'datetime', 'widget' => 'choice', 'years' => range(1980,2000),'placeholder'=>'-SELECT-'])
            ->add('contactNumber', TextType::class)
            ->add('eMail', TextType::class, ['required' => false])
            ->add('address', TextType::class, ['required' => false])
            ->add('save', SubmitType::class, ['label'=>'Update Profile'])
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $connection = $em->getConnection();

            $query_student = "UPDATE  student SET";
            $query_student .= " first_name = '" .$student->getFirstName()."', ";
            $query_student .= "second_name = '" .$student->getSecondName() ."', ";
            $query_student .= "faculty='".$student->getFaculty()."',";
            $query_student .= "department = '".$student->getDepartment()."',";
            $query_student .= "gender = '".$student->getGender()."',";
            $query_student .= "birthday ='".$student->getBirthday()->format('y/m/d')."', ";
            $query_student .= "contact_number = '".$student->getContactNumber()."',";
            $query_student .= "e_mail = '".$student->getEMail()."',";
            $query_student .= "address = '".$student->getAddress()."'";
            $query_student .= "WHERE id = '".$student->getId()."'";

            $statement1 = $connection->prepare($query_student);
            $statement1 -> execute();


            return $this->render('student/edit.html.twig');
        }

        return $this->render('student/edit.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/student/view_profile/{id}", defaults={"id"=0}, name="student_view_profile")

     *
     */

    public function viewStudentProfile(Request $request, $id){
        $user = $this->getUser();
        $id = $user->getUsername();

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $query = 'SELECT * ';
        $query .= 'FROM student AS s ';
        $query .= 'WHERE s.id = :id';
        $statement = $connection->prepare($query);
        $statement->bindValue('id', $id);
        $statement->execute();
        $student = $statement->fetchAll();

        return $this->render('student/view_profile.html.twig', array('Student'=>$student));
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
