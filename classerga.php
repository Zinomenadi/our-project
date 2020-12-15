<?php include "databaseobject.class.php" ?>
<?php

  class Classerga extends DatabaseObject {

      static public $table_name='classerga';
      static public $db_columns= ['IDGA' , 'TAILLE', 'NBA'];
     // protected $pass_required = true;

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
         
        }
        return $result;
      }
      public function update() {
    /*    if($this->pass != '') {
      $this->pass_required = false;
        }*/
        $this->validate();
        if(!empty($this->errors)) { return false; }

        $attributes = $this->sanitized_attributes();
        $attribute_pairs = [];
        foreach($attributes as $key => $value) {
          $attribute_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " . static::$table_name . " SET ";
        $sql .= join(', ', $attribute_pairs);
        $sql .= " WHERE " .static::$db_columns['0']. " = '"  . self::$database->escape_string($this->idga) . "' ";
        $sql .= " AND ".static::$db_columns['1']. " = '"  . self::$database->escape_string($this->taille) . "' ";
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
       $sql .= "WHERE  " .static::$db_columns['0']. " = '" . self::$database->escape_string($this->idga) . "' ";
       $sql .= " AND ".static::$db_columns['1']. " = '"  . self::$database->escape_string($this->taille) . "' ";
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

    public $idga;
    public $taille;
    public $nba;
   
    function __construct($args=[])
    {
      $this->idga=$args['taille'] ?? '';
      $this->taille=$args['taille'] ?? '';
      $this->nba=$args['nba'] ?? '';
      
    }
    /*public function name(){
      return "{$this->id_medecin} - {$this->nom_medecin} {$this->prenom_medecin}";
    }  */
    public function validate() {
      $this->errors = [];


     if(is_blank($this->nba)) {
        $this->errors[] = "Nom ne peut pas etre vide.";
      }
      /*
      if(is_blank($this->prenom_medecin)) {
        $this->errors[] = "Prenom ne peut pas etre vide.";
      }
      if(is_blank($this->specialite)) {
          $this->errors[] = "la specialite ne peut pas etre vide.";
        }
        if(is_blank($this->email)) {
          $this->errors[] = "Email ne peut pas etre vide.";
        } elseif (!has_length($this->email, array('max' => 255))) {
          $this->errors[] = "l'email doit avoir moin de 255 characters.";
        } elseif (!has_valid_email_format($this->email)) {
          $this->errors[] = "Email doit avoir un format valide.";
          $this->errors[] = "email existe deja . veuiller le changer.";
        }

        if($this->pass_required) {
          if(is_blank($this->pass)) {
            $this->errors[] = "Password cannot be blank.";
          } elseif (!has_length($this->pass, array('min' => 12))) {
            $this->errors[] = "Password must contain 12 or more characters";
          } elseif (!preg_match('/[A-Z]/', $this->pass)) {
            $this->errors[] = "Password must contain at least 1 uppercase letter";
          } elseif (!preg_match('/[a-z]/', $this->pass)) {
            $this->errors[] = "Password must contain at least 1 lowercase letter";
          } elseif (!preg_match('/[0-9]/', $this->pass)) {
            $this->errors[] = "Password must contain at least 1 number";
          } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->pass)) {
            $this->errors[] = "Password must contain at least 1 symbol";
          }
        }
        */
      return $this->errors;
    }



  }


 ?>
