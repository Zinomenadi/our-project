<?php include "databaseobject.class.php" ?>
<?php

  class Medecin extends DatabaseObject {

      static public $table_name='boutique';
      static public $db_columns= ['IDB' , 'IDV', 'NOMB', 'LOCALB'];
     //protected $pass_required = true;

      public function ajouter() {
        $this->validate(); 
        if(!empty($this->errors)) { return false; }

        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . static::$table_name . " (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        $result = self::$database->query($sql);
        if($result) {
          $this->idb = self::$database->insert_id;
        }
        return $result;
      }
      public function update() {
      /*  if($this->pass != '') {
      $this->pass_required = false;
        } */
        $this->validate();
        if(!empty($this->errors)) { return false; }

        $attributes = $this->sanitized_attributes();
        $attribute_pairs = [];
        foreach($attributes as $key => $value) {
          $attribute_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " . static::$table_name . " SET ";
        $sql .= join(', ', $attribute_pairs);
        $sql .= " WHERE " .static::$db_columns['0']. " = '"  . self::$database->escape_string($this->idb) . "' ";
        $sql .= "LIMIT 1";
        $result = self::$database->query($sql);
        return $result;
      }
     /* public function save() {
        // A new record will not have an ID yet
        if(isset($this->id_medecin)) {
          return $this->update();
        } else {
          return $this->create();
        }
      }  */

     public function delete() {
       $sql = "DELETE FROM " . static::$table_name . " ";
       $sql .= "WHERE  " .static::$db_columns['0']. " = '" . self::$database->escape_string($this->idb) . "' ";
       $sql .= "LIMIT 1";
       $result = self::$database->query($sql);
       return $result;
     }
    /* static public function find_by_email($email) {
       $sql = "SELECT * FROM " . static::$table_name . " ";
       $sql .= "WHERE email='" . self::$database->escape_string($email) . "'";
       $obj_array = static::find_by_sql($sql);
       if(!empty($obj_array)) {
         return array_shift($obj_array);
       } else {
         return false;
       }
     }  */

    public $idb;
    public $idv;
    public $nomb;
    public $localb;
   
    function __construct($args=[])
    {
      $this->idv=$args['idv'] ?? '';
      $this->nomb=$args['nomb'] ?? '';
      $this->localb=$args['localb'] ?? '';
      
    }
    public function name(){
      return "{$this->idb} - {$this->nomb} - {$this->localb}";
    }
    public function validate() {
      $this->errors = [];


      if(is_blank($this->nomb)) {
        $this->errors[] = "Nom ne peut pas etre vide.";
      }
      if(is_blank($this->localb)) {
        $this->errors[] = "Prenom ne peut pas etre vide.";
      }

      
      return $this->errors;
    }

  }


 ?>
