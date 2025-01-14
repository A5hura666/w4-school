<?php

namespace App\Entity;

interface Sortable
{
    public function setPosition(int $position): static;
    public function getPosition(): ?int;
}
