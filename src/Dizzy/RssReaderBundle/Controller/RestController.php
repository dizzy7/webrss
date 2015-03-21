<?php

namespace Dizzy\RssReaderBundle\Controller;

use DateInterval;
use DateTime;
use Dizzy\RssReaderBundle\Entity\Post;
use Dizzy\RssReaderBundle\Entity\User;
use Dizzy\RssReaderBundle\Interfaces\TokenAuthenticatedController;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestController extends Controller implements TokenAuthenticatedController
{
    /**
     * @Route("/api/getFeeds/",name="api_get_feeds",options={"expose":true})
     * @Method("POST")
     */
    public function getFeedsAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $resp          = $entityManager->getRepository('DizzyRssReaderBundle:Feed')->getUserFeedsWithPostsCount($this->getUser());
        $result        = [];
        foreach ($resp as $item) {
            $feed                = $item[0];
            $feed['unreadCount'] = $item[1];
            $result[]            = $feed;
        }

        return new JsonResponse(['success'=>true,'data'=>$result]);
    }

    /**
     * @Route("/api/getPosts/{feed}/{fromId}/",name="api_get_posts",options={"expose":true})
     * @Method("POST")
     * @param          $feed
     * @param DateTime $fromDate
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPostsAction($feed, $fromId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($feed === 'all') {
            $newPosts = $entityManager->getRepository('DizzyRssReaderBundle:Post')->getPosts(
                $this->getUser(),
                null,
                $fromId
            );
        } else {
            $feed = $entityManager->find('DizzyRssReaderBundle:Feed', $feed);
            if ($feed) {
                /** @var Collection $userFeeds */
                $userFeeds = $this->getUser()->getFeeds();

                if ($userFeeds->contains($feed)) {
                    $newPosts = $entityManager->getRepository('DizzyRssReaderBundle:Post')->getPosts(
                        $this->getUser(),
                        $feed,
                        $fromId
                    );
                } else {
                    return new JsonResponse(['error' => 'Лента не найдена']);
                }
            } else {
                return new JsonResponse(['error' => 'Лента не найдена']);
            }
        }

        return new JsonResponse($newPosts);
    }

    /**
     * @Route("/api/setPostRead/{post}/",name="api_set_post_read",options={"expose":true})
     * @Method("POST")
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function setPostReadAction(Post $post)
    {
        $user = $this->getUser();
        if ($post->getUnreadByUsers()->contains($user)) {
            $post->removeUnreadByUser($user);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /** Авторизация мобильных приложений
     * @Route("/api/getToken/",name="api_get_token")
     *
     */
    public function getToken(Request $request){
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('username');
        $password = $request->request->get('password');

        /** @var User $user */
        $user = $em->getRepository('DizzyRssReaderBundle:User')->findOneBy(['username'=>$username]);
        if($user){
            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($user);
            $encoded_pass = $encoder->encodePassword($password, $user->getSalt());

            if($encoded_pass === $user->getPassword()){
                $user->setMobileToken(md5(microtime().$user->getSalt()));
                $expire = new DateTime();
                $expire->add(new DateInterval('P90D'));
                $user->setMobileTokenExpire($expire);
                $em->flush();

                return new JsonResponse(['success'=>true,'token'=>$user->getMobileToken()]);
            }
        }

        return new JsonResponse(['success'=>false]);
    }

    public function getUser()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        if ($token = $request->request->get('token')) {
            $entityManager = $this->getDoctrine()->getManager();
            /** @var $user User */
            $user = $entityManager->getRepository('DizzyRssReaderBundle:User')->findOneBy(['mobileToken' => $token]);
            if ($user) {
                $now = new DateTime();
                if ($now < $user->getMobileTokenExpire()) {
                    return $user;
                }
            }
        }

        return parent::getUser();
    }

}
