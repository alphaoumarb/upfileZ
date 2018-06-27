<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Transfer;

class TransferController extends Controller
{
    public function index()
    {
        $transfer = $this->getDoctrine()
            ->getRepository(Transfer::class)
            ->findAll();
        
        return $this->render('page/index.html.twig', array(
            'transfers' => $transfer
        )) ;
    }
}
