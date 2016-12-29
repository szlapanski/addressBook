<?php

namespace AddressbookBundle\Controller;

use AddressbookBundle\Entity\Email;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Email controller.
 *
 * @Route("email")
 */
class EmailController extends Controller
{
    /**
     * Lists all email entities.
     *
     * @Route("/", name="email_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $emails = $em->getRepository('AddressbookBundle:Email')->findAll();

        return $this->render('AddressbookBundle:email:index.html.twig', array(
            'emails' => $emails,
        ));
    }

    /**
     * Creates a new email entity.
     *
     * @Route("/new/{p_id}", name="email_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $p_id)
    {
        $personrep = $this->getDoctrine()->getRepository('AddressbookBundle:Person');
        $per = $personrep->find($p_id);

        $email = new Email();
        $form = $this->createForm('AddressbookBundle\Form\EmailType', $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $email->setPersonId($per);
            $em->persist($email);
            $em->flush($email);

            return $this->redirectToRoute('email_show', array('id' => $email->getId()));
        }

        return $this->render('AddressbookBundle:email:new.html.twig', array(
            'email' => $email,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a email entity.
     *
     * @Route("/{id}", name="email_show")
     * @Method("GET")
     */
    public function showAction(Email $email)
    {
        $deleteForm = $this->createDeleteForm($email);

        return $this->render('AddressbookBundle:email:show.html.twig', array(
            'email' => $email,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing email entity.
     *
     * @Route("/{id}/edit", name="email_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Email $email)
    {
        $deleteForm = $this->createDeleteForm($email);
        $editForm = $this->createForm('AddressbookBundle\Form\EmailType', $email);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('email_edit', array('id' => $email->getId()));
        }

        return $this->render('AddressbookBundle:email:edit.html.twig', array(
            'email' => $email,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a email entity.
     *
     * @Route("/{id}", name="email_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Email $email)
    {
        $form = $this->createDeleteForm($email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($email);
            $em->flush($email);
        }

        return $this->redirectToRoute('email_index');
    }

    /**
     * Creates a form to delete a email entity.
     *
     * @param Email $email The email entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Email $email)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('email_delete', array('id' => $email->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
