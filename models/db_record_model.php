<?php
/**
 *
 * Represents an Active Record Model
 *
 * @author Cari
 *
 */

class DbRecordModel {
    
    protected $db;
    protected static 
        $db_pk = 'id',
        $sql_order_by_allowed_values = array('id'),
        $sql_direction_allowed_values = array('DESC','ASC');
    
    protected function __construct(){
        $this->db = Db::getInstance();
    }
    
    
    /**
     * sets an objects propertys using an array
     * @param mixed $array - property_name => value
     * @throws ErrorException
     * @return get_called_class()[]
     */
    public function setPropValues($array){
        if(is_array($array)){
            foreach($array as $key => $value){
                if(property_exists(get_called_class(),$key)){
                    $this->{$key} = $value;
                }
            }
            return $this;
        }else{
            throw new ErrorException('argument is not an array');
        }
    }
    
    /**
     * build sql parts based on array values
     * @param string $part
     * @param mixed[] $arg_array
     * @throws ErrorException
     * @return array[string 'query_part', mixed[] 'sql_params_arr']
     */
    protected static function buildSqlPart($part = 'where', $arg_array){
        $parts_implemented = array('where','order_by');
        
        if(in_array($part, $parts_implemented)){
            
            $sql_part = '';
            $sql_params_arr = array();
            
            switch($part){
                case 'where':
                    $sql_part = ' WHERE ';
                    foreach($arg_array as $field => $value){
                        $placeholder = ':'.$field;
                        $sql_part.= $field.' = '.$placeholder.' AND ';
                        $sql_params_arr[$placeholder] = $value;
                    }
                    $sql_part = substr($sql_part, 0, -5);
                    break;
                case 'order_by' :
                    $sql_part = ' ORDER BY ';
                    foreach($arg_array as $field => $direction){
                        if(in_array($field, static::$sql_order_by_allowed_values) AND in_array($direction, static::$sql_direction_allowed_values)){
                            $sql_part.= $field.' '.$direction.', ';
                        }
                    }
                    $sql_part = substr($sql_part, 0, -2);
                    break;
            }
            //die(var_dump(array('query_part'=>$sql_part, 'param_array'=>$sql_params_arr)));
            return array('query_part'=>$sql_part, 'param_array'=>$sql_params_arr);
            
        }else{
            throw new ErrorException('defined part is not implemented');
        }
    }
    
    
    
    /**
     * get record from database using pk
     * @param mixed $pk - pk is usually an integer
     * @throws ErrorException
     * @return get_called_class()|NULL
     */
    public static function getByPk($pk){
        $record = NULL;
        
        if(isset(static::$db_table) AND isset(static::$db_pk)){
            
            if(!is_array(static::$db_pk)){
                $sql_where = ' WHERE '.static::$db_pk.' = :pk';
                $sql_arguments = [':pk'=>$pk];
            }else{
                foreach(static::$db_pk as $pk_name){
                    if(!array_key_exists($pk_name, $pk)){
                        throw new ErrorException('given pk keys do not match specified pk names');
                    }
                }
                $sql_part = self::buildSqlPart('where',$pk);
                $sql_where = $sql_part['query_part'];
                $sql_arguments = $sql_part['param_array'];
            }
            
            
            
            $db = Db::getInstance();
            $sql = 'SELECT * FROM '.static::$db_table.$sql_where;
            $req = $db->prepare($sql);
            
            if($req->execute($sql_arguments)) {
                if($req->rowCount() == 1) {
                    $record = $req->fetchObject(get_called_class());
                }
            }
            
        }else{
            throw new ErrorException('db table or pk is not set!');
        }
        
        return $record;
    }
    
    /**
     * get a record using a unique key
     * @param string $field
     * @param mixed $value
     * @throws ErrorException
     * @return get_called_class()|NULL
     */
    public static function getByUniqueKey($field,$value){
        $record = NULL;
        
        if(isset(static::$db_table)){
            
            $db = Db::getInstance();
            $sql = 'SELECT * FROM '.static::$db_table.' WHERE '.$field.' = :value';
            $req = $db->prepare($sql);
            
            if($req->execute(array(':value' => $value))) {
                if($req->rowCount() == 1) {
                    $record = $req->fetchObject(get_called_class());
                }
            }
            
        }else{
            throw new ErrorException('db table is not set');
        }
        
        return $record;
    }
    
    /**
     * Get all records of table
     * @param mixed[] format: field=>direction[ASC|DESC]
     * @throws ErrorException
     * @return get_called_class()[]
    */
     
