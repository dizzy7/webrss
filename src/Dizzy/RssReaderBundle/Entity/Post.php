<?php

namespace Dizzy\RssReaderBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Dizzy\RssReaderBundle\Entity\Feed;

/**
 * Post
 *
 * @ORM\Table(name="rss_post")
 * @ORM\Entity(repositoryClass="Dizzy\RssReaderBundle\Entity\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=1024)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=1024)
     */
    private $url;

    /**
     * @var Datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(name="fetched", type="datetime")
     */
    private $fetched;

    /**
     * @var Feed
     *
     * @ORM\ManyToOne(targetEntity="Feed", inversedBy="posts")
     * @ORM\JoinColumn(name="feed_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $feed;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="Dizzy\RssReaderBundle\Entity\User",inversedBy="unreadPosts")
     * @ORM\JoinTable(name="rss_user_post")
     */
    private $unreadByUsers;


    /**
     * @ORM\PrePersist()
     *
     * @return void
     */
    public function prePersist()
    {
        if ($this->fetched === null) {
            $this->fetched = new DateTime();
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->unreadPosts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Post
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Post
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Post
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set feed
     *
     * @param \Dizzy\RssReaderBundle\Entity\Feed $feed
     *
     * @return Post
     */
    public function setFeed(\Dizzy\RssReaderBundle\Entity\Feed $feed = null)
    {
        $this->feed = $feed;

        return $this;
    }

    /**
     * Get feed
     *
     * @return \Dizzy\RssReaderBundle\Entity\Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }


    /**
     * Add unreadByUsers
     *
     * @param \Dizzy\RssReaderBundle\Entity\User $unreadByUsers
     *
     * @return Post
     */
    public function addUnreadByUser(\Dizzy\RssReaderBundle\Entity\User $unreadByUsers)
    {
        $this->unreadByUsers[] = $unreadByUsers;

        return $this;
    }

    /**
     * Remove unreadByUsers
     *
     * @param \Dizzy\RssReaderBundle\Entity\User $unreadByUsers
     */
    public function removeUnreadByUser(\Dizzy\RssReaderBundle\Entity\User $unreadByUsers)
    {
        $this->unreadByUsers->removeElement($unreadByUsers);
    }

    /**
     * Get unreadByUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnreadByUsers()
    {
        return $this->unreadByUsers;
    }
}
