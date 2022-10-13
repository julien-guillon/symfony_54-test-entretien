<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateArticleAction;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[UniqueEntity('slug', message: 'Ce slug est utilisé pour un autre article')]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'read']],
        'post' => [
            'controller' => CreateArticleAction::class,
            'denormalization_context' => ['groups' => 'write:collection'],
        ],
    ],
    itemOperations : [
        'get' => ['normalization_context' => ['groups' => 'read']],
        'put' => [
            'denormalization_context' => ['groups' => 'write:item']
        ],
        'delete'
    ],
    order: ['id' => 'DESC'],
    paginationEnabled: false,
)]
class Article
{

    /**
     * @var int
     * Id unique de l'article
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type : 'integer')]
    #[Groups(['read'])]
    private int $id;


    /**
     * @var string|null
     * Titre de l'article
     */
    #[ORM\Column(type : 'string', length: 150)]
    #[Assert\NotBlank(message: 'Merci de renseigner le titre')]
    #[Assert\Length(max: 150, maxMessage: 'Merci de renseigner un titre avec moins de 150 caractères')]
    #[Groups(['write:collection', 'read'])]
    private ?string $title;

    /**
     * @var string|null
     * Slug de l'article (endpoint)
     */
    #[ORM\Column(type : 'string', length: 150, unique: true)]
    #[Assert\NotBlank(message: 'Merci de renseigner le slug')]
    #[Assert\Length(max: 150, maxMessage: 'Merci de renseigner un slug avec moins de 150 caractères')]
    #[Groups(['write:collection', 'read'])]
    private ?string $slug;

    /**
     * @var string|null
     * Introduction de l'article
     */
    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Merci de renseigner une introduction avec moins de 255 caractères')]
    #[Groups(['write:collection', 'write:item', 'read'])]
    private ?string $introduction = null;


    /**
     * @var string|null
     * Contenu de l'article
     */
    #[ORM\Column(type : 'text')]
    #[Assert\NotBlank(message: 'Merci de rédiger un texte')]
    #[Groups(['write:collection', 'write:item', 'read'])]
    private ?string $content;

    /**
     * @var string|null
     * Photo associée à l'article (stockée sous forme d'URL en BDD)
     */
    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    #[Assert\File(
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Merci de choisir une image au format JPEG ou PNG',
        groups: ['create']
    )]
    #[Groups(['read'])]
    private ?string $photo = null;

    /**
     * @var string|null
     * Champ fichier non stocké en DB utilisé pour l'upload REST
     */
    #[Groups(['write:collection'])]
    private ?string $file = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }
}
