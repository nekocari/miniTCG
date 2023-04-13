<?php
/**
 *
 * Represents an Db Record Model
 *
 * @author Cari
 *
 */

abstract class DbRecordModel {
    
    protected $db;
    protected static 
        $db_pk = 'id',
        $sql_order_by_allowed_values = array('id'),
        $sql_direction_allowed_values = array('DESC','ASC','RAND()');
    
    protected function __construct(){
        $this->db = Db::getInstance();
    }
    
    
    /**
     * returns name of related table in database
     * @throws ErrorException
     * @return String|NULL
     */
    public static function getDbTableName(){
        if(isset(static::$db_table)){
            return static::$db_table;
        }else{
            throw new ErrorException('table name for '.get_called_class().' not set');
            return null;
        }
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
     * @return array[string 'query_part', mixed[] 'param_array']
     */
    public static function buildSqlPart($part = 'where', $arg_array = array()){ 
        $parts_implemented = array('where','order_by','limit');
        
        if(in_array($part, $parts_implemented)){
            
            $sql_part = '';
            $sql_params_arr = array();
            
            
            if(!is_null($arg_array)){ 
                switch($part){
                    case 'where': 
                        $sql_part = ' WHERE ';
                        if(is_array($arg_array)){
                            foreach($arg_array as $field => $value){
                                $placeholder = ':'.$field;
                                $sql_part.= $field.' = '.$placeholder.' AND ';
                                $sql_params_arr[$placeholder] = $value;
                            }
                            $sql_part = substr($sql_part, 0, -5);
                        }else{
                            $sql_part.= $arg_array;
                        }
                        
                        break;
                    case 'order_by' :
                        if(is_array($arg_array)){
                            $sql_part = ' ORDER BY ';
                            foreach($arg_array as $field => $direction){
                                if(in_array($field, static::$sql_order_by_allowed_values) AND in_array($direction, static::$sql_direction_allowed_values)){
                                    $sql_part.= $field.' '.$direction.', ';
                                }
                            }
                            if(strlen($sql_part) > 10){
                                $sql_part = substr($sql_part, 0, -2);
                            }else{
                                $sql_part = '';
                            }
                        }
                        break;
                    case 'limit':
                        if(!is_array($arg_array)){
                            $sql_part = ' LIMIT '.intval($arg_array);
                        }elseif(!is_null($arg_array) AND is_array($arg_array) AND count($arg_array) == 2){
                            $sql_part = ' LIMIT '.$arg_array[0].', '.$arg_array[1];
                        }
                }
            }
            //DEBUG:
            //print_r(array('query_part'=>$sql_part, 'param_array'=>$sql_params_arr));
            return array('query_part'=>$sql_part, 'param_array'=>$sql_params_arr);
            
        }else{
            throw new ErrorException('defined part is not implemented');
        }
    }
    
    /**
     * returns the number of rows meeting the set condition
     * @param mixed[] $condition
     * @return int
     */
    public static function getCount($condition = null){
        $db = Db::getInstance();        
        $sql_where = self::buildSqlPart('where',$condition);
        $sql = 'SELECT COUNT(*) FROM '.static::$db_table;
        $sql.= $sql_where['query_part'];
        $req = $db->prepare($sql);
        $req->execute($sql_where['param_array']);
        return $req->fetchColumn();
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
            $sql.= self::buildSqlPart('order_by',$order_settings)['query_part'];
            $sql.= self::buildSqlPart('limit',$limit)['query_part'];
            
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
     * @param mixed[]|string $condition
     * @param mixed[] $order_settings
     * @param int $limit
     * @throws ErrorException
     * @return get_called_class()[]
     */
    public static function getWhere($condition,$order_settings=null,$limit=null){
                    
        $records = array();
        if(isset(static::$db_table)){
            $db = Db::getInstance();
            
            if(is_array($condition)){ 
                $sql_where = self::buildSqlPart('where',$condition);
            }else{
                $sql_where['query_part'] = ' WHERE '.$condition;
                $sql_where['param_array'] = null;
            }
            
            $sql = 'SELECT * FROM '.static::$db_table;
            $sql.= $sql_where['query_part'];
            $sql.= self::buildSqlPart('order_by',$order_settings)['query_part'];
            $sql.= self::buildSqlPart('limit',$limit)['query_part']; 
            $req = $db->prepare($sql);
            //var_dump($sql);
            if($req->execute($sql_where['param_array'])){
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
            
            if(property_exists($this, 'id')){
                $this->id = $this->db->lastInsertId();
            }
            
            return $this->db->lastInsertId();
        }else{
            throw new ErrorException('db table is not set or db fields are not set');
        }
    }
    
    /**
     * update current objects record in db 
     * @throws ErrorException
     * @return boolean|NULL - returns null in case nothing changed
     */
    public function update(){
        if(isset(static::$db_table) AND isset(static::$db_fields)){
            $properties = '';
            $property_values = array();
            
            foreach($this as $key => $value){
                if(in_array($key,static::$db_fields)){
                    if($value != 'CURRENT_TIMESTAMP'){
                        $properties.= $key.'=?, ';
                        $property_values[] = $value;
                    }else{
                        $properties.= $key.'='.$value.', ';
                    }
                }
            }
            $properties = substr($properties, 0, -2);
            $property_values[] = $this->{static::$db_pk};
            $req = $this->db->prepare('UPDATE '.static::$db_table.' SET '.$properties.' WHERE '.static::$db_pk.' = ?');
            $req->execute($property_values);
            if($req->rowCount() == 1){
                return true;
            }else{
                return null;
            }
        }else{
            throw new ErrorException('db table is not set or db fields are not set');
            return false;
        }
        return false;
    }
    
    /**
     * delete current objects record from db;
     * @return boolean
     * @throws ErrorException
     */
    public function delete(){
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