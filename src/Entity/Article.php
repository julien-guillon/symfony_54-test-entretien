<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=150, nullable=false, unique=true)
     * 
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $introduction;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $content;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $photo;

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle(): ?string 
    {
		return $this->title;
	}

    /**
     * @param mixed $title
     */
	public function setTitle(?string $title) {
		$this->title = $title;
	}

    /**
     * @return mixed
     */
    public function getSlug(): ?string 
    {
		return $this->slug;
	}

    /**
     * @param mixed $slug
     */
	public function setSlug(?string $slug) {
		$this->slug = $slug;
	}

    /**
     * @return mixed
     */
    public function getIntroduction(): ?string 
    {
		return $this->introduction;
	}

    /**
     * @param mixed $introduction
     */
	public function setIntroduction(?string $introduction) 
    {
		$this->introduction = $introduction;
	}

    /**
     * @return mixed
     */
    public function getContent(): ?string 
    {
		return $this->content;
	}

    /**
     * @param mixed $content
     */
	public function setContent(?string $content)
    {
		$this->content = $content;
	}

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    
}
