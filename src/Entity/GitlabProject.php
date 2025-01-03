<?php

namespace App\Entity;

use App\Repository\GitlabProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GitlabProjectRepository::class)]
class GitlabProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $project_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GitlabInstance $instance = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getProjectId(): ?int
    {
        return $this->project_id;
    }

    public function setProjectId(int $project_id): static
    {
        $this->project_id = $project_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getInstance(): ?GitlabInstance
    {
        return $this->instance;
    }

    public function setInstance(?GitlabInstance $instance): static
    {
        $this->instance = $instance;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
