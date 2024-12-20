<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursesRepository::class)]
class Courses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $teacher_id = null;

    /**
     * @var Collection<int, Chapters>
     */
    #[ORM\OneToMany(targetEntity: Chapters::class, mappedBy: 'course_id')]
    private Collection $chapters;

    /**
     * @var Collection<int, CourseEnrollments>
     */
    #[ORM\OneToMany(targetEntity: CourseEnrollments::class, mappedBy: 'coursesId')]
    private Collection $courseEnrollments;

    /**
     * @var Collection<int, CourseTags>
     */
    #[ORM\OneToMany(targetEntity: CourseTags::class, mappedBy: 'courseId')]
    private Collection $courseTags;


    public function __construct()
    {
        $this->chapters = new ArrayCollection();
        $this->courseEnrollments = new ArrayCollection();
        $this->courseTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTeacherId(): ?user
    {
        return $this->teacher_id;
    }

    public function setTeacherId(?user $teacher_id): static
    {
        $this->teacher_id = $teacher_id;

        return $this;
    }

    /**
     * @return Collection<int, Chapters>
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapters $chapter): static
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setCourseId($this);
        }

        return $this;
    }

    public function removeChapter(Chapters $chapter): static
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getCourseId() === $this) {
                $chapter->setCourseId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CourseEnrollments>
     */
    public function getCourseEnrollments(): Collection
    {
        return $this->courseEnrollments;
    }

    public function addCourseEnrollment(CourseEnrollments $courseEnrollment): static
    {
        if (!$this->courseEnrollments->contains($courseEnrollment)) {
            $this->courseEnrollments->add($courseEnrollment);
            $courseEnrollment->setCoursesId($this);
        }

        return $this;
    }

    public function removeCourseEnrollment(CourseEnrollments $courseEnrollment): static
    {
        if ($this->courseEnrollments->removeElement($courseEnrollment)) {
            // set the owning side to null (unless already changed)
            if ($courseEnrollment->getCoursesId() === $this) {
                $courseEnrollment->setCoursesId(null);
            }
        }

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
            $courseTag->setCourseId($this);
        }

        return $this;
    }

    public function removeCourseTag(CourseTags $courseTag): static
    {
        if ($this->courseTags->removeElement($courseTag)) {
            // set the owning side to null (unless already changed)
            if ($courseTag->getCourseId() === $this) {
                $courseTag->setCourseId(null);
            }
        }

        return $this;
    }
}
