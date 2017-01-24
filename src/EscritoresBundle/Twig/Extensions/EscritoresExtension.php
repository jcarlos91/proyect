<?php 
namespace EscritoresBundle\Twig\Extensions;

/**
* 
*/
class EscritoresExtension extends \Twig_Extension{

	public function getFilters(){
		return array(
			'created_go' => new \Twig_Filter_Method($this,'createdAgo'),
		);
	}	
	
	public function createdAgo(\DateTime $dateTime){
		$delta = time() - $dateTime->getTimeStamp();
		if ($delta < 0) {
			throw new \Exception("createdAgo is enable to handle dates in the futire");
		}
		$duration = "";
		if ($delta < 60) {
			//segundos
			$time = $delta;
			$duration = $time . "second" . (($time > 1) ? "s" : " ") . "ago";
		}
		elseif($delta <= 3600){
			//minutes
			$time = floor($delta / 60);
			$duration = $time . "minute" . (($time > 1) ? "s" : " ") . "ago";
		}
		elseif($delta <= 86400 ){
			//Horas
			$time = floor($delta / 3600);
			$duration = $time . "hour" . (($time > 1) ? "s" : " ") . "ago";
		}
		else{
			//Días
			$time = floor($delta / 86400);
			$duration = $time ."day" . (($time > 1) ? "s" : " ")."ago";
		}
		return $duration;
	}

	public function getName(){
		return 'blogger_blog_extension';
	}
}