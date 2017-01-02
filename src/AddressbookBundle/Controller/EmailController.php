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

            return $this->redirectToRoute('_edit', array('id' => $per->getId()));
        }

        return $this->render('AddressbookBundle:email:new.html.twig', array(
            'email' => $email,
            'pid' => $p_id,
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

        return $this->render('AddressbookBundle:email:show.html.twig', array(
            'email' => $email,
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
        $editForm = $this->createForm('AddressbookBundle\Form\EmailType', $email);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('_edit', array('id' => $email->getPersonId()->getId()));
        }

        return $this->render('AddressbookBundle:email:edit.html.twig', array(
            'email' => $email,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes an email entity.
     *
     * @Route("/{id}/delete", name="email_delete")
     * @Method("GET")
     */

    public function deleteAction($id)
    {

        $emailrep = $this->getDoctrine()->getRepository('AddressbookBundle:Email');
        $email = $emailrep->find($id);
        $whos = $email->getPersonId()->getId();

        $em = $this->getDoctrine()->getManager();
        $em->remove($email);
        $em->flush($email);

        return $this->redirectToRoute('_edit', array('id' => $whos));
    }

}
