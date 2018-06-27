<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function index()
    {

        $repository = $this->getDoctrine()
                           ->getRepository(Transfer::class);

        $transfers = $repository->findAll();

        return $this->render('page/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /* public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT * FROM transfer';
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $result = $statement->fetchAll();
        

        return $this->render('page/index.html.twig', [
            '' => 'HomeController',
        ]);
    } */

}