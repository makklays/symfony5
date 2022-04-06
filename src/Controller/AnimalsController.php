<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Animals;
use App\Form\AnimalsType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnimalsController extends AbstractController
{
    public function index(): Response
    {
        $animals = $this->getDoctrine()
                        ->getRepository(Animals::class)
                        ->findAll();

        return $this->render('animals/index.html.twig', [
            'animals' => $animals,
        ]);
    }

    public function show(Request $request, $id) 
    {
        $animal = $this->getDoctrine()
                    ->getRepository(Animals::class)
                    ->find($id);

        return $this->render('animals/show.html.twig', [
            'animal' => $animal,
        ]);                  
    } 

    public function add(Request $request, SluggerInterface $slugger)
    {
        /*$animal = new Animals();
        $animal->setName('Gato');
        $animal->setDescription('Gato rubio');
        $animal->setTypeId(1);
        $animal->setImg(null);
        $animal->setIsActive(true);*/

        $animal = new Animals();
        
        $form = $this->createForm(AnimalsType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animal = $form->getData();

            $img = $form->get('img')->getData();
            if ($img) {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$img->guessExtension();
                
                // save file to store
                try {
                    $img->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                    $animal->setImg($newFilename);

                } catch (FileException $e) {
                    //
                    echo 'Exception! Message: '.$e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine();
                    exit;
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($animal);
            $em->flush();

            return $this->redirectToRoute('animals_index');
        }

        /*$form = $this->createFormBuilder(Animals::class)
                    ->add('name', TextType::class)
                    ->add('type_id', SelectType::class)
                    ->add('description', TextType::class)
                    ->add('add', SubmitType::class, ['label' => 'Add animal']);*/

        return $this->render('animals/add.html.twig', [
            'form_animale' => $form->createView(),
        ]);
    }

    public function edit(Request $request, $id, SluggerInterface $slugger)
    {
        $animal = $this->getDoctrine()->getRepository(Animals::class)->find($id);

        $form = $this->createForm(AnimalsType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animal = $form->getData();

            $img = $form->get('img')->getData();
            if ($img) {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$img->guessExtension();

                // save file to store
                try {
                    $img->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                    $animal->setImg($newFilename);

                } catch (FileException $e) {
                    //
                    echo 'Exception! Message: '.$e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine();
                    exit;
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($animal);
            $em->flush();

            return $this->redirectToRoute('animals_index');
        }

        return $this->render('animals/edit.html.twig', [
            'id' => $id,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, $id)
    {
        $animal = $this->getDoctrine()->getRepository(Animals::class)->find($id);

        if (is_object($animal)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($animal);
            $em->flush();
        }

        return $this->redirectToRoute('animals_index');
    }
}

