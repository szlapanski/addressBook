<?php

namespace AddressbookBundle\Controller;

use AddressbookBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Address controller.
 *
 * @Route("address")
 */
class AddressController extends Controller
{

    /**
     * Creates a new address entity.
     *
     * @Route("/new/{p_id}", name="address_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $p_id)
    {
        $personrep = $this->getDoctrine()->getRepository('AddressbookBundle:Person');
        $per = $personrep->find($p_id);

        $address = new Address();
        $form = $this->createForm('AddressbookBundle\Form\AddressType', $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $address->setPersonId($per);
            $em->persist($address);
            $em->flush($address);

            return $this->redirectToRoute('_edit', array('id' => $per->getId()));
        }

        return $this->render('AddressbookBundle:address:new.html.twig', array(
            'address' => $address,
            'pid' => $p_id,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a address entity.
     *
     * @Route("/{id}", name="address_show")
     * @Method("GET")
     */
    public function showAction(Address $address)
    {
        $deleteForm = $this->createDeleteForm($address);

        return $this->render('AddressbookBundle:address:show.html.twig', array(
            'address' => $address,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing address entity.
     *
     * @Route("/{id}/edit", name="address_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Address $address)
    {
        $deleteForm = $this->createDeleteForm($address);
        $editForm = $this->createForm('AddressbookBundle\Form\AddressType', $address);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('_edit', array('id' => $address->getPersonId()->getId()));
        }

        return $this->render('AddressbookBundle:address:edit.html.twig', array(
            'address' => $address,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a address entity.
     *
     * @Route("/{id}", name="address_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Address $address)
    {
        $form = $this->createDeleteForm($address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush($address);
        }

        return $this->redirectToRoute('address_index');
    }

    /**
     * Creates a form to delete a address entity.
     *
     * @param Address $address The address entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Address $address)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('address_delete', array('id' => $address->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
