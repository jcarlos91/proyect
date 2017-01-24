<?php

namespace EscritoresBundle\Repository;

/**
 * EscritosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EscritosRepository extends \Doctrine\ORM\EntityRepository{
	public function getLatesEscritos($limit = null){
		$qd = $this->createQueryBuilder('b')
					->select('b')
					->addOrderBy('b.created','DESC');
		if (false == is_null($limit)) {
			$qd->setMaxResults($limit);
		}
		/*$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
            $qd,
            $this->get('request')->query->get('page', 1),10
        );*/
		return $qd->getQuery()->getResult();
	}

	public function getByEscritor($user){
        $qd = $this->createQueryBuilder('b')
                    ->select('b')
                    ->where('b.author = ?1')
                    ->setParameter(1, $user);
        return $qd->getQuery()->getResult();

    }
	public function getTags(){
		$blogTags = $this->createQueryBuilder('b')
						->select('b.tags')
						->getQuery()
						->getResult();
		$tags = array();
		foreach ($blogTags as $blogtag) {
			$tags = array_merge(explode(",", $blogtag['tags']), $tags);
		}	

		foreach ($tags as &$tag) {
			$tag = trim($tag);
		}					
		return $tags;
	}

	public function getTagsWeights($tags){
		$tagWeights = array();
		if (empty($tags)) {
			return $tagWeights;
		}
		foreach ($tags as $tag) {
			$tagWeights[$tag] = (isset($tagWeights[$tag])) ? $tagWeights[$tag] + 1 : 1;
		}
		uksort($tagWeights,function(){
			return rand() > rand();
		});

		$max = max($tagWeights);

		$multiplier = ($max > 5 ) ? 5 / $max : 1;
		foreach($tagWeights as $tag){
			$tag = ceil($tag * $multiplier);
		}

		return $tagWeights;
	}
}
