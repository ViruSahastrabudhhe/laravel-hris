<?php

namespace App\Enums;

enum TrainingType: string
{
    case Leadership = "Leadership";
    case SoftSkills = "Soft Skills";
    case Technical = "Technical";
    case Safety = "Safety";
    case Compliance = "Compliance";
}
