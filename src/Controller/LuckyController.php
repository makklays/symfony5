<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LuckyController extends AbstractController
{
    public function index(): Response 
    {
        return $this->render('lucky/index.html.twig');
    }

    public function randomNumber() : Response
    {
        $num = random_int(0, 350);

        //return new Response('<html><body>Fatima\'s lucky number:' . $num . '</body></html>');

        return $this->render('lucky/number.html.twig', [
            'page_title' => 'Lucky number',
            'num' => $num,
        ]);
    }

    public function randomFrase(): Response
    {
        $frase = random_int(111, 999);

        return $this->render('lucky/frase.html.twig', [
            'page_title' => 'Lucky prase',
            'frase' => $frase,
        ]);
    }
}


