<?php

namespace App\Entity;

use App\Repository\PackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackageRepository::class)]
class Package
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $activity_count = null;

    #[ORM\ManyToMany(targetEntity: ACtivity::class, inversedBy: 'packages')]
    private Collection $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivityCount(): ?int
    {
        return $this->activity_count;
    }

    public function setActivityCount(int $activity_count): static
    {
        $this->activity_count = $activity_count;

        return $this;
    }

    /**
     * @return Collection<int, ACtivity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(ACtivity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
        }

        return $this;
    }

    public function removeActivity(ACtivity $activity): static
    {
        $this->activities->removeElement($activity);

        return $this;
    }
}