    public static function getAll($order_settings=null,$limit=null){
        $records = array();
        if(isset(static::$db_table)){
            $db = Db::getInstance();
            
            $sql = 'SELECT * FROM '.static::$db_table;
            
            if(!is_null($order_settings) AND is_array($order_settings) AND count($order_settings) > 0){
                    // add order clause to statement
                    $sql.= ' ORDER BY ';
                    foreach($order_settings as $field => $direction){
                        if(in_array($field, static::$sql_order_by_allowed_values) AND in_array($direction, static::$sql_direction_allowed_values)){
                            $sql.= $field.' '.$direction.', ';
                        }
                    }
                    $sql = substr($sql, 0, -2);
            }
            
            if(!is_null($limit) AND !is_array($limit)){
                $sql.= ' LIMIT '.intval($limit);
            }
            
            $req = $db->query($sql);
            if($req->rowCount() > 0){
                foreach($req->fetchALL(PDO::FETCH_CLASS,get_called_class()) as $record){
                    $records[] = $record;
                }
            }
        }else{
            throw new ErrorException('db table is not set');
        }
        
        return $records;
    }
    
    /**
     * get records with defined where condition
     * @param string $condition
     * @param string $order_field
     * @param string $direction
     * @throws ErrorException
     * @return get_called_class()[]
     */
    public static function getWhere($condition,$order_settings=null){
                    
        $records = array();
        if(isset(static::$db_table)){
            $db = Db::getInstance();
            
            $sql = 'SELECT * FROM '.static::$db_table.' WHERE '.$condition;
            //echo $sql;
            
            if(!is_null($order_settings) AND is_array($order_settings) AND count($order_settings) > 0){
                // add order clause to statement
                $sql.= ' ORDER BY  ';
                foreach($order_settings as $field => $direction){
                    if(in_array($field, static::$sql_order_by_allowed_values) AND in_array($direction, static::$sql_direction_allowed_values)){
                        $sql.= $field.' '.$direction.', ';
                    }else{
                        throw new ErrorException('the given field and order pair is not valid');
                    }
                }
                $sql = substr($sql, 0, -2);
            }
            
            $req = $db->prepare($sql);
            if($req->execute()){
                foreach($req->fetchALL(PDO::FETCH_CLASS,get_called_class()) as $record){
                    $records[] = $record;
                }
            }
        }else{
            throw new ErrorException('db table is not set');
        }
        return $records;
    }
    
    /**
     * Call this function to store current object as new record in database
     * @throws ErrorException
     * @return int|NULL primary key of new record or NULL
     */
    public function create(){
        if(isset(static::$db_table) AND isset(static::$db_fields)){
            $properties = '';
            $values = '';
            $property_values = array();
            
            foreach($this as $key => $value){
                if(in_array($key,static::$db_fields) AND !is_null($value)){
                    $properties.= $key.', ';
                    $values.= '?, ';
                    $property_values[] = $value;
                }
            }
            $properties = substr($properties, 0, -2);
            $values = substr($values, 0, -2);
            
            $sql = 'INSERT INTO '.static::$db_table.' ('.$properties.') VALUES ('.$values.') ';
            $req = $this->db->prepare($sql);
            
            $req->execute($property_values);
            
            return $this->db->lastInsertId();
        }else{
            throw new ErrorException('db table is not set or db fields are not set');
        }
    }
    
    /**
     * update current objects record in db 
     * @throws ErrorException
     * @return boolean
     */
    public function update(){
        if(isset(static::$db_table) AND isset(static::$db_fields)){
            $properties = '';
            $property_values = array();
            
            foreach($this as $key => $value){
                if(in_array($key,static::$db_fields)){
                    $properties.= $key.'=?, ';
                    $property_values[] = $value;
                }
            }
            $properties = substr($properties, 0, -2);
            $property_values[] = $this->{static::$db_pk};
                
            $req = $this->db->prepare('UPDATE '.static::$db_table.' SET '.$properties.' WHERE '.static::$db_pk.' = ?');
            $req->execute($property_values);
            if($req->rowCount() == 1){
                return true;
            }else{
                return false;
            }
        }else{
            throw new ErrorException('db table is not set or db fields are not set');
        }
    }
    
    /**
     * delete current objects record from db;
     * @return boolean
     * @throws ErrorException
     */
    public function delete(){
        // TODO: testing -> Delete Active Record
        // TODO: make funktional for more than one pk
        if(isset(static::$db_table)){
            $req = $this->db->prepare('DELETE FROM '.static::$db_table.' WHERE '.static::$db_pk.' = ?');
            $req->execute([$this->{static::$db_pk}]);
            if($req->rowCount() == 1){
                return true;
            }else{
                return false;
            }
        }else{
            throw new ErrorException('db table is not set');
        }
    }
    
}