<?php include "databaseobject.class.php" ?>
<?php

  class Cat_article extends DatabaseObject {

      static public $table_name='Cat_article';
      static public $db_columns= ['CAT'];
    

      public $cat;

    function __construct($args=[])
    {
      $this->cat=$args['cat'] ?? ''; 
    }
    
    
  }


 ?>
