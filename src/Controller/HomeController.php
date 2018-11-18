<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Ingredient;
use App\Entity\Pizza;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/pizzas", name="pizzas_list")
     */
    public function pizzasAction()
    {
        $em = $this->get('doctrine')->getManager();

        return $this->render('home/pizzas.html.twig', [
            'pizzas' => $em->getRepository(Pizza::class)->findAll()
        ]);
    }

    /**
     * @Route("/pizzas/insert", name="insert_pizza")
     */
    public function insertPizzaAction()
    {
      $em = $this->get('doctrine')->getManager();

      $mozarella = new Ingredient;
      $mozarella->setName("Mozarella");
      $parmesan = new Ingredient;
      $parmesan->setName("Parmesan");

      $quatreFromages = new Pizza;
      $quatreFromages
        ->setName('4 fromages')
        ->setPrice(32.2)
        ->setDescription('Pour les fans de fromage')
        ;
      $quatreFromages->addIngredient($mozarella);
      $quatreFromages->addIngredient($parmesan);

      $em->persist($quatreFromages);
      $em->persist($mozarella);
      $em->persist($parmesan);

      $em->flush();

      return new Response("Pizzas créées");

    }
}
