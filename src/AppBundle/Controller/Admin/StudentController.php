<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class StudentController extends Controller
{
    /**
     * @Route("/student/new", name="new_student")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return new Response("This can only be accessed by the admin");
    }

    /**
     * @Route("/student/all", name="all_student")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return new Response("This can only be accessed by the admin");
    }

    /**
     * @Route("/student/new", name="new_student")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return new Response("This can only be accessed by the admin");
    }
}
