<?php

namespace Dizzy\RssReaderBundle\Controller;

use Dizzy\RssReaderBundle\Entity\Feed;
use Dizzy\RssReaderBundle\Entity\User;
use Dizzy\RssReaderBundle\Form\FeedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FeedController extends Controller
{

    /**
     * Displays a form to create a new Feed entity.
     *
     * @Route("/feed/new/", name="feed_new")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction()
    {
        $entity = new Feed();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }


    /**
     * Creates a new Feed entity.
     *
     * @Route("/feed/new/", name="feed_create")
     * @Method("POST")
     * @Template("DizzyRssReaderBundle:Feed:new.html.twig")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        /** @var $user User */
        $user = $this->getUser();
        $feed = new Feed();
        $form = $this->createCreateForm($feed);
        $form->handleRequest($request);

        $url = $form->get('url')->getData();
        /** @var $feed Feed */
        $oldFeed = $entityManager->getRepository('DizzyRssReaderBundle:Feed')->findOneBy(['url' => $url]);

        if ($oldFeed) {
            $feed = $oldFeed;
        }

        if ($oldFeed) {
            if (in_array($user, $feed->getUsers()->toArray())) {
                $form->addError(new FormError('Вы уже подписаны на этот RSS'));
                return array(
                    'entity' => $feed,
                    'form'   => $form->createView(),
                );
            } else {
                $user->addFeed($feed);
                $entityManager->flush();
                return $this->redirect($this->generateUrl('readFeed', array('feed' => $feed->getId())));
            }

        } else {
            $simplePie = new \SimplePie();
            $simplePie->enable_cache(false);
            $simplePie->set_feed_url($url);
            $simplePie->init();

            if ($title = $simplePie->get_title()) {
                $feed->setTitle($simplePie->get_title());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($feed);
                $user->addFeed($feed);
                $feed->addUser($user);
                $entityManager->flush();

                $fetcher = $this->get('rss.fetch');
                $fetcher->fetchFeed($feed);

                return $this->redirect($this->generateUrl('readFeed', array('feed' => $feed->getId())));
            } else {
                $form->addError(new FormError('Не удалось получить данные, проверьте ссылку'));
                return array(
                    'entity' => $feed,
                    'form'   => $form->createView(),
                );
            }
        }
    }

    /**
     * @Route("/feed/edit/", name="feed_edit")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     *
     * @return array
     */
    public function editAction(){
        $entityManager = $this->getDoctrine()->getManager();
        $feeds = $entityManager->getRepository('DizzyRssReaderBundle:Feed')->getUserFeeds($this->getUser());

        return ['feeds'=>$feeds];
    }

    /**
     * @Route("/feed/unsubscribe/{feed}/", name="feed_unsubscribe")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Feed $feed
     *
     * @return RedirectResponse
     */
    public function unsubscribeAction(Feed $feed){
        $feed->removeUser($this->getUser());
        $this->getUser()->removeFeed($feed);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('feed_edit'));
    }

    /**
     * Creates a form to create a Feed entity.
     *
     * @param Feed $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Feed $entity)
    {
        $form = $this->createForm(
            new FeedType(),
            $entity,
            array(
                'action' => $this->generateUrl('feed_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Добавить'));

        return $form;
    }
}
