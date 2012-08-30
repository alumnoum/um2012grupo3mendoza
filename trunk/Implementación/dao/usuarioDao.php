<?php

class usuarioDao {
 
 /**
     * createValueObject-method. This method is used when the Dao class needs
     * to create new value object instance. The reason why this method exists
     * is that sometimes the programmer may want to extend also the valueObject
     * and then this method can be overrided to return extended valueObject.
     * NOTE: If you extend the valueObject class, make sure to override the
     * clone() method in it!
     */
    function createValueObject() {
          return new usuarioDao();
    }


    /**
     * getObject-method. This will create and load valueObject contents from database 
     * using given Primary-Key as identifier. This method is just a convenience method 
     * for the real load-method which accepts the valueObject as a parameter. Returned
     * valueObject will be created using the createValueObject() method.
     */
    function getObject($conn, $id) {

          $valueObject = $this->createValueObject();
          $valueObject->setId($id);
          $this->load($conn, $valueObject);
          return $valueObject;
    }


    /**
     * load-method. This will load valueObject contents from database using
     * Primary-Key as identifier. Upper layer should use this so that valueObject
     * instance is created and only primary-key should be specified. Then call
     * this method to complete other persistent information. This method will
     * overwrite all other fields except primary-key and possible runtime variables.
     * If load can not find matching row, NotFoundException will be thrown.
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance to be loaded.
     *                     Primary-key field must be set for this to work properly.
     */
    function load($conn, &$valueObject) {

          if (!$valueObject->getId()) {
               //print "Can not select without Primary-Key!";
               return false;
          }

          $sql = "SELECT * FROM usuarios WHERE (id = ".$valueObject->getId().") "; 

          if ($this->singleQuery($conn, $sql, $valueObject))
               return true;
          else
               return false;
    }


    /**
     * LoadAll-method. This will read all contents from database table and
     * build an Vector containing valueObjects. Please note, that this method
     * will consume huge amounts of resources if table has lot's of rows. 
     * This should only be used when target tables have only small amounts
     * of data.
     *
     * @param conn         This method requires working database connection.
     */
    function loadAll($conn) {


          $sql = "SELECT * FROM usuarios ORDER BY id ASC ";

          $searchResults = $this->listQuery($conn, $sql);

          return $searchResults;
    }
    
    
    function busqueda($conn,$query){
    	$sql = "SELECT id, nombre, apellido, email FROM  `usuarios` 
    			WHERE  
    			`nombre` LIKE  '%".$query."%' OR  
    			`apellido` LIKE  '%".$query."%'  OR  
    			`email` LIKE  '%".$query."%'  OR  
    			`ciudad` LIKE  '%".$query."%' 
    			LIMIT 0 , 30";
    		
    	$searchResults = array();

    	$result = $conn->execute($sql);
    		
    	while ($row = $conn->nextRow($result)) {
    	
    		$temp = new usuarioModel();
    			
    		$temp->setId($row[0]);
    		$temp->setNombre($row[1]);
    		$temp->setApellido($row[2]);
    		$temp->setEmail($row[3]);
    	
    		array_push($searchResults, $temp);
    	}
    	return $searchResults;
    }
    
    function checkLogin($conn,$usuario) {
    
    
    	$sql = "SELECT id, nombre, email FROM usuarios where email = '".$usuario->email."' and contrasena='".$usuario->contrasena."'";
    	
    	$searchResults = array();
    	$result = $conn->execute($sql);
    	
    	if($row = $conn->nextRow($result)){
    		$usuario->id = $row[0];
    		$usuario->nombre = $row[1];
    		$usuario->email = $row[2];
    		return $usuario;
    	}
    	    		    	
    	return false;
    }

    function buscaAmigos($conn,$idUser) {
    
    
    	$sql = "SELECT * FROM relaciones WHERE id='".$idUser."' OR idbis='".$idUser."'";

 
    	$amigos = array();

    	$result = $conn->execute($sql);
    	
    	while ($row = $conn->nextRow($result)) {
    		 
    		$id = $row[0];
    		$idbis = $row[1];
    		 
    		if($id == $idUser)
    			array_push($amigos, $idbis);
			else
    			array_push($amigos, $id);
    	}
    	
    	
    	$ids = implode(',', $amigos);
    	
    	$sql = "SELECT id, nombre, email FROM usuarios WHERE id IN ($ids)";

    	if(strlen($ids)>0){

    	$result = $conn->execute($sql);
    	
	    	$amigos=NULL;
	    	$amigos = array();
	    	
	    	while ($row = $conn->nextRow($result)) {	 
	    		$id = $row[0];
	    		$nombre = $row[1];
	    		$email = $row[2];
	    		array_push($amigos, array('id' => $id, 'nombre' => $nombre, 'email' => $email));
	    	}
	    	return $amigos;
    	}
    	
	    return false;
    }    

