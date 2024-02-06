<?php

namespace App\Entity;

use App\Repository\PedidosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidosRepository::class)]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $enviado = null;

    #[ORM\OneToMany(targetEntity: restaurante::class, mappedBy: 'restaurante')]
    private Collection $restaurante;

    public function __construct()
    {
        $this->restaurante = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEnviado(): ?int
    {
        return $this->enviado;
    }

    public function setEnviado(int $enviado): static
    {
        $this->enviado = $enviado;

        return $this;
    }

    /**
     * @return Collection<int, restaurante>
     */
    public function getRestaurante(): Collection
    {
        return $this->restaurante;
    }

    public function addRestaurante(restaurante $restaurante): static
    {
        if (!$this->restaurante->contains($restaurante)) {
            $this->restaurante->add($restaurante);
            $restaurante->setRestaurante($this);
        }

        return $this;
    }

    public function removeRestaurante(restaurante $restaurante): static
    {
        if ($this->restaurante->removeElement($restaurante)) {
            // set the owning side to null (unless already changed)
            if ($restaurante->getRestaurante() === $this) {
                $restaurante->setRestaurante(null);
            }
        }

        return $this;
    }
}
