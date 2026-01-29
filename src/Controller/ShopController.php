<?php
namespace App\Controller;

use App\Model\ProductManager;

class ShopController extends AbstractController
{
    public function index(): void
    {
        $productManager = new ProductManager();
        $products = $productManager->findAll();

        $this->render('shop/index', [
            'products' => $products
        ]);
    }
}