<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={
 *             "get"
 *         }
 *     },
 *     denormalizationContext={
 *         "groups"={
 *             "get", "set", "patch"
 *         }
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "denormalization_context"={
 *                 "groups"={"set"}
 *             }
 *         }
 *     },
 *     itemOperations={
 *         "get",
 *         "patch"={
 *             "normalization_context"={
 *                 "groups"={"get"}
 *             },
 *             "denormalization_context"={
 *                 "groups"={"patch"}
 *             }
 *         },
 *         "delete"
 *     },
 * )
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @Vich\Uploadable
 */
class Article
{
    /**
     * @ApiProperty(identifier=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"get", "set"})
     * @ORM\Column(type="string", length=150)
     */
    private $title;

    /**
     * @ApiProperty(identifier=true)
     * @Gedmo\Slug(fields={"title"})
     * @Groups("get")
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $slug;

    /**
     * @Groups({"get", "set", "patch"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $introduction;

    /**
     * @Groups({"get", "set", "patch"})
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @Groups("get")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="photos", fileNameProperty="photo")
     *
     */
    private $photoFile;

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
    
    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;
    }
    
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('title', new Assert\Length([
            'min' => 1,
            'max' => 150,
            'minMessage' => 'Votre titre doit faire au moins {{ limit }} caractère.',
            'maxMessage' => 'Votre titre ne doit pas dépasser {{ limit }} caractères.',
        ]));
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'slug'
        ]));
        $metadata->addPropertyConstraint('introduction', new Assert\Length([
            'max' => 255,
            'maxMessage' => 'Votre introduction ne doit pas dépasser {{ limit }} caractères.',
        ]));
        $metadata->addPropertyConstraint('content', new Assert\NotBlank());
    }
}
