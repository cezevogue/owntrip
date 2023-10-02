<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripePayController extends AbstractController
{
    #[Route('/stripe/pay', name: 'app_stripe_pay')]
    public function index(CartService $cs): Response
    {

        $fullCart=$cs->getCartWithData();

        $line_items=[];


         foreach ($fullCart as $item)
          {
            $line_items[]=[
                'price_data'=>[
                    'unit_amount'=>$item['activity']->getPrice(), 'currency'=>'EUR',
                    'product_data'=>['name'=>$item['activity']->getName()
                    ]
                ],
                   'quantity'=>$item['quantity']


            ];
          }










        return $this->render('stripe_pay/index.html.twig', [
            'controller_name' => 'StripePayController',
        ]);
    }
}
