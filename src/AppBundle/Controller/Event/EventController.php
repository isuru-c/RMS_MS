<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventController extends Controller
{
    /**
     * @Route("/event", name="event_homepage")
     */
    public function eventHomeAction(Request $request)
    {
        return $this->render('event/home.html.twig', array());
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function eventNewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sports = $em->getRepository('AppBundle:Sport')->findAll();

        $event = new Event();

        $form = $this->createFormBuilder($event)
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
            ->add('type', ChoiceType::class, [
                'choices' => ['Single' => 1, 'Dual' => 2, 'Team' => 3],
                'choices_as_values' => true,
                'placeholder' => 'Choose an event type',
            ])
            ->add('title', TextType::class)
            ->add('startDate', DateType::class)
            ->add('endDate', DateType::class)
            ->add('state', ChoiceType::class, [
                'choices' => ['Won' => 1, 'Lost' => 2, 'Draw' => 3, 'Not yet' => 4],
                'choices_as_values' => true,
                'placeholder' => 'Choose the state',
            ])
            ->add('opponent', TextType::class, ['required' => false])
            ->add('remarks', TextareaType::class, ['required' => false])
            ->add('save', SubmitType::class, ['label' => 'Add new event'])
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){

            $connection = $em->getConnection();

            $query1 = "INSERT INTO event (sport_id, type, title, start_date, end_date, state, opponent, remarks) VALUES ";
            $query1 .= "(" . $event->getSport()->getId() . ", " . $event->getType() . ", '" . $event->getTitle() . "', ";
            $query1 .= "'" . $event->getStartDate()->format('y/m/d') . "', '" . $event->getEndDate()->format('y/m/d') . "', ";
            $query1 .= "" . $event->getState() . ", '" . $event->getOpponent() . "', '" .  $event->getRemarks() . "')";

            $statement = $connection->prepare($query1);
            $statement->execute();

            $query2 = "SELECT name, gender FROM sport WHERE id=" . $event->getSport()->getId();

            $statement = $connection->prepare($query2);
            $statement->execute();
            $sport = $statement->fetchAll();

            return $this->render('event/new.html.twig', array(
                'event' => $event, 'sport' => $sport[0], 's_date' => $event->getStartDate()->format('y/m/d'),
                'e_date' => $event->getEndDate()->format('y/m/d'),
            ));
        }

        return $this->render('event/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/event/view/{sport_id}", defaults={"sport_id"=0}, name="event_view")
     */
    public function viewEventAction(Request $request, $sport_id){

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $query = "SELECT id, name, gender FROM sport";

        $statement = $connection->prepare($query);
        $statement->execute();
        $sports = $statement->fetchAll();

        if($request->isMethod('POST')){
            $sport_id = $request->request->get('sport_id');
        }

        if($sport_id == 0){

            return $this->render('event/view.html.twig', array(
                'sports' => $sports, 'sport_id' => $sport_id,
            ));
        }

        $query = "SELECT * FROM event WHERE sport_id=$sport_id";
        $statement = $connection->prepare($query);
        $statement->execute();
        $event_list = $statement->fetchAll();

        return $this->render('event/view.html.twig', array(
            'sports' => $sports, 'sport_id' => $sport_id, 'event_list' => $event_list,
        ));

    }

    /**
     * @Route("/event/view/single/{event_id}", defaults={"event_id"=0}, name="event_single")
     */
    public function viewSingleAction(Request $request, $event_id){

        if($event_id == 0){
            $this->redirectToRoute('event_view');
        }

        $connection = $this->getDoctrine()->getManager()->getConnection();

        $query1 = "SELECT * FROM event WHERE id=$event_id";

        $statement = $connection->prepare($query1);
        $statement->execute();
        $event = $statement->fetchAll();

        $query2 = "SELECT name, gender FROM sport WHERE id IN (SELECT sport_id FROM event WHERE id=$event_id)";

        $statement = $connection->prepare($query2);
        $statement->execute();
        $sport = $statement->fetchAll();

        $query3 = "SELECT student_id, remarks, first_name, second_name FROM event_member INNER JOIN student ON (event_member.student_id=student.id) WHERE event_id=$event_id";

        $statement = $connection->prepare($query3);
        $statement->execute();
        $students = $statement->fetchAll();

        return $this->render('event/single.html.twig', array(
            'event' => $event[0], 'sport' => $sport[0], 'students' => $students,
        ));
    }

    /**
     * @Route("/event/add/student/{event_id}", name="add_event_student")
     */
    public function addStudentEventAction(Request $request, $event_id){

        $connection = $this->getDoctrine()->getManager()->getConnection();

        if($request->getMethod() == 'POST'){

            $student_id = $request->request->get('student_id');
            $remarks = $request->request->get('remarks');

            $query = "INSERT INTO event_member (event_id, student_id, remarks) VALUES ($event_id, '$student_id','$remarks')";

            $statement = $connection->prepare($query);
            $statement->execute();

            return $this->redirectToRoute('event_single', ['event_id'=>$event_id]);
            echo "sddsd";
        }

        $query1 = "SELECT * FROM event WHERE id=$event_id";

        $statement = $connection->prepare($query1);
        $statement->execute();
        $event = $statement->fetchAll();

        return $this->render('event/mem.html.twig', array(
            'event' => $event[0],
        ));
    }
}

