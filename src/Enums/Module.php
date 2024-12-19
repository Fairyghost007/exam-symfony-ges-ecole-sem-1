<?php

namespace App\Enums;

enum Module: string
{
    case MATHS = 'maths';
    case PHYSIQUE = 'physique';
    case SVT = 'svt';
    case ANGLAIS = 'anglais';
    case FRANCAIS = 'francais';
    case PHILOSOPHIE = 'philosophie';
    case HISTOIRE_GEO = 'histoire-geo';
    case ECONOMIE_GESTION = 'economie-gestion';
    case ESPAGNOL = 'espagnol';
    case INFORMATIQUE = 'informatique';

    public function value(): string
    {
        return $this->name;
    }
}
