<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class BlogController extends AbstractController
{
    public function index(): Response 
    {
        $data = [
            'data' => 'List of products',
            'products' => [
                [
                    'id' => 8,
                    'title' => 'Product 8',
                    'description' => 'Description product 8',
                ],
                [
                    'id' => 11,
                    'title' => 'Product 11',
                    'description' => 'Description product 11',
                ],
                [
                    'id' => 12,
                    'title' => 'Product 12',
                    'description' => 'Description product 12 text ',
                ],
            ],
        ];

        return $this->json($data);
    }

    public function show(int $id): Response 
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        // error 
        if (!$product) {
            return $this->json(['status' => 'error', 'error' => 'No product found for id '.$id]);
        }

        $data = [
            'data' => 'One product',
            'product' => $product,
        ];

        return $this->json($data, 200);
    }

    public function edit(): Response 
    {
        return $this->json();
    }
}