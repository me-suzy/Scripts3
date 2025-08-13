$types_to_register = array('GET','POST','COOKIE','SESSION','SERVER');
  foreach ($types_to_register as $type) {
    $arr = @${'HTTP_' . $type . '_VARS'};
    if (@count($arr) > 0) {
      extract($arr, EXTR_OVERWRITE);
    }
  }