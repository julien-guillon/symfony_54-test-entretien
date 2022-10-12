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
        'get',
        'post' => [
            'controller' => CreateArticleAction::class,
            'deserialize' => false,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => [
                                        'description' => 'Le titre de l\'article',
                                        'type' => 'string'
                                    ],
                                    'slug' => [
                                        'description' => 'le slug de l\'article',
                                        'type' => 'string'
                                    ],
                                    'introduction' => [
                                        'description' => 'Une introduction ou résumé',
                                        'type' => 'string'
                                    ],
                                    'content' => [
                                        'description' => 'Le contenu',
                                        'type' => 'string'
                                    ],
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                                'required' => ['title', 'slug', 'content']
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    itemOperations : [
        'get',
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
    #[Groups(['read:collection, read:item'])]
    private int $id;


    /**
     * @var string|null
     * Titre de l'article
     */
    #[ORM\Column(type : 'string', length: 150)]
    #[Assert\NotBlank(message: 'Merci de renseigner le titre')]
    #[Assert\Length(max: 150, maxMessage: 'Merci de renseigner un titre avec moins de 150 caractères')]
    #[Groups(['read:collection', 'write:collection', 'read:item', 'delete:item'])]
    private ?string $title;

    /**
     * @var string|null
     * Slug de l'article (endpoint)
     */
    #[ORM\Column(type : 'string', length: 150, unique: true)]
    #[Assert\NotBlank(message: 'Merci de renseigner le slug')]
    #[Assert\Length(max: 150, maxMessage: 'Merci de renseigner un slug avec moins de 150 caractères')]
    #[Groups(['read:collection', 'write:collection', 'read:item', 'delete:item'])]
    private ?string $slug;

    /**
     * @var string|null
     * Introduction de l'article
     */
    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Merci de renseigner une introduction avec moins de 255 caractères')]
    #[Groups(['read:collection', 'write:collection', 'read:item', 'write:item', 'delete:item'])]
    private ?string $introduction = null;


    /**
     * @var string|null
     * Contenu de l'article
     */
    #[ORM\Column(type : 'text')]
    #[Assert\NotBlank(message: 'Merci de rédiger un texte')]
    #[Groups(['read:collection', 'write:collection', 'read:item', 'write:item', 'delete:item'])]
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
    #[Groups(['read:collection', 'write:collection', 'read:item', 'delete:item'])]
    private ?string $photo = null;



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
}
