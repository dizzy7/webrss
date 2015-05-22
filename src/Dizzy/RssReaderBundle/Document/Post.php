<?php

namespace Dizzy\RssReaderBundle\Document;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 * @MongoDB\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @MongoDB\String
     */
    private $title;

    /**
     * @MongoDB\String
     */
    private $body;

    /**
     * @MongoDB\String
     */
    private $url;

    /**
     * @MongoDB\Date
     */
    private $created;

    /**
     * @MongoDB\Date
     */
    private $fetched;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Feed", inversedBy="posts")
     */
    private $feed;

    /**
     * @MongoDB\ReferenceMany(targetDocument="User")
     */
    private $unreadByUsers;

    /**
     * @MongoDB\PrePersist()
     */
    public function prePersist()
    {
        if ($this->fetched === null) {
            $this->fetched = new DateTime();
        }
    }
    public function __construct()
    {
        $this->unreadByUsers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set body
     *
     * @param string $body
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get body
     *
     * @return string $body
     */
    public function getBody()
    {
        return $this->body;
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
     * Set created
     *
     * @param date $created
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return date $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set fetched
     *
     * @param date $fetched
     * @return self
     */
    public function setFetched($fetched)
    {
        $this->fetched = $fetched;
        return $this;
    }

    /**
     * Get fetched
     *
     * @return date $fetched
     */
    public function getFetched()
    {
        return $this->fetched;
    }

    /**
     * Set feed
     *
     * @param Dizzy\RssReaderBundle\Document\Feed $feed
     * @return self
     */
    public function setFeed(\Dizzy\RssReaderBundle\Document\Feed $feed)
    {
        $this->feed = $feed;
        return $this;
    }

    /**
     * Get feed
     *
     * @return Dizzy\RssReaderBundle\Document\Feed $feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * Add unreadByUser
     *
     * @param Dizzy\RssReaderBundle\Document\User $unreadByUser
     */
    public function addUnreadByUser(\Dizzy\RssReaderBundle\Document\User $unreadByUser)
    {
        $this->unreadByUsers[] = $unreadByUser;
    }

    /**
     * Remove unreadByUser
     *
     * @param Dizzy\RssReaderBundle\Document\User $unreadByUser
     */
    public function removeUnreadByUser(\Dizzy\RssReaderBundle\Document\User $unreadByUser)
    {
        $this->unreadByUsers->removeElement($unreadByUser);
    }

    /**
     * Get unreadByUsers
     *
     * @return Collection $unreadByUsers
     */
    public function getUnreadByUsers()
    {
        return $this->unreadByUsers;
    }
}
