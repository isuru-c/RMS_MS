<?php

namespace AppBundle\Controller\Student;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
}
