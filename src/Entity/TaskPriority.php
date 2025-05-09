<?php

namespace App\Entity;

enum TaskPriority: string
{
    case LOW = 'basse';
    case NORMAL = 'normale';
    case HIGH = 'haute';
}