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

        return $this->render('AddressbookBundle:person:show.html.twig', array(
            'person' => $person,
            'editable' => false
        ));
    }

    /**
     * Displays a form to edit an existing person entity.
     *
     * @Route("/{id}/modify", name="_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Person $person)
    {
        $editForm = $this->createForm('AddressbookBundle\Form\PersonType', $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('_edit', array('id' => $person->getId()));
        }

        return $this->render('AddressbookBundle:person:edit.html.twig', array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
            'editable' => true
        ));
    }

    /**
     * Deletes a person entity.
     *
     * @Route("/{id}/delete", name="_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $personrep = $this->getDoctrine()->getRepository('AddressbookBundle:Person');
        $per = $personrep->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($per);
        $em->flush($per);

        return $this->redirectToRoute('_index');
    }
}
