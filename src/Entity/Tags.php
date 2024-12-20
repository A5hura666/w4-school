<?php

namespace App\Entity;

use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagsRepository::class)]
class Tags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, CourseTags>
     */
    #[ORM\OneToMany(targetEntity: CourseTags::class, mappedBy: 'tagId')]
    private Collection $courseTags;

    public function __construct()
    {
        $this->courseTags = new ArrayCollection();
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

    /**
     * @return Collection<int, CourseTags>
     */
    public function getCourseTags(): Collection
    {
        return $this->courseTags;
    }

    public function addCourseTag(CourseTags $courseTag): static
    {
        if (!$this->courseTags->contains($courseTag)) {
            $this->courseTags->add($courseTag);
            $courseTag->setTagId($this);
        }

        return $this;
    }

    public function removeCourseTag(CourseTags $courseTag): static
    {
        if ($this->courseTags->removeElement($courseTag)) {
            // set the owning side to null (unless already changed)
            if ($courseTag->getTagId() === $this) {
                $courseTag->setTagId(null);
            }
        }

        return $this;
    }
}
