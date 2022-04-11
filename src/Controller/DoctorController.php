<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Doctor;
use App\Form\DoctorType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;

class DoctorController extends AbstractController
{
    public function index(LoggerInterface $logger)
    {
        $doctors = $this->getDoctrine()->getRepository(Doctor::class)->findAll();

        $logger->info('Show log: Lista de doctors ');

        return $this->render('doctor/index.html.twig', [
            'doctors' => $doctors,
        ]);
    }

    public function show(Request $request, $id)
    {
        $doctor = $this->getDoctrine()->getRepository(Doctor::class)->find($id);
        if (!$doctor) {
            throw $this->createNotFoundException('The doctor does not exist');
        }

        return $this->render('doctor/show.html.twig', [
            'doctor' => $doctor,
        ]);
    }

    public function add(Request $request, SluggerInterface $slugger)
    {
        $doctor = new Doctor;

        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctor = $form->getData();

            $fotoFile = $form->get('foto')->getData();
            if ($fotoFile) {
                $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fotoFile->guessExtension();
                
                try {
                    $fotoFile->move(
                        $this->getParameter('foto_doctor_directory'),
                        $newFilename
                    );
                    $doctor->setFoto($newFilename);
                } catch (FileException $e) {
                    echo 'Exception! Message: '.$e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine();
                    exit;
                }
            }

            $doctor->setCreatedAt(new \DateTime('now'));
            $doctor->setModifiedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($doctor);
            $em->flush();

            return $this->redirectToRoute('doctor_index');
        }

        return $this->render('doctor/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request, $id, SluggerInterface $slugger)
    {
        $doctor = $this->getDoctrine()->getRepository(Doctor::class)->find($id);
        if (!$doctor) {
            throw $this->createNotFoundException('The doctor does not exist');
        }

        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $doctor = $form->getData();

            $fotoFile = $form->get('foto')->getData();
            if ($fotoFile) {
                $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fotoFile->guessExtension();
                
                try {
                    $fotoFile->move(
                        $this->getParameter('foto_doctor_directory'),
                        $newFilename
                    );
                    $doctor->setFoto($newFilename);
                } catch (FileException $e) {
                    echo 'Exception! Message: '.$e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine();
                    exit;
                }
            }

            $doctor->setModifiedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($doctor);
            $em->flush();
        }

        return $this->render('doctor/edit.html.twig', [
            'form' => $form->createView(),
            'doctor' => $doctor,
        ]);
    }

    public function lista(Request $request, $id)
    {
        $doctor = $this->getDoctrine()->getRepository(Doctor::class)->find($id);
        if (!$doctor) {
            throw $this->createNotFoundException('The doctor does not exist');
        }
        
        return $this->render('doctor/lista.html.twig', [
            'doctor' => $doctor,
        ]);
    }
}
