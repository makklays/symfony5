<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Subscribe;
use App\Form\SubscribeType;

class MainController extends AbstractController
{
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    public function info(Request $request)
    {
        $locale = $request->getLocale();

        return $this->render('main/info.html.twig', [
            'locale' => $locale,
        ]);
    }

    public function map()
    {
        return $this->render('main/map.html.twig');
    }

    // mail 
    public function email(MailerInterface $mailer): Response 
    {
        $mail = (new Email())
            ->from('mail@mihospital.com')
            ->to(new Address('admin@mihospital.com', 'Admin'))
            ->addTo('sysadmin@mihospital.com')

            //->cc('mail.cc@mihospital.com')
            //->bcc('mail.bcc@mihospital.com')
            ->replyTo(Address::create('Alfred Potencial <alfreda@mihospital.com>'))
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')

            ->text('Sending emails is fun again!')
            ->html('<p><b>Mail</b><br/>This mail in HTML format from mihospital.com</p>');

            //->attach(fopen('/path/to/documents/contract.doc', 'r'))
            //->attachFromPath('/path/to/documents/privacy.pdf', 'Privacy Policy')
            //->attachFromPath('/path/to/documents/privacy2.doc', 'Privacy Policy2', 'application/msword') 

        try {
            $mailer->send($mail);
        } catch(TransportExceptionInterface $e) {
            echo 'Error! Message:'.$e->getMessage();
            exit;
        } 

        return new Response('<html><h1>Envió el corréo!</h1></html>');
    } 

    // template mail 
    public function email_template(MailerInterface $mailer): Response
    {
        $mail = (new TemplatedEmail())
            ->from('mail@mihospital.com')
            ->to(new Address('admin@mihospital.com'))
            ->subject('Thanks for signig up!')

            // path to the HTML twig template 
            ->htmlTemplate('emails/signup.html.twig')
            //->txtTemplate('emails/signup.txt.twig')

            // name => values 
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
            ]);
        
        try {
            $mailer->send($mail);

        } catch(TransportExceptionInterface $e) {
            echo 'Error! Message:'.$e->getMessage();
            exit;
        }
        return new Response('<html><h1>Envió el corréo con template!</h1></html>');
    }

    // 
    public function subscribe(Request $request)
    {
        $subscribe = new Subscribe();

        $form = $this->createForm(SubscribeType::class, $subscribe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $subscribe = $form->getData();

            // si no existe la suscripción 
            $subscribe_one = $this->getDoctrine()->getRepository(Subscribe::class)->findOneBy(['email' => $subscribe->getEmail()]);
            if (is_null($subscribe_one)) {
                $subscribe->setIsActive(1);
                $subscribe->setCreatedAt(new \DateTime('now'));

                // flash
                $this->addFlash('notice', 'Tu se suscribió en nuestras noticias');

                $em = $this->getDoctrine()->getManager();
                $em->persist($subscribe);
                $em->flush(); 
            } else {
                // flash 
                $this->addFlash('notice', 'Tu ya se suscribías en nuestras noticias');
            }
        }

        return $this->render('main/subscribe.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}