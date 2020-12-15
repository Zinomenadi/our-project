<?php include "databaseobject.class.php" ?>
<?php

  class Client extends DatabaseObject {

      static public $table_name='client';
      static public $db_columns= ['IDCL' , 'NOMCL', 'PRENOMCL', 'TELCL' , 'DATENCL' , 'SEXECL',
      'EMAILCL','LOCALCL','PSEUDOCL','MOTPASSECL'];
      protected $pass_required = true;

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
          $this->idcl = self::$database->insert_id;
        }
        return $result;
      }
      public function update() {
        if($this->motpassecl != '') {
      $this->pass_required = false;
        }
        $this->validate();
        if(!empty($this->errors)) { return false; }

        $attributes = $this->sanitized_attributes();
        $attribute_pairs = [];
        foreach($attributes as $key => $value) { //key nom de colone values c'est la valeur de la colone 
          $attribute_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " . static::$table_name . " SET ";
        $sql .= join(', ', $attribute_pairs);
        $sql .= " WHERE " .static::$db_columns['0']. " = '"  . self::$database->escape_string($this->idcl) . "' "; // en prend pas le id en commence par 1eme colone
        $sql .= "LIMIT 1"; // aux max update 1 seul ligne
        $result = self::$database->query($sql);
        return $result;
      }
     /* public function save() {
        // A new record will not have an ID yet
        if(isset($this->IDCL)) {
          return $this->update();
        } else {
          return $this->create();
        }
      }*/

     public function delete() {
       $sql = "DELETE FROM " . static::$table_name . " ";
       $sql .= "WHERE  " .static::$db_columns['0']. " = '" . self::$database->escape_string($this->idcl) . "' ";
       $sql .= "LIMIT 1";
       $result = self::$database->query($sql);
       return $result;
     }
     static public function find_by_email($email) {
       $sql = "SELECT * FROM " . static::$table_name . " ";
       $sql .= "WHERE EMAILCL='" . self::$database->escape_string($email) . "'";
       $obj_array = static::find_by_sql($sql);
       if(!empty($obj_array)) {
         return array_shift($obj_array);
       } else {
         return false;
       }
     }

    public $idcl;
    public $nomcl;
    public $prenomcl;
    public $telcl;
    public $datencl;
    public $sexecl;
    public $emailcl;
    public $localcl;
    public $pseudocl;
    public $motpassecl;
    public $imagecl;

    function __construct($args=[])
    {
      
      $this->nomcl=$args['nomcl'] ?? ''; ////arg['nomcl'] = zino   les information de formulaire (par ex nta ktebt fe formulaire nom zino)
      $this->prenomcl=$args['prenomcl'] ?? '';
      $this->telcl=$args['telcl'] ?? '';
      $this->sexecl=$args['sexecl'] ?? '';
      $this->emailcl=$args['emailcl'] ?? '';
      // $this->image=$args['image'] ?? '';
      $this->localcl=$args['localcl'] ?? '';
      $this->pseudocl=$args['pseudocl'] ?? '';
      $this->motpassecl=$args['motpassecl'] ?? '';
    }
    public function name(){
      return "{$this->idcl} - {$this->nomcl}- {$this->prenomcl}";
    }
    public function validate() {
      $this->errors = [];


      if(is_blank($this->nomcl)) {
        $this->errors[] = "Nom ne peut pas etre vide.";
      }
      if(is_blank($this->prenomcl)) {
        $this->errors[] = "Prenom ne peut pas etre vide.";
      }
      if(is_blank($this->telcl)) {
          $this->errors[] = "la specialite ne peut pas etre vide.";
        }
        if(is_blank($this->emailcl)) {
          $this->errors[] = "Email ne peut pas etre vide.";
        } elseif (!has_length($this->email, array('max' => 255))) {
          $this->errors[] = "l'email doit avoir moin de 255 characters.";
        } elseif (!has_valid_email_format($this->email)) {
          $this->errors[] = "Email doit avoir un format valide.";
          $this->errors[] = "email existe deja . veuiller le changer.";
        }

        if($this->pass_required) {
          if(is_blank($this->motpassecl)) {
            $this->errors[] = "Password cannot be blank.";
          } elseif (!has_length($this->motpassecl, array('min' => 12))) {
            $this->errors[] = "Password must contain 12 or more characters";
          } elseif (!preg_match('/[A-Z]/', $this->motpassecl)) {
            $this->errors[] = "Password must contain at least 1 uppercase letter";
          } elseif (!preg_match('/[a-z]/', $this->motpassecl)) {
            $this->errors[] = "Password must contain at least 1 lowercase letter";
          } elseif (!preg_match('/[0-9]/', $this->motpassecl)) {
            $this->errors[] = "Password must contain at least 1 number";
          } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->motpassecl)) {
            $this->errors[] = "Password must contain at least 1 symbol";
          }
        }
      return $this->errors;
    }

  }


 ?>
