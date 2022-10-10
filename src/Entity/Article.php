<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    #[Assert\NotBlank(message: 'Merci de renseigner le titre')]
    #[Assert\Length(max: 150, maxMessage: 'Merci de renseigner un titre avec moins de 150 caractères')]
    private string $title;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    #[Assert\NotBlank(message: 'Merci de renseigner le slug')]
    #[Assert\Length(max: 150, maxMessage: 'Merci de renseigner un slug avec moins de 150 caractères')]
    private string $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Assert\Length(max: 255, maxMessage: 'Merci de renseigner une introduction avec moins de 255 caractères')]
    private ?string $introduction;

    /**
     * @ORM\Column(type="text")
     */
    #[Assert\NotBlank(message: 'Merci de rédiger un texte')]
    private ?string $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
