<?php

namespace AppBundle\Controller\Equipment;

use AppBundle\Entity\EquipmentLend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\EquipmentCategory;
use AppBundle\Entity\EquipmentReserve;

class EquipmentController extends Controller
{
    /**
     * @Route("/equipment", name="equipment_homepage")
     */
    public function equipmentHomeAction(Request $request)
    {
        return $this->render('equipment/home.html.twig', array());
    }


    /**
     * @Route("/equipment/view/{sport_id}", defaults={"sport_id"=0}, name="equipment_view")
     */
    public function viewEquipmentAction(Request $request, $sport_id){

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

            return $this->render('equipment/view.html.twig', array(
                'sports' => $sports, 'sport_id' => $sport_id,
            ));
        }

        $query = "SELECT * FROM equipment_category WHERE sport_id=$sport_id";
        $statement = $connection->prepare($query);
        $statement->execute();
        $category_list = $statement->fetchAll();

        return $this->render('equipment/view.html.twig', array(
            'sports' => $sports, 'sport_id' => $sport_id, 'category_list' => $category_list,
        ));

    }

    /**
     * @Route("/equipment/view/single/{cat_id}", defaults={"cat_id"=0}, name="equipment_view_single")
     */
    public function viewSingleEquipmentAction(Request $request, $cat_id){

        if($cat_id == 0){
            return $this->redirectToRoute('equipment_view');
        }

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $query = "SELECT name, gender FROM sport WHERE id IN ";
        $query .= "(SELECT sport_id FROM equipment_category WHERE id=$cat_id)";

        $statement = $connection->prepare($query);
        $statement->execute();
        $sport = $statement->fetchAll();

        $query = "SELECT name, lend_time FROM equipment_category WHERE id=$cat_id ";

        $statement = $connection->prepare($query);
        $statement->execute();
        $category = $statement->fetchAll();

        $query = "SELECT * FROM equipment WHERE equipment_category_id=$cat_id";

        $statement = $connection->prepare($query);
        $statement->execute();
        $equip_list = $statement->fetchAll();

        $query4 = "SELECT equipment.id, reserved_date, student_id FROM equipment ";
        $query4 .= "LEFT OUTER JOIN equipment_reserve ON (equipment.id=equipment_reserve.equipment_id) ";
        $query4 .= "WHERE equipment.equipment_category_id=$cat_id AND equipment_reserve.state=1";

        $statement = $connection->prepare($query4);
        $statement->execute();
        $equip_res_det = $statement->fetchAll();

        $query5 = "SELECT student_id, equipment_id, lend_date, due_date FROM equipment_lend ";
        $query5 .= "LEFT JOIN equipment_reserve ON (equipment_reserve.id=equipment_lend.equipment_reserve_id) ";
        $query5 .= "WHERE equipment_lend.state=1 AND equipment_id IN ";
        $query5 .= "(SELECT id FROM equipment WHERE equipment_category_id=$cat_id)";

        $statement = $connection->prepare($query5);
        $statement->execute();
        $equip_lend_det = $statement->fetchAll();

        var_dump($equip_lend_det);

        return $this->render('equipment/single.html.twig', array(
            'sport' => $sport[0], 'category' => $category[0], 'equip_list' => $equip_list,
            'count' => sizeof($equip_list), 'equip_res_det' => $equip_res_det, 'equip_lend_det' => $equip_lend_det,
        ));
    }

    /**
     * @Route("/equipment/new", name="equipment_add")
     */
    public function addNewEquipmentAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $sports = $em->getRepository('AppBundle:Sport')->findAll();

        $category = new EquipmentCategory();

        $form_ss = $this->createFormBuilder($category)
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
            ->add('name', TextType::class )
            ->add('lendTime', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Add Category'])
            ->getForm();

        $form_ss->handleRequest($request);

        if($form_ss->isValid()){

            $em = $this->getDoctrine()->getEntityManager();
            $connection = $em->getConnection();

            $query = "INSERT INTO equipment_category ";
            $query .= "(lend_time, name, sport_id )";
            $query .= "VALUES ";
            $query .= "(" . $category->getLendTime() . ", '" . $category->getName() . "', " . $category->getSport()->getId() . ")";

            $statement = $connection->prepare($query);
            $statement->execute();

            $query = "SELECT MAX(id) AS cat_id FROM equipment_category";

            $statement = $connection->prepare($query);
            $statement->execute();
            $tmp = $statement->fetchAll();

            $cat_id = $tmp[0]['cat_id'];

            $category->setId($cat_id);

            return $this->render('equipment/new.html.twig', array(
                'equipment' => $category, 'sport' => $category->getSport(),
            ));
        }

        return $this->render('equipment/new.html.twig', array(
            'form' => $form_ss->createView(),
        ));

    }

    /**
     * @Route("/equipment/new/finish/{cat_id}", defaults={"cat_id"=0}, name="equipment_add_finish")
     */
    public function addNewFinishAction(Request $request, $cat_id){

        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

        $query = "INSERT INTO equipment (equipment_category_id, available, reserved ) VALUES ";
        $query .= "($cat_id, 1, 0)";

        $noe = $request->request->get('noe');

        for($i=0; $i<$noe; $i=$i+1){
            $statement = $connection->prepare($query);
            $statement->execute();
        }

        return $this->redirectToRoute('equipment_homepage');
    }

    /**
     * @Route("/equipment/reserve/{id}", defaults={"id"=0}, name="equipment_reserve")     *
     */
    public function reserveEquipmentAction(Request $request, $id){

        $this->denyAccessUnlessGranted('ROLE_STUDENT', null, 'Only student can reserve equipments!');

        if($id == 0){
            return $this->redirectToRoute('equipment_view');
        }

        $connection = $this->getDoctrine()->getManager()->getConnection();

        $user= $this->get('security.context')->getToken()->getUser();
        $equipment_id = $id;

        $query1 = "SELECT * FROM equipment_category WHERE id IN (SELECT equipment_category_id FROM equipment WHERE id=$id)";

        $statement = $connection->prepare($query1);
        $statement->execute();
        $category = $statement->fetchAll();

        $today = date("Y/m/d");

        $query2 = "INSERT INTO equipment_reserve (reserved_date, student_id, equipment_id, state) VALUES ";
        $query2 .= "('" . $today . "', '" . $user->getUsername() . "', " . $equipment_id . ", " . 1 . ")";

        $query3 = "UPDATE equipment SET reserved=1 WHERE id=$id";

        $statement = $connection->prepare($query2);
        $statement->execute();

        $statement = $connection->prepare($query3);
        $statement->execute();

        $msg = "A " . $category[0]['name'] . " reserved for student Id " . $user->getUsername();

        return $this->render('equipment/msg.html.twig', array(
            'msg' => $msg, 'path' => "",
        ));
    }

    /**
     * @Route("/equipment/lend/{id}", defaults={"id"=0}, name="equipment_lend")
     */
    public function lendEquipmentAction(Request $request, $id){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Only admin can give away equipments!');

        if($id == 0){
            return $this->redirectToRoute('equipment_view');
        }

        $connection = $this->getDoctrine()->getManager()->getConnection();

        $query1 = "SELECT * FROM equipment_category WHERE id IN (SELECT equipment_category_id FROM equipment WHERE id=$id)";

        $statement = $connection->prepare($query1);
        $statement->execute();
        $category = $statement->fetchAll();

        if(sizeof($category) == 0){
            // An unregistered equipment.
            // Error
        }

        $query2 = "SELECT * FROM equipment_reserve WHERE equipment_id=$id AND state=1";

        $statement = $connection->prepare($query2);
        $statement->execute();
        $reservation = $statement->fetchAll();

        $due_date = "2016/01/31";

        if(sizeof($reservation) == 0){

            return $this->render('equipment/lend.html.twig', array(
                'reserved_item' => false, 'category' => $category[0],
                'due_date' => $due_date, 'equip_id' => $id,
            ));
        }

        return $this->render('equipment/lend.html.twig', array(
            'reserved_item' => true, 'category' => $category[0], 'reserve' => $reservation[0],
            'due_date' => $due_date,
        ));
    }

    /**
     * @Route("/equipment/lend/finish/{id}", defaults={"id"=0}, name="equipment_lend_finish")
     */
    public function lendEquipmentFinishAction(Request $request, $id){

        $res_id = $request->request->get('reservation_id');
        $student_id = $request->request->get('student_id');
        $due_date = $request->request->get('due_date');

        $today = date("Y/m/d");

        $connection = $this->getDoctrine()->getManager()->getConnection();

        if($res_id == "0"){

            $query_reserve = "INSERT INTO equipment_reserve (reserved_date, student_id, equipment_id, state) VALUES ";
            $query_reserve .= "('" . $today . "', '" . $student_id . "', " . $id . ", " . 1 . ")";

            $statement = $connection->prepare($query_reserve);
            $statement->execute();

            $query_check = "SELECT * FROM equipment_reserve WHERE equipment_id=$id AND state=1";

            $statement = $connection->prepare($query_check);
            $statement->execute();
            $result = $statement->fetchAll();

            $res_id = $result[0]['id'];

        }

        $query_res_update = "UPDATE equipment_reserve SET state=2 WHERE id=$res_id";

        $statement = $connection->prepare($query_res_update);
        $statement->execute();

        $query_lend = "INSERT INTO equipment_lend (equipment_reserve_id, lend_date, due_date, state) ";
        $query_lend .= "VALUES ($res_id, '$today', '$due_date', 1)";

        $statement = $connection->prepare($query_lend);
        $statement->execute();

        $query_equ_update = "UPDATE equipment SET reserved=0, available=0 WHERE id=$id";

        $statement = $connection->prepare($query_equ_update);
        $statement->execute();

        $msg = "An equipment lend to student Id $student_id";

        return $this->render('equipment/msg.html.twig', array(
            'msg' => $msg, 'path' => "",
        ));
    }

    /**
     * @Route("equipment/return/{id}", defaults={"id"=0}, name="equipment_return")
     */
    public function equipmentReturnAction(Request $request){

    }
}
