<?php

namespace AddressbookBundle\Controller;

use AddressbookBundle\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Phone controller.
 *
 * @Route("phone")
 */
class PhoneController extends Controller
{
    /**
     * Lists all phone entities.
     *
     * @Route("/", name="phone_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $phones = $em->getRepository('AddressbookBundle:Phone')->findAll();

        return $this->render('phone/index.html.twig', array(
            'phones' => $phones,
        ));
    }

    /**
     * Creates a new phone entity.
     *
     * @Route("/new/{p_id}", name="phone_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $p_id)
    {
        $personrep = $this->getDoctrine()->getRepository('AddressbookBundle:Person');
        $per = $personrep->find($p_id);

        $phone = new Phone();
        $form = $this->createForm('AddressbookBundle\Form\PhoneType', $phone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $phone->setPersonId($per);
            $em->persist($phone);
            $em->flush($phone);

            return $this->redirectToRoute('_edit', array('id' => $per->getId()));
        }

        return $this->render('AddressbookBundle:phone:new.html.twig', array(
            'phone' => $phone,
            'pid' => $p_id,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a phone entity.
     *
     * @Route("/{id}", name="phone_show")
     * @Method("GET")
     */
    public function showAction(Phone $phone)
    {
        $deleteForm = $this->createDeleteForm($phone);

        return $this->render('phone/show.html.twig', array(
            'phone' => $phone,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing phone entity.
     *
     * @Route("/{id}/edit", name="phone_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Phone $phone)
    {
        $editForm = $this->createForm('AddressbookBundle\Form\PhoneType', $phone);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('_edit', array('id' => $phone->getPersonId()->getId()));
        }

        return $this->render('AddressbookBundle:phone:edit.html.twig', array(
            'phone' => $phone,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a phone entity.
     *
     * @Route("/{id}/delete", name="phone_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $phonerep = $this->getDoctrine()->getRepository('AddressbookBundle:Phone');
        $phone = $phonerep->find($id);
        $whos = $phone->getPersonId()->getId();

        $em = $this->getDoctrine()->getManager();
        $em->remove($phone);
        $em->flush($phone);

        return $this->redirectToRoute('_edit', array('id' => $whos));
    }
}
