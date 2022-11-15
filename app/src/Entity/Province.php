<?php

namespace App\Entity;

use App\Repository\ProvinceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProvinceRepository::class)]
class Province
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'province', targetEntity: Municipality::class, orphanRemoval: true)]
    private Collection $municipalities;

    #[ORM\ManyToOne(inversedBy: 'provinces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Community $community = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Municipality $capital = null;

    #[ORM\Column]
    private ?int $population = null;

    public function __construct()
    {
        $this->municipalities = new ArrayCollection();
    }
}
