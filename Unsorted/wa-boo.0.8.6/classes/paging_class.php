<?php
// =====================================================
// Description: This class handles the paging from a query to be print 
//              to the browser. You can customize it to your needs.
//
// This is distribute as is. Free of use and free to do anything you want.
//
// PLEASE REPORT ANY BUG TO ME BY EMAIL :)
//
// =========================
// Programmer:	  Pierre-Yves Lemaire
//											py@ottawa.com
// =========================
// Date:			2001-03-25
// Version: 2.0
//
// Modif: 
// Version 1.1 (2001-04-09) Remove 3 lines in getNumberOfPage() that were forgot after debugging
// Version 1.1 (2001-04-09) Modification to the exemple
// Version 1.1 (2001-04-10) Added more argv to the previous and next link. ( by: peliter@mail.peliter.com )

// Version 2.0 (2001-11-22) Complete re-write of the script
// Summary: The class will be make it easier to play with results...
// * The class now only returns 2 arrays. All HTML, except href, tag were remove.
// * Function printPaging() broken in two: getPagingArray() and getPagingRowArray()
// * Function openTable() and closeTable() removed.
// =====================================================
// =====================================================
class Paging {
  
  var $page_size;  // Number of result to show per page (decided by user)
  var $total_rows;     // Total number of items (SQL count from db)
  var $current_position;// Current position in recordset
  var $extra_argv;    // Extra argv of query string
  
  // ------------------------------------------------------------------------ Constructor
  //
  function Paging( $total_rows, $current_position, $page_size, $extra_argv = "" ){
    $this->total_rows = $total_rows;
    $this->page_size = $page_size;
    $this->current_position = $current_position;
    $this->extra_argv = urldecode( $extra_argv );
  } // End constructor

  // ------------------------------------------------------------------- getNumberOfPage()
  // This function returns the total number of page to display.
  function getNumberOfPage(){
    $nb_of_page = $this->total_rows / $this->page_size;
    return $nb_of_page;
  } // end function
	
  // -------------------------------------------------------------------- getCurrentPage()
  // This function returns the current page number.
  function getCurrentPage(){
    $int_cur_page = ( $this->current_position * $this->getNumberOfPage() ) / $this->total_rows;
    return number_format( $int_cur_page, 0 );
  } // end function
  
  // ----------------------------------------------------------------------- getPagingArray()
  // This function print the paging to the screen. 
  // This function returns an array:
  // $array_paging['lower'] lower limit of where we are in result set
  // $array_paging['upper'] upper limit of where we are in result set
  // $array_paging['total'] total number of result
  // $array_paging['previous_link'] href tag for previous link
  // $array_paging['next_link'] href tag for next link
  function getPagingArray(){
    global $PHP_SELF;

    $array_paging['lower'] = ( $this->current_position + 1 );

    if( $this->current_position + $this->page_size >= $this->total_rows ){
      $array_paging['upper'] = $this->total_rows;
    }else{
      $array_paging['upper'] = ( $this->current_position + $this->page_size );
    }
    
    $array_paging['total'] = $this->total_rows;
		
    if ( $this->current_position != 0 ){
      $array_paging['previous_link'] = "<a href=\"$PHP_SELF?current_position=". ( $this->current_position - $this->page_size ).$this->extra_argv ."\">";
    }			
    
    if( ( $this->total_rows - $this->current_position ) > $this->page_size ){
      $int_new_position = $this->current_position + $this->page_size;	
      $array_paging['next_link'] = "<a href=\"$PHP_SELF?current_position=$int_new_position". $this->extra_argv ."\">";
    }
    return $array_paging;
  } // end function

  // ----------------------------------------------------------------------- getPagingRowArray()
  // This function returns an array of string (href link with the page number)
  function getPagingRowArray(){
    global $PHP_SELF;

    for( $i=0; $i<$this->getNumberOfPage(); $i++ ){
      // if current page, do not make a link
      if( $i == $this->getCurrentPage() ){
        $array_all_page[$i] = "<b>". ($i+1) ."</b>";
      }else{
        $int_new_position = ( $i * $this->page_size );
        $array_all_page[$i] = "<a href=\"". $PHP_SELF ."?current_position=$int_new_position$this->extra_argv\">". ($i+1) ."</a>";
      }
    }
    return $array_all_page;
  } // end function
}; // End Class