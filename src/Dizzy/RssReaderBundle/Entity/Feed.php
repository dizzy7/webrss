<?php

namespace Dizzy\RssReaderBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Feed
 *
 * @ORM\Table(name="rss_feed")
 * @ORM\Entity(repositoryClass="Dizzy\RssReaderBundle\Entity\FeedRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Feed
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=1024)
     */
    private $url;


    /**
     * @var Datetime
     *
     * @ORM\Column(name="last_check", type="datetime")
     */
    private $lastcheck;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="feeds")
     */
    private $users;

    /**
     * @var Post[]
     *
     * @ORM\OneToMany(targetEntity="Post",mappedBy="feed")
     */
    private $posts;

    /**
     * @ORM\PrePersist()
     *
     * @return void
     */
    public function prePersist()
    {
        if ($this->lastcheck === null) {
            $this->lastcheck = new DateTime("1970-01-01");
        }
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Feed
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
     * Set url
     *
     * @param string $url
     *
     * @return Feed
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
     * Set lastcheck
     *
     * @param \DateTime $lastcheck
     *
     * @return Feed
     */
    public function setLastcheck($lastcheck)
    {
        $this->lastcheck = $lastcheck;

        return $this;
    }

    /**
     * Get lastcheck
     *
     * @return \DateTime
     */
    public function getLastcheck()
    {
        return $this->lastcheck;
    }

    /**
     * Add users
     *
     * @param \Dizzy\RssReaderBundle\Entity\User $users
     *
     * @return Feed
     */
    public function addUser(\Dizzy\RssReaderBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Dizzy\RssReaderBundle\Entity\User $users
     */
    public function removeUser(\Dizzy\RssReaderBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add posts
     *
     * @param \Dizzy\RssReaderBundle\Entity\Post $posts
     *
     * @return Feed
     */
    public function addPost(\Dizzy\RssReaderBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Dizzy\RssReaderBundle\Entity\Post $posts
     */
    public function removePost(\Dizzy\RssReaderBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
