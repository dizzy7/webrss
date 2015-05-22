<?php

namespace Dizzy\RssReaderBundle\Document;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Dizzy\RssReaderBundle\Document\Repositories\FeedRepository")
 * @MongoDB\HasLifecycleCallbacks
 */
class Feed
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $title;

    /**
     * @MongoDB\String
     */
    private $url;


    /**
     * @MongoDB\Date()
     */
    private $lastcheck;

    /**
     * @MongoDB\ReferenceMany(targetDocument="User", inversedBy="feeds")
     */
    private $users;

    /**
     * @MongoDB\EmbedMany(targetDocument="Post")
     */
    private $posts;

    /**
     * @MongoDB\PrePersist()
     */
    public function prePersist()
    {
        if ($this->lastcheck === null) {
            $this->lastcheck = new DateTime('1970-01-01');
        }
    }
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set lastcheck
     *
     * @param DateTime $lastcheck
     * @return self
     */
    public function setLastcheck($lastcheck)
    {
        $this->lastcheck = $lastcheck;
        return $this;
    }

    /**
     * Get lastcheck
     *
     * @return DateTime $lastcheck
     */
    public function getLastcheck()
    {
        return $this->lastcheck;
    }

    /**
     * Add user
     *
     * @param User $user
     */
    public function addUser(\Dizzy\RssReaderBundle\Document\User $user)
    {
        $this->users[] = $user;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(\Dizzy\RssReaderBundle\Document\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return Collection $users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add post
     *
     * @param Post $post
     */
    public function addPost(\Dizzy\RssReaderBundle\Document\Post $post)
    {
        $this->posts[] = $post;
    }

    /**
     * Remove post
     *
     * @param Post $post
     */
    public function removePost(\Dizzy\RssReaderBundle\Document\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return Collection $posts
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
