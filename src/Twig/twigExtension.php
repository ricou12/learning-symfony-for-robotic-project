<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class twigExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('sayHello', [$this, 'sayHello']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sayHello', [$this, 'sayHello']),
            new TwigFunction('dateDiff', [$this, 'dateDiff']),
        ];
    }

    public function sayHello()
    {
        return ('hello');
    }

    public function dateDiff(string $date){
        $tomorrow = date("m/d/Y H:i", strtotime('now'));
        $tomorrow = date_create($tomorrow);
        $target = date_create($date);
        $diff = $tomorrow->diff($target);

        $years = $diff->y;
        $month = $diff->m;
        $days = $diff->d;
        $hour = $diff->h;
        $min = $diff->i;

        $resultat=array();
        
        if($years >= 1){
            if($years == 1){
               $resultat[]= $years." an"; 
            } else {
                $resultat[]= $years." ans";
            }
        } 
        elseif( $month >= 1 ) {
            $resultat[]= $month." mois";
        } 
        elseif( $days >= 1 ){
            if($days == 1){
                $resultat[]= $days." jour"; 
             } else {
                $resultat[]= $days." jours";
             }
        }
        elseif( $hour >= 1 ){
            if($hour == 1){
                $resultat[]= $hour." heure"; 
             } else {
                $resultat[]= $hour." heures";
             }
        }
        elseif( $min >= 1 ){
            if($min == 1){
                $resultat[]= $min." minute"; 
             } else {
                $resultat[]= $min." minutes";
             }
        }
        
        $dateResult = implode(" ", $resultat);
        return $dateResult;
    }
}