<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class LuckyController
{
    public function randomNumber() : Response
    {
        $num = random_int(0, 350);

        return new Response('<html><body>Fatima\'s lucky number:' . $num . '</body></html>');
    }
}


