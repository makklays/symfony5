<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrType;

class AuthController extends AbstractController
{
    public function login(Request $request) 
    {
        $user = new User;

        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $this->getDoctrine()->getRepository(User::class)->findBy(['login' => $data->login, 'password' => $data->password]);
            if ($user) {
                $session = new Session();
                $session->start();

                $session->set('user', $user);

                return $this->redirectToRoute('dashboard_index');
            } else {
                $this->addFlash(
                    'error',
                    'No tiene Login o Password!'
                );
            }
        }

        return $this->render('auth/login.html.twig', [
            'form' => $form,
        ]);
    }

    public function registr(Request $request)
    {
        $user = new User;

        $form = $this->createForm(RegistrType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $session = new Session();
            $session->start();
            $session->set('user', $user);

            return $this->redirectToRoute('dashboard_index');
        }

        return $this->render('auth/registr.html.twig', [
            'form' => $form,
        ]);
    }
}

