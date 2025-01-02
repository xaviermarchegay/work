<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GitlabInstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GitlabInstanceRepository::class)]
class GitlabInstance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    /**
     * @var Collection<int, GitlabProject>
     */
    #[ORM\OneToMany(targetEntity: GitlabProject::class, mappedBy: 'instance', orphanRemoval: true)]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection<int, GitlabProject>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(GitlabProject $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setInstance($this);
        }

        return $this;
    }

    public function removeProject(GitlabProject $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getInstance() === $this) {
                $project->setInstance(null);
            }
        }

        return $this;
    }
}
