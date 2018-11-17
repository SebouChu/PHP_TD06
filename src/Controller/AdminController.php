<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Pizza;
use App\Form\PizzaType;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/pizzas", name="pizzas")
     */
    public function pizzasAction()
    {
        $em = $this->get('doctrine')->getManager();

        return $this->render('admin/pizzas/index.html.twig', [
            'pizzas' => $em->getRepository(Pizza::class)->findAll()
        ]);
    }

    /**
     * @Route("/pizzas/new", name="new_pizza")
     */
    public function newPizzaAction(Request $request)
    {
        $pizza = new Pizza;

        $form = $this->createForm(PizzaType::class, $pizza);
        $form->add('save', SubmitType::class, array('label' => 'Create Pizza'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $pizza = $form->getData();

          $em = $this->get('doctrine')->getManager();
          $em->persist($pizza);
          $em->flush();

          return $this->redirectToRoute('admin_pizzas');
        }

        return $this->render('admin/pizzas/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
