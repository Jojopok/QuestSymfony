<?php

namespace App\Service;


use App\Entity\Program;


class ProgramDuration {

    public function calculate(Program $program): string{

        $programDuration = 0;
        foreach ($program->getSeasons() as $season){
            foreach ($season->getEpisodes() as $episode){
                $programDuration += $episode->getDuration();
            }
        }

        $minutes = $programDuration%60;
        $hours = floor($programDuration/60);
        $days = floor($hours/24);

        $hours %= 24;

        $result = $days. " jours " . $hours . " heure ". $minutes . " minutes";

        return $result;
    }

}