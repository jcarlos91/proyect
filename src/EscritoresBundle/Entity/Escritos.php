<?php

namespace EscritoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCo0llection;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * Escritos
 * @ORM\Entity(repositoryClass="EscritoresBundle\Repository\EscritosRepository")
 * @ORM\Table(name="escritos")
 * @ORM\HasLifecycleCallbacks()
 */
class Escritos
{
    /**
     * @var int
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
     * @var \EscritoresBundle\Entity\Users
     * @ORM\ManyToOne(targetEntity="EscritoresBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="author", referencedColumnName="id")
     *   })
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="blog", type="text")
     */
    private $blog;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=150)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="text")
     */
    private $tags;

    /**
     * 
     *
     * @ORM\OneToMany(targetEntity="EscritoresBundle\Entity\Comment", mappedBy="blog")
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;


    /**
     * Get id
     *
     * @return int
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
     * @return Escritos
     */
    public function setTitle($title)
    {
        $this->title = $title;

        $this->setSlug($this->title);

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
     * Set author
     *
     * @param \EscritoresBundle\Entity\Users
     *
     * @return Escritos
     */
    public function setAuthor(\EscritoresBundle\Entity\Users $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \EscritoresBundle\Entity\Users
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set blog
     *
     * @param string $blog
     *
     * @return Escritos
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return string
     */
    public function getBlog($length=null)
    {
        if(false ===  is_null($length) && $length > 0)
            return substr($this->blog,0,$length);
        else
            return $this->blog;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Escritos
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return Escritos
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set comments
     *
     * @param EscritoresBundle\Entity\Comment $comment
     *
     * @return Escritos
     */
    public function setComments(\EscritoresBundle\Entity\Comment $comments = null)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return EscritoresBundle\Entity\Comment
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set created
     *
     * @param string $created
     *
     * @return Escritos
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Escritos
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Escritos
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add comment
     *
     * @param \EscritesBundle\Entity\Comment $comment
     *
     * @return Blog
     */
    public function addComment(\EscritoresBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \EscritoresBundle\Entity\Comment $comment
     */
    public function removeComment(\EscritoresBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function __toString(){
        return $this->getTitle();
    }

    public function slugify($text){
        //sustituye caracteres de espacio  o digitos en un -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        //recorta espacios en ambos extremos
        $text = trim($text,'-');

        //translitera
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        //cambia a minusculas
        $text = strtolower($text);

        //eliminar carecteres indeseables
        $text = preg_replace('#[^\\pL\d]+#u', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

