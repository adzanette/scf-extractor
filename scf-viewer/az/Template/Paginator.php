<?php

namespace AZ\Framework\Template;

class Paginator{

  protected $pageSize;
  protected $pageWindow;
  protected $page;
  protected $total;
  protected $link;

  public function __construct($pageSize, $pageWindow){
    $this->pageSize = $pageSize;
    $this->pageWindow = $pageWindow;
  }

  public function initialize($page, $total, $link){
    $this->page = $page;
    $this->total = $total;
    $this->link = $link;
  }

  private function getLink($page){
    return str_replace('{page}', $page, $this->link);
  }
  
  public function __toString(){
    $totalPages = ceil($this->total/$this->pageSize);
    
    $html = '';
    $html .= '<div ><ul class="pagination">';

    if($this->page == 1) {
      $html .= '<li class="disabled"><a href="#">&lt;&lt;</a></li>';
      $html .= '<li class="disabled"><a href="#">&lt;</a></li>';
      $html .= '<li class="active"><a href="'.$this->getLink(1).'">1</a></li>';
    }else{
      $previous = $this->page - $this->pageWindow < 1 ? 1 : $this->page - $this->pageWindow;
      $html .= '<li><a href="'.$this->getLink($previous).'">&lt;&lt;</a></li>';
      $html .= '<li><a href="'.$this->getLink($this->page-1).'">&lt;</a></li>';
      $html .= '<li><a href="'.$this->getLink(1).'">1</a></li>';
    }

    if($this->page > $this->pageWindow+2) $html .= '<li class="active"><a href="#">...</a></li>';

    $init = $this->page - $this->pageWindow < 2 ? 2 : $this->page - $this->pageWindow;
    $end = $this->page + $this->pageWindow > $totalPages ? $totalPages-1 : $this->page + $this->pageWindow;
    for($i = $init; $i <= $end; $i++){
      if($this->page == $i){
        $html .= '<li class="active"><a href="#">'.$i.'</a></li>';
      }else{
        $html .= '<li><a href="'.$this->getLink($i).'">'.$i.'</a></li>';
      }
    }

    if($this->page + $this->pageWindow < $totalPages-1) $html .= '<li class="active"><a href="#">...</a></li>';

    if($this->page == $totalPages) {
      if ($totalPages != 1) $html .= '<li class="active"><a href="'.$this->getLink($totalPages).'">'.$totalPages.'</a></li>';
      $html .= '<li class="disabled"><a href="#">&gt;</a></li>';
      $html .= '<li class="disabled"><a href="#">&gt;&gt;</a></li>';
    } else {
      $html .= '<li><a href="'.$this->getLink($totalPages).'">'.$totalPages.'</a></li>';
      $html .= '<li><a href="'.$this->getLink($this->page+1).'">&gt;</a></li>';
      $next = $this->pageWindow + $this->page >= $totalPages ? $totalPages : $this->page + $this->pageWindow;
      $html .= '<li><a href="'.$this->getLink($next).'">&gt;&gt;</a></li>';
    }

    $html .= '</ul></div>';
    return $html;
  }
}

