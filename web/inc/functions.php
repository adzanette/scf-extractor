<?php
define ("PAGE_SIZE", 50);
define('PAGE_WINDOW', 12);

function paginate($page, $total, $link, $parameters) {
  $totalPages = ceil($total/PAGE_SIZE);
    
  $html = '';
  $html .= '<div class="pagination pagination-small"><ul>';

  if($page == 1) {
    $html .= '<li class="disabled"><a href="#">&lt;&lt;</a></li>';
    $html .= '<li class="disabled"><a href="#">&lt;</a></li>';
    $html .= '<li class="active"><a href="'.$link.'?page=1&'.$parameters.'">1</a></li>';
  }else{
    $previous = $page - PAGE_WINDOW < 1 ? 1 : $page - PAGE_WINDOW;
    $html .= '<li><a href="'.$link.'?page='.$previous.'&'.$parameters.'">&lt;&lt;</a></li>';
    $html .= '<li><a href="'.$link.'?page='.($page-1).'&'.$parameters.'">&lt;</a></li>';
    $html .= '<li><a href="'.$link.'?page=1&'.$parameters.'">1</a></li>';
  }

  if($page > PAGE_WINDOW+2) $html .= '<li class="active"><a href="#">...</a></li>';

  $init = $page - PAGE_WINDOW < 2 ? 2 : $page - PAGE_WINDOW;
  $end = $page + PAGE_WINDOW > $totalPages ? $totalPages-1 : $page + PAGE_WINDOW;
  for($i = $init; $i <= $end; $i++){
    if($page == $i){
      $html .= '<li class="active"><a href="#">'.$i.'</a></li>';
    }else{
      $html .= '<li><a href="'.$link.'?page='.$i.'&'.$parameters.'">'.$i.'</a></li>';
    }
  }

  if($page+PAGE_WINDOW < $totalPages-1) $html .= '<li class="active"><a href="#">...</a></li>';

  if($page == $totalPages) {
    if ($totalPages != 1) $html .= '<li class="active"><a href="'.$link.'?page='.$totalPages.'&'.$parameters.'">'.$totalPages.'</a></li>';
    $html .= '<li class="disabled"><a href="#">&gt;</a></li>';
    $html .= '<li class="disabled"><a href="#">&gt;&gt;</a></li>';
  } else {
    $html .= '<li><a href="'.$link.'?page='.$totalPages.'&'.$parameters.'">'.$totalPages.'</a></li>';
    $html .= '<li><a href="'.$link.'?page='.($page+1).'&'.$parameters.'">&gt;</a></li>';
    $next = PAGE_WINDOW + $page >= $totalPages ? $totalPages : $page+PAGE_WINDOW;
    $html .= '<li><a href="'.$link.'?page='.$next.'&'.$parameters.'">&gt;&gt;</a></li>';
  }

  $html .= '</ul></div>';
  echo $html;
}

function echo_pre($s){
  echo "<pre>";
  print_r(htmlentities($s));
  echo "</pre>";
}

function select($name, $arr,  $selected){
  $select = "<option value=\"\">Selecione</option>";
  foreach ($arr as $a => $b){
    if ($a != $selected){
      $select .= "<option value=\"{$a}\">{$b}</option>";
    }else{
      $select .= "<option value=\"{$a}\" selected>{$b}</option>";
    }
  } 
  $select = "<select id=\"{$name}\" name=\"{$name}\">{$select}</select>";
  echo $select;
}

function get_roles(){
  $content = file_get_contents('inc/papeis.txt');
  $list = explode(',', $content);
  $roles = array();
  foreach ($list as $l){
    $l = trim($l);
    if ($l != ''){
     $roles[$l] = $l;
    } 
  }
  return $roles;
}

?>
