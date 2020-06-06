<?php

include "Link.php";


class LinksRepository
{

    public function __construct($rawLinks){

        // This includes groups such as name, publisher, school, type, isbn, etc...
        $this->allLinks = [];

        foreach ($rawLinks as $rawLink){
            $this->allLinks[] = new Link($rawLink);
        }
    }

    public function getAllLinks(){
        return $this->allLinks;
    }

    public function getAllLinksForItem($itemId){
        $itemLinks = [];
        foreach ($this->allLinks as $link){
            $result = $link->isObjectPartOfLink($itemId);
            if($result)
                $itemLinks[] = $link->getLinkId();
        }
        return $itemLinks;
    }
}