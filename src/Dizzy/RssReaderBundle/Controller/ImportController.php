<?php

namespace Dizzy\RssReaderBundle\Controller;

use Dizzy\RssReaderBundle\Form\ImportType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends Controller
{
    /**
     * @Route("/import/opml/",name="import_opml")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function opmlImportAction()
    {
        $form = $this->createCreateForm();

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Feed entity.
     *
     * @Route("/import/opml/", name="import_opml_post")
     * @Method("POST")
     * @Template("DizzyRssReaderBundle:Import:opmlImport.html.twig")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $form = $this->createCreateForm();
        $form->handleRequest($request);

        $data = $form->getData();

        if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
            $import        = $this->get('rss.import.opml');
            $newFeedsCount = $import->importFile($this->getUser(), $data['file']);
            $form->addError(new FormError("Импортировано {$newFeedsCount} лент"));
        } else {
            $form->addError(new FormError('Необходимо загрузить файл'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm()
    {
        $form = $this->createForm(
            new ImportType(),
            null,
            array(
                'action' => $this->generateUrl('import_opml_post'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Добавить'));

        return $form;
    }
}
