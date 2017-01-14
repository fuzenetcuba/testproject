<?php

namespace BackendBundle\Controller;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Candidate;
use BackendBundle\Form\CandidateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Candidate controller.
 *
 */
class CandidateController extends Controller
{
    /**
     * Lists all Candidate entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Candidate e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), 5
        );

        return $this->render('candidate/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Candidate entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Candidate e WHERE "
                . "e.id LIKE '%" . $find . "%' OR "
                . "e.id LIKE '%" . $find . "%' "
                . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), 5
            );

            return $this->render('candidate/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('candidate'));
        }
    }

    /**
     * Displays a form to create a new Candidate entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Candidate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The candidate was created succesfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('candidate_new'));
            } else {
                return $this->redirectToRoute('candidate_show', array('id' => $entity->getId()));
            }

        }

        return $this->render('candidate/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Candidate entity.
     *
     * @param Candidate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Candidate $entity)
    {
        $form = $this->createForm('BackendBundle\Form\CandidateType', $entity, array(
            'action' => $this->generateUrl('candidate_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Candidate entity.
     *
     */
    public function showAction(Candidate $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        $html = $this->render('candidate/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));


        $pdfGenerator = $this->get('spraed.pdf.generator');
        $this->createCandidatePDFReport($entity, $pdfGenerator->generatePDF($html));

        return $html;
    }

    /**
     * Generate a PDF report for a Candidate entity.
     *
     */
    public function exportToPDFAction(Candidate $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        $html = $this->render('candidate/pdf.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));

        return $html;

//        $pdfGenerator = $this->get('spraed.pdf.generator');
//
//        return new Response($pdfGenerator->generatePDF($html),
//            200,
//            array(
//                'Content-Type' => 'application/pdf',
//                'Content-Disposition' => 'inline; filename="out.pdf"'
//            )
//        );
    }

    /**
     * Generate a PDF report for a Candidate entity.
     *
     */
    public function pdfReportAction(Candidate $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        $html = $this->render('candidate/pdf.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));


        $pdfGenerator = $this->get('spraed.pdf.generator');
        $this->createCandidatePDFReport($entity, $pdfGenerator->generatePDF($html));

        return $this->redirectToRoute('candidate_show', array('id' => $entity->getId()));
    }

    /**
     * Displays a form to edit an existing Candidate entity.
     *
     */
    public function editAction(Request $request, Candidate $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The candidate was updated succesfully.');
            return $this->redirectToRoute('candidate_show', array('id' => $entity->getId()));
        }

        return $this->render('candidate/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Candidate entity.
     *
     * @param Candidate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Candidate $entity)
    {
        $form = $this->createForm('BackendBundle\Form\CandidateType', $entity, array(
            'action' => $this->generateUrl('candidate_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Candidate entity.
     *
     */
    public function deleteAction(Request $request, Candidate $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Candidate entity.');
        }

        // removing files
        if (!$this->removeFilesOfCandidate($entity)) {
            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('warning', 'The CV and/or Cover Letter files of the candidate do not exist or was not removed because an error.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The candidate was deleted succesfully.');

        return $this->redirect($this->generateUrl('candidate'));


    }

    private function removeFilesOfCandidate(Candidate $entity)
    {
        // deleting files in filesystem
        $fs = new Filesystem();

        $removed = false;

        try {
            $absolutePath = realpath($this->getParameter('careers.pdf.store'));
            $CVFile = $absolutePath . '/' . $entity->getCv();
            $coverFile = $absolutePath . '/' . $entity->getCoverLetter();

            if ($fs->exists($CVFile)) {
                $fs->remove($CVFile);
                $removed = true;
            } else {
                $removed = false;
            }

            if ($fs->exists($coverFile)) {
                $fs->remove($coverFile);
                $removed = true;
            } else {
                $removed = false;
            }
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while removing files at " . $e->getPath();
        }

//        var_export("CV: " . $CVFile . "<br />" . "Cover: " . $coverFile);
//        die;

        return $removed;
    }

    private function createCandidatePDFReport(Candidate $entity, $fileContent)
    {
        // deleting files in filesystem
        $fs = new Filesystem();

        try {
            $absolutePath = realpath($this->getParameter('system.pdf.store.report'));
            $pdfFile = $absolutePath . '/' . trim($entity->getSocialNumber() . "_" . $entity->getOpening()->getId()) . ".pdf";

            $fs->dumpFile($pdfFile, $fileContent);
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating files at " . $e->getPath();
        }
    }

    /**
     * Creates a form to delete a Candidate entity.
     *
     * @param Candidate $entity The Candidate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Candidate $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('candidate_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Candidate entities.
     *
     */
    public function batchAction(Request $request)
    {
        $action = $request->get('batch_action_do');
        $ids = $request->get('batch_action_checkbox');
        $recordsSelected = false;
        $errorRemoving = false;

        if ($ids) {
            $em = $this->getDoctrine()->getManager();

            if ($action == "delete") {
                foreach ($ids as $id) {
                    $entity = $em->getRepository('BackendBundle:Candidate')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Candidate entity.');
                    } else {

                        // removing files
                        if (!$this->removeFilesOfCandidate($entity)) {
                            $errorRemoving = true;
                        }

                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Candidates deleted succesfully.');
                }
                if ($errorRemoving) {
                    $this->get('session')->getFlashBag()->add('warning', 'One or more files of candidates do not exist or was not removed because an error.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('candidate'));
    }
}
