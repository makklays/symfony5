<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\Query;

class DoctorController extends AbstractController
{
    public function index(DoctorRepository $repository, Request $request, LoggerInterface $logger)
    {   
        //$doctors = $this->getDoctrine()->getRepository(Doctor::class)->findAll();

        if (!empty($request->get('page'))) {
            $page = $request->get('page');
        } else {
            $page = 1;
        }

        $onPage = 10;
        $doctors = $repository->findAllByPage($page, $onPage); // limit
        $countDoctors = $repository->findAllCount(); // all count
        $pagesCount = ceil($countDoctors / $onPage);
        
        /*$doctors = $this->getDoctrine()->getRepository(Doctor::class);
        $query = $doctors->createQueryBuilder('d')
                    //->where('d.is_active = 1')    
                    ->orderBy('d.lastname', 'ASC')
                    ->orderBy('d.firstname', 'ASC')
                    ->getQuery();

        $pageSize = 1;
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $pageSize);
        $doctors = $paginator->getQuery()
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize)
            ->getResult();
        */    

        //$doctors = $repository->findAllActivesss();
        //$doctors = $this->getDoctrine()->getRepository(Doctor::class)->findAllActives();

        $logger->info('Show log: Lista de doctors ');

        return $this->render('doctor/index.html.twig', [
            'doctors' => $doctors,
            'total_page' => $pagesCount,
            'page' => $page, 
        ]);
    }

    public function show(Request $request, $id)
    {
        $doctor = $this->getDoctrine()->getRepository(Doctor::class)->find($id);
        if (!$doctor) {
            throw $this->createNotFoundException('The doctor does not exist');
        }

        $startDate = $doctor->getBirthday();
        $nowDate = date_create();
        $edad = date_diff($startDate, $nowDate);

        return $this->render('doctor/show.html.twig', [
            'doctor' => $doctor,
            'edad' => $edad,
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