    /**
     * create-method. This will create new row in database according to supplied
     * valueObject contents. Make sure that values for all NOT NULL columns are
     * correctly specified. Also, if this table does not use automatic surrogate-keys
     * the primary-key must be specified. After INSERT command this method will 
     * read the generated primary-key back to valueObject if automatic surrogate-keys
     * were used. 
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance to be created.
     *                     If automatic surrogate-keys are not used the Primary-key 
     *                     field must be set for this to work properly.
     */
    function create($conn, &$valueObject) {

          $sql = "INSERT INTO usuarios (nombre, apellido, ";
          $sql = $sql."direccion, telefono, email, ";
          $sql = $sql."ciudad, pais, contrasena) VALUES ( ";
          $sql = $sql."'".$valueObject->getNombre()."', ";
          $sql = $sql."'".$valueObject->getApellido()."', ";
          $sql = $sql."'".$valueObject->getDireccion()."', ";
          $sql = $sql."'".$valueObject->getTelefono()."', ";
          $sql = $sql."'".$valueObject->getEmail()."', ";
          $sql = $sql."'".$valueObject->getCiudad()."', ";
          $sql = $sql."'".$valueObject->getPais()."', ";
          $sql = $sql."'".$valueObject->getContrasena()."') ";
          $result = $this->databaseUpdate($conn, $sql);

          return true;
    }


    /**
     * save-method. This method will save the current state of valueObject to database.
     * Save can not be used to create new instances in database, so upper layer must
     * make sure that the primary-key is correctly specified. Primary-key will indicate
     * which instance is going to be updated in database. If save can not find matching 
     * row, NotFoundException will be thrown.
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance to be saved.
     *                     Primary-key field must be set for this to work properly.
     */
    function save($conn, &$valueObject) {

          $sql = "UPDATE usuarios SET nombre = '".$valueObject->getNombre()."', ";
          $sql = $sql."apellido = '".$valueObject->getApellido()."', ";
          $sql = $sql."direccion = '".$valueObject->getDireccion()."', ";
          $sql = $sql."telefono = '".$valueObject->getTelefono()."', ";
          $sql = $sql."email = '".$valueObject->getEmail()."', ";
          $sql = $sql."ciudad = '".$valueObject->getCiudad()."', ";
          $sql = $sql."pais = '".$valueObject->getPais()."', ";
          $sql = $sql."contrasena = '".$valueObject->getContrasena()."'";
          $sql = $sql." WHERE (id = ".$valueObject->getId().") ";
          $result = $this->databaseUpdate($conn, $sql);

          if ($result != 1) {
               //print "PrimaryKey Error when updating DB!";
               return false;
          }

          return true;
    }


    /**
     * delete-method. This method will remove the information from database as identified by
     * by primary-key in supplied valueObject. Once valueObject has been deleted it can not 
     * be restored by calling save. Restoring can only be done using create method but if 
     * database is using automatic surrogate-keys, the resulting object will have different 
     * primary-key than what it was in the deleted object. If delete can not find matching row,
     * NotFoundException will be thrown.
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance to be deleted.
     *                     Primary-key field must be set for this to work properly.
     */
    function delete($conn, &$valueObject) {


          if (!$valueObject->getId()) {
               //print "Can not delete without Primary-Key!";
               return false;
          }

          $sql = "DELETE FROM usuarios WHERE (id = ".$valueObject->getId().") ";
          $result = $this->databaseUpdate($conn, $sql);

          if ($result != 1) {
               //print "PrimaryKey Error when updating DB!";
               return false;
          }
          return true;
    }

    function invitarAmigo($conn,$id){
    	
    	$sql = "INSERT INTO  `facebook`.`invitaciones` (`invita` ,`invitado`)	
    			VALUES ('".Session::get('id')."',  '$id')";
    					
    	$result = $this->databaseUpdate($conn, $sql);
    			 
    	if ($result != 1) 
    		return false;
    			
    	return true;    	
    }
    
    function eliminarAmigo($conn, $idAmigo){
    	
    	
    	$idUser = Session::get('id');
    	
    	$sql = "DELETE FROM relaciones WHERE (id='".$idUser."' AND idbis='".$idAmigo."') OR  (id='".$idAmigo."' AND idbis='".$idUser."')";
    	$result = $this->databaseUpdate($conn, $sql);
    	
    	if ($result != 1) {
    		return false;
    	}
    	return true;
    }
    

    /**
     * deleteAll-method. This method will remove all information from the table that matches
     * this Dao and ValueObject couple. This should be the most efficient way to clear table.
     * Once deleteAll has been called, no valueObject that has been created before can be 
     * restored by calling save. Restoring can only be done using create method but if database 
     * is using automatic surrogate-keys, the resulting object will have different primary-key 
     * than what it was in the deleted object. (Note, the implementation of this method should
     * be different with different DB backends.)
     *
     * @param conn         This method requires working database connection.
     */
    function deleteAll($conn) {

          $sql = "DELETE FROM usuarios";
          $result = $this->databaseUpdate($conn, $sql);

          return true;
    }


