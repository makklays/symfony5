<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Animals;
use App\Form\AnimalsType;

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

    public function add(Request $request)
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

    public function edit(Request $request, $id)
    {
        return $this->render('animals/edit.html.twig', [
            'id' => $id,
        ]);
    }

    public function delete(Request $request, $id)
    {
        return $this->redirect()->path('animals_index');
    }
}

