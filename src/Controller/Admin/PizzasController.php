<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Ingredient;
use App\Entity\Pizza;
use App\Form\PizzaType;

/**
 * @Route("/admin/pizzas", name="admin_")
 */
class PizzasController extends Controller
{
    /**
     * @Route("/", name="pizzas")
     */
    public function index()
    {
      $em = $this->get('doctrine')->getManager();

      return $this->render('admin/pizzas/index.html.twig', [
          'pizzas' => $em->getRepository(Pizza::class)->findAll()
      ]);
    }

    /**
     * @Route("/{id}", name="pizza")
     */
    public function show(Pizza $pizza)
    {
      return $this->render('admin/pizzas/show.html.twig', [
          'pizza' => $pizza,
      ]);
    }

    /**
     * @Route("/new", name="new_pizza")
     */
    public function new(Request $request)
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

    /**
     * @Route("/{id}/edit", name="edit_pizza")
     */
    public function edit(Request $request, Pizza $pizza)
    {
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->add('save', SubmitType::class, array('label' => 'Update Pizza'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          $this->get('doctrine')->getManager()->flush();

          return $this->redirectToRoute('admin_pizza', [
            'id' => $pizza->getId()
          ]);
        }

        return $this->render('admin/pizzas/edit.html.twig', [
            'form' => $form->createView(),
            'pizza' => $pizza,
        ]);
    }
}