<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Paciente;
use App\Form\PacienteType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;
use App\Repository\PacienteRepository;

class PacienteController extends AbstractController
{
    public function index(PacienteRepository $repository, LoggerInterface $logger): Response
    {
        $pacientes = $this->getDoctrine()
                        ->getRepository(Paciente::class)
                        ->findAll();

        // para la p'agina de busqueta
        //$pacientes = $repository->findAllByNie('11');
        //$pacientes = $repository->findAllByFullname('nim');

        // log
        $logger->info('Log!......');

        return $this->render('paciente/index.html.twig', [
            'pacientes' => $pacientes,
        ]);
    }

    public function add(Request $request, SluggerInterface $slugger)
    {
        $paciente = new Paciente();

        $form = $this->createForm(PacienteType::class, $paciente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $paciente = $form->getData();

            $fotoFile = $form->get('foto')->getData();
            if ($fotoFile) {
                $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fotoFile->guessExtension();
                
                try {
                    $fotoFile->move(
                        $this->getParameter('foto_pacient_directory'),
                        $newFilename
                    );
                    $paciente->setFoto($newFilename);
                } catch (FileException $e) {
                    echo 'Exception! Message: '.$e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine();
                    exit;
                }
            }

            $paciente->setCreatedAt(new \DateTime('now'));
            $paciente->setModifiedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($paciente);
            $em->flush();

            $this->addFlash(
                'notice',
                'Tu las cambias guardó!',
            );

            return $this->redirectToRoute('paciente_index');
        }

        return $this->render('paciente/add.html.twig', [
            'form' => $form->createView(),
        ]);
    } 

    public function edit(Request $request, $id, SluggerInterface $slugger)
    {
        $paciente = $this->getDoctrine()->getRepository(Paciente::class)->find($id);
        if (!$paciente) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $form = $this->createForm(PacienteType::class, $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paciente = $form->getData();

            $fotoFile = $form->get('foto')->getData();
            if ($fotoFile) {
                $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fotoFile->guessExtension();
                
                try {
                    $fotoFile->move(
                        $this->getParameter('foto_pacient_directory'),
                        $newFilename
                    );
                    $paciente->setFoto($newFilename);
                } catch (FileException $e) {
                    echo 'Exception! Message: '.$e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine();
                    exit;
                }
            }

            $paciente->setModifiedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($paciente);
            $em->flush();

            $this->addFlash(
                'notice',
                'Tu las cambias editó y guardó'
            );

            //return $this->redirectToRoute('paciente_index');
        }

        return $this->render('paciente/edit.html.twig', [
            'form' => $form->createView(),
            'paciente' => $paciente,
        ]);
    }

    public function show(Request $request, $id, LoggerInterface $logger)
    {
        $paciente = $this->getDoctrine()
                        ->getRepository(Paciente::class)
                        ->find($id);

        // edad 
        $startDate = $paciente->getBirthday();
        $nowDate = date_create();
        $edad = date_diff($startDate, $nowDate);                

        $logger->info('Paciente ID = ' . $paciente->getId() );

        return $this->render('paciente/show.html.twig', [
            'paciente' => $paciente,
            'edad'     => $edad,
        ]);
    }
}
