<?php

namespace AppBundle\Controller\Equipment;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\EquipmentCategory;

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

        $em = $this->getDoctrine()->getEntityManager();
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

        $em = $this->getDoctrine()->getEntityManager();
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

        return $this->render('equipment/single.html.twig', array(
            'sport' => $sport, 'category' => $category[0],
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
}
