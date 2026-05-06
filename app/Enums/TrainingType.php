<?php

namespace App\Enums;

enum TrainingType: string
{
    case Leadership = "Leadership";
    case SoftSkills = "Soft Skills";
    case Orientation = "Orientation";
    case Upskilling = "Upskilling";
    case Reskilling = "Reskilling";
    case Technical = "Technical";
    case Safety = "Safety";
    case Service = "Service";
    case DEI = "DEI";
    case Compliance = "Compliance";
}