    /**
     * coutAll-method. This method will return the number of all rows from table that matches
     * this Dao. The implementation will simply execute "select count(primarykey) from table".
     * If table is empty, the return value is 0. This method should be used before calling
     * loadAll, to make sure table has not too many rows.
     *
     * @param conn         This method requires working database connection.
     */
    function countAll($conn) {

          $sql = "SELECT count(*) FROM usuarios";
          $allRows = 0;

          $result = $conn->execute($sql);

          if ($row = $conn->nextRow($result))
                $allRows = $row[0];

          return $allRows;
    }


    /** 
     * searchMatching-Method. This method provides searching capability to 
     * get matching valueObjects from database. It works by searching all 
     * objects that match permanent instance variables of given object.
     * Upper layer should use this by setting some parameters in valueObject
     * and then  call searchMatching. The result will be 0-N objects in vector, 
     * all matching those criteria you specified. Those instance-variables that
     * have NULL values are excluded in search-criteria.
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance where search will be based.
     *                     Primary-key field should not be set.
     */
    function searchMatching($conn, &$valueObject) {

          $first = true;
          $sql = "SELECT * FROM usuarios WHERE 1=1 ";

          if ($valueObject->getId() != 0) {
              if ($first) { $first = false; }
              $sql = $sql."AND id = ".$valueObject->getId()." ";
          }

          if ($valueObject->getNombre() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND nombre LIKE '".$valueObject->getNombre()."%' ";
          }

          if ($valueObject->getApellido() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND apellido LIKE '".$valueObject->getApellido()."%' ";
          }

          if ($valueObject->getDireccion() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND direccion LIKE '".$valueObject->getDireccion()."%' ";
          }

          if ($valueObject->getTelefono() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND telefono LIKE '".$valueObject->getTelefono()."%' ";
          }

          if ($valueObject->getEmail() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND email LIKE '".$valueObject->getEmail()."%' ";
          }

          if ($valueObject->getCiudad() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND ciudad LIKE '".$valueObject->getCiudad()."%' ";
          }

          if ($valueObject->getPais() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND pais LIKE '".$valueObject->getPais()."%' ";
          }

          if ($valueObject->getContrasena() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND contrasena LIKE '".$valueObject->getContrasena()."%' ";
          }


          $sql = $sql."ORDER BY id ASC ";

          // Prevent accidential full table results.
          // Use loadAll if all rows must be returned.
          if ($first)
               return array();

          $searchResults = $this->listQuery($conn, $sql);

          echo '<pre>';
          print_r($searchResults);
          exit();
          
          return $searchResults;
    }


    /** 
     * getDaogenVersion will return information about
     * generator which created these sources.
     */
    function getDaogenVersion() {
        return "DaoGen version 2.4.1";
    }


    /**
     * databaseUpdate-method. This method is a helper method for internal use. It will execute
     * all database handling that will change the information in tables. SELECT queries will
     * not be executed here however. The return value indicates how many rows were affected.
     * This method will also make sure that if cache is used, it will reset when data changes.
     *
     * @param conn         This method requires working database connection.
     * @param stmt         This parameter contains the SQL statement to be excuted.
     */
    function databaseUpdate($conn, &$sql) {

          $result = $conn->execute($sql);

          return $result;
    }



    /**
     * databaseQuery-method. This method is a helper method for internal use. It will execute
     * all database queries that will return only one row. The resultset will be converted
     * to valueObject. If no rows were found, NotFoundException will be thrown.
     *
     * @param conn         This method requires working database connection.
     * @param stmt         This parameter contains the SQL statement to be excuted.
     * @param valueObject  Class-instance where resulting data will be stored.
     */
    function singleQuery($conn, &$sql, &$valueObject) {

          $result = $conn->execute($sql);

          if ($row = $conn->nextRow($result)) {

                   $valueObject->setId($row[0]); 
                   $valueObject->setNombre($row[1]); 
                   $valueObject->setApellido($row[2]); 
                   $valueObject->setDireccion($row[3]); 
                   $valueObject->setTelefono($row[4]); 
                   $valueObject->setEmail($row[5]); 
                   $valueObject->setCiudad($row[6]); 
                   $valueObject->setPais($row[7]); 
                   $valueObject->setContrasena($row[8]); 
          } else {
               //print " Object Not Found!";
               return false;
          }
          return true;
    }


    /**
     * databaseQuery-method. This method is a helper method for internal use. It will execute
     * all database queries that will return multiple rows. The resultset will be converted
     * to the List of valueObjects. If no rows were found, an empty List will be returned.
     *
     * @param conn         This method requires working database connection.
     * @param stmt         This parameter contains the SQL statement to be excuted.
     */
    function listQuery($conn, &$sql) {

          $searchResults = array();
          $result = $conn->execute($sql);

          while ($row = $conn->nextRow($result)) {
               $temp = $this->createValueObject();

               $temp->setNombre($row[0]); 
               $temp->setApellido($row[1]); 
               $temp->setDireccion($row[2]); 
               $temp->setTelefono($row[3]); 
               $temp->setEmail($row[4]); 
               $temp->setCiudad($row[5]); 
               $temp->setPais($row[6]); 
               $temp->setContrasena($row[7]); 
               array_push($searchResults, $temp);
          }

          return $searchResults;
    }
}

?>
