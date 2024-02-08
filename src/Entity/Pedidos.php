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
    private ?bool $enviado = null;

    #[ORM\ManyToOne(inversedBy: 'pedidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurante $restaurante = null;

    #[ORM\OneToMany(targetEntity: Pedidosproductos::class, mappedBy: 'pedido', orphanRemoval: true)]
    private Collection $pedidosproductos;

    #[ORM\Column]
    private ?float $precio = null;

    public function __construct()
    {
        $this->pedidosproductos = new ArrayCollection();
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

    public function getRestaurante(): ?Restaurante
    {
        return $this->restaurante;
    }

    public function setRestaurante(?Restaurante $restaurante): static
    {
        $this->restaurante = $restaurante;

        return $this;
    }

    /**
     * @return Collection<int, Pedidosproductos>
     */
    public function getPedidosproductos(): Collection
    {
        return $this->pedidosproductos;
    }

    public function addPedidosproducto(Pedidosproductos $pedidosproducto): static
    {
        if (!$this->pedidosproductos->contains($pedidosproducto)) {
            $this->pedidosproductos->add($pedidosproducto);
            $pedidosproducto->setPedido($this);
        }

        return $this;
    }

    public function removePedidosproducto(Pedidosproductos $pedidosproducto): static
    {
        if ($this->pedidosproductos->removeElement($pedidosproducto)) {
            // set the owning side to null (unless already changed)
            if ($pedidosproducto->getPedido() === $this) {
                $pedidosproducto->setPedido(null);
            }
        }

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }
}
