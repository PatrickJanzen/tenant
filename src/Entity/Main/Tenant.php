<?php

namespace App\Entity\Main;

use App\Repository\TenantDbConfigRepository;
use Doctrine\ORM\Mapping as ORM;
use Hakam\MultiTenancyBundle\Services\TenantDbConfigurationInterface;
use Hakam\MultiTenancyBundle\Traits\TenantDbConfigTrait;

#[ORM\Entity(repositoryClass: TenantDbConfigRepository::class)]
class Tenant implements TenantDbConfigurationInterface
{
    use TenantDbConfigTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $OrganisationName = null;

    public function getOrganisationName(): ?string
    {
        return $this->OrganisationName;
    }

    public function setOrganisationName(?string $OrganisationName): self
    {
        $this->OrganisationName = $OrganisationName;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
}
