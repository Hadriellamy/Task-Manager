<?php

namespace App\Entity;

enum TaskStatus: string
{
    case TODO = 'à faire';
    case IN_PROGRESS = 'en cours';
    case COMPLETED = 'terminé';
}