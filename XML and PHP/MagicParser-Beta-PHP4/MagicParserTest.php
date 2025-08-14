<?php
  require("MagicParser.o");

  function myRecordHandler($record)
  {
    print_r($record);
  }
  
  MagicParser_parse("MagicParserTest.xml","myRecordHandler");
?> 
