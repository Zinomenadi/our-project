<?php

class DatabaseObject {

  static protected $database;
  static protected $table_name = "";
  static protected $db_columns = [];
  public $errors = [];

  static public function set_database($database) {
    self::$database = $database;
  }        
  //appliquer un requete sur une base de données 
  static public function query($sql){
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    return $result;


  }

  //transformer la requete $sql sous forme tableau des objets
  static public function find_by_sql($sql) {
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }

    // results into objects
    $object_array = [];
    while($record = $result->fetch_assoc()) {
      $object_array[] = static::instantiate($record);
    }

    $result->free();

    return $object_array;
  }
  // retourner toute la table(table name) de base base de donnes (table name) sous forme un tableau des objets donc si on a [id:1 ,nom: ,pre][id:2 ,nom: ,prenom:] ... 
  static public function find_all() {
    $sql = "SELECT * FROM " . static::$table_name;
    return static::find_by_sql($sql);
  }
  // retourner la ligne de id=$id de "table name " sous forme un tableau d'objets qui est un objet
  static public function find_by_id($id) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE " .static::$db_columns['0']. " = '" . self::$database->escape_string($id) . "'";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  //transoformer une ligne de la BD sous forme objet
  static protected function instantiate($record) {
    $object = new static;
    // Could manually assign values to properties
    // but automatically assignment is easier and re-usable
    foreach($record as $property => $value) {
      if(property_exists($object, $property)) {
        $object->$property = $value;
      }
    }
    return $object;
  }
 //return un tableau d'erreurs 
  public function validate() {
    $this->errors = [];

    // Add custom validations

    return $this->errors;
  }

  public function ajouter() {
    $this->validate();
    if(!empty($this->errors)) { return false; }

    $attributes = $this->sanitized_attributes();                      // return un tableux des colone nomcl prenomcl ......
    $sql = "INSERT INTO " . static::$table_name . " (";// insert into article(ida,....) values ()
    $sql .= join(', ', array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));  //arrays values return un tableux des valeur de colone
    $sql .= "')";
    $result = self::$database->query($sql);
    if($result) {
      $this->IDCL = self::$database->insert_id; // insert_id:retourner la valuer d'id de de derniiere requeute Exécuté dans  database
    }
    return $result;
  }


  public function update() {
    $this->validate();
    if(!empty($this->errors)) { return false; }

    $attributes = $this->sanitized_attributes();
    $attribute_pairs = [];
    foreach($attributes as $key => $value) {
      $attribute_pairs[] = "{$key}='{$value}'";
    }

    $sql = "UPDATE " . static::$table_name . " SET ";
    $sql .= join(', ', $attribute_pairs);
    $sql .= " WHERE " .static::$db_columns['0']. " = '"  . self::$database->escape_string($this->IDCL) . "' ";
    $sql .= "LIMIT 1";
    $result = self::$database->query($sql);
    return $result;
  }

  //???????????
  public function save() {
    // A new record will not have an ID yet
    if(isset($this->IDCL) {
      return $this->update();
    } else {
      return $this->create();
    }
  }
// pour lié les attributs et valeurs entré dans les formulaire notre site
  public function merge_attributes($args=[]) {
    foreach($args as $key => $value) {
      if(property_exists($this, $key) && !is_null($value)) {
        $this->$key = $value;
      }
    }
  }

  // Properties which have database columns, excluding ID

  // les proprietés qui ont une column de la table a par l'id de cette table
  // retourner un tablea des attributs de la table table name 
  //retoutner les colones de table name sous forme un tableaux
  public function attributes() {
    $attributes = [];

    foreach(static::$db_columns as $column) {
      if($column == static::$db_columns['0']) { continue; }
      $attributes[$column] = $this->$column;
    }
    return $attributes;
  }
// pour securisé les valeur entré (tableau des attributs de la table table name)
  protected function sanitized_attributes() {
    $sanitized = [];
    foreach($this->attributes() as $key => $value) {
      $sanitized[$key] = self::$database->escape_string($value);
    }
    return $sanitized;
  }

  public function delete() {
    $sql = "DELETE FROM " . static::$table_name . " ";
    $sql .= "WHERE  " .static::$db_columns['0']. " = '" . self::$database->escape_string($this->IDCL) . "' ";
    $sql .= "LIMIT 1";
    $result = self::$database->query($sql);
    return $result;

    // After deleting, the instance of the object will still
    // exist, even though the database record does not.
    // This can be useful, as in:
    //   echo $user->first_name . " was deleted.";
    // but, for example, we can't call $user->update() after
    // calling $user->delete().
  }

  static public function count_all_clients() {
    $sql = "SELECT COUNT(*) as total FROM client " ;
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    $row = $result->fetch_array();
    return array_shift($row);
  }
  static public function count_all_vendeurs() {
    $sql = "SELECT COUNT(*) as total FROM vendeur " ;
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    $row = $result->fetch_array();
    return array_shift($row);
  }

  static public function count_all_boutiques() {
    $sql = "SELECT COUNT(*) as total FROM boutique " ;
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    $row = $result->fetch_array();
    return array_shift($row);
  }

  static public function count_all_livraisons() {
    $sql = "SELECT COUNT(*) as total FROM livraison " ;
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    $row = $result->fetch_array();
    return array_shift($row);
  }


  static public function count_all_articles() {
    $sql = "SELECT COUNT(*) as total FROM article " ;
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    $row = $result->fetch_array();
    return array_shift($row);
  }

  

  /*static public function find_all_patient() {
    $sql = "SELECT * FROM " . static::$table_name;
    $sql .=" INNER JOIN patient ";
    $sql .="ON ".static::$table_name.".id_patient = " . "patient.id_patient ORDER BY date_rendez_vous ASC,heure_d ASC";
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    return $result;
  } */

  static public function all_article() {
    $sql = "SELECT * FROM article " ;
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    return $result;
  }
  static public function all_client() {
    $sql = "SELECT * FROM " . "client";
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    return $result;
  }

  static public function all_boutique() {
    $sql = "SELECT * FROM boutique" ;
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    return $result;
  }

  static public function all_vendeur() {
    $sql = "SELECT * FROM " . "vendeur";
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
        return $result;
  }

   /*
  //retourner une requete de jointure entre dossiers et patient , les patients ordoner selon le nom
  static public function find_all_dossier_patient_nom() {
    $sql = "SELECT * FROM dossier";
    $sql .=" INNER JOIN patient ";
    $sql .="ON dossier.id_patient = patient.id_patient ORDER BY nom_patient ASC";
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    return $result;
  }

  //retourner une requete de jointure entre dossiers et patient , les patients ordoner selon date de creation
  static public function find_all_dossier_patient_date() {
    $sql = "SELECT * FROM dossier";
    $sql .=" INNER JOIN patient ";
    $sql .="ON dossier.id_patient = patient.id_patient ORDER BY nom_patient ASC ,date_de_creation_dossier DESC";
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    return $result;
  }

  //retourner une requete de jointure entre dossiers et patient, diagnostique 
  static public function find_all_dossier_patient_diagnostique_medecin() {
    $sql = "SELECT * FROM patient";
    $sql .=" INNER JOIN dossier ";
    $sql .="ON patient.id_patient = dossier.id_patient ";
    $sql .="INNER JOIN diagnostique ";
    $sql .="ON dossier.id_dossier = diagnostique.id_dossier ";
    $sql .="INNER JOIN medecin ";
    $sql .="ON diagnostique.id_medecin = medecin.id_medecin";
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    return $result;
  }

  // recherce dans la table table name avec email et fait de depiler les elements et retourner
  static public function find_by_email($email) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE email='" . self::$database->escape_string($email) . "'";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return array_shift($obj_array); //depiler et retourner
    } else {
      return false;
    }
  }
  */

  public function escap_s($val){
    $val = self::$database->escape_string($val);
    return  $val;

  }
  
  

}

?>
