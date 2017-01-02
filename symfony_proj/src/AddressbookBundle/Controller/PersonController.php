<?php

namespace AddressbookBundle\Controller;

use AddressbookBundle\Entity\Person;
use AddressbookBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Person controller.
 *
 * @Route("/")
 */
class PersonController extends Controller
{
    /**
     * Lists all person entities.
     *
     * @Route("/", name="_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $people = $em->getRepository('AddressbookBundle:Person')->findAll();

        return $this->render('AddressbookBundle:person:index.html.twig', array(
            'people' => $people,
        ));
    }

    /**
     * Creates a new person entity.
     *
     * @Route("/new", name="_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        $address = new Address();
        $form = $this->createForm('AddressbookBundle\Form\PersonType', $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush($person);

            return $this->redirectToRoute('_show', array('id' => $person->getId()));
        }

        return $this->render('AddressbookBundle:person:new.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a person entity.
     *
     * @Route("/{id}", name="_show")
     * @Method("GET")
     */
    public function showAction(Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);

        return $this->render('AddressbookBundle:person:show.html.twig', array(
            'person' => $person,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing person entity.
     *
     * @Route("/{id}/edit", name="_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);
        $editForm = $this->createForm('AddressbookBundle\Form\PersonType', $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('_edit', array('id' => $person->getId()));
        }

        return $this->render('AddressbookBundle:person:edit.html.twig', array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a person entity.
     *
     * @Route("/{id}", name="_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Person $person)
    {
        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($person);
            $em->flush($person);
        }

        return $this->redirectToRoute('_index');
    }

    /**
     * Creates a form to delete a person entity.
     *
     * @param Person $person The person entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('_delete', array('id' => $person->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
