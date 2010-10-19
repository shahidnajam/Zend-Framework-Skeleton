<?php
/**
 * Model that manages the privileges
 *
 * @package backoffice_models
 * @copyright Company
 */

class Privilege extends App_Model
{
    /**
     * Column for the primary key
     *
     * @var string
     * @access protected
     */
    protected $_primary = 'id';
    
    /**
     * Holds the table's name
     *
     * @var string
     * @access protected
     */
    protected $_name = 'privileges';
    
    /**
     * Finds a privilege based on its name and the id of the
     * resource it belongs to
     * 
     * @param string $name 
     * @param int $resourceId 
     * @access public
     * @return void
     */
    public function findByNameAndResourceId($name, $resourceId){
        $select = new Zend_Db_Select($this->_db);
        $select->from($this->_name);
        $select->where('name = ?', $name);
        $select->where('flag_id = ?', $resourceId);
        
        return $this->_db->fetchRow($select);
    }
    
    /**
     * Retrieves all the privileges attached to
     * the specified resource
     * 
     * @param mixed $resourceId 
     * @access public
     * @return void
     */
    public function findByResourceId($resourceId){
        $select = new Zend_Db_Select($this->_db);
        $select->from($this->_name);
        $select->where('flag_id = ?', $resourceId);
        
        return $this->_db->fetchAll($select);
    }
    
    /**
     * Overrides deleteById() in App_Model
     * 
     * @param int $privilegeId
     * @access public
     * @return void
     */
    public function deleteById($privilegeId){
        $flipperModel = new Flipper();
        
        $this->delete($this->_db->quoteInto('id = ?', $privilegeId));
        $flipperModel->deleteByPrivilegeId($privilegeId);
        
        return TRUE;
    }
    
    /**
     * Overrides App_Model::getQuery()
     * 
     * @access protected
     * @return void
     */
    protected function _select(){
        $select = new Zend_Db_Select($this->_db);
        
        $select->from(array('p' => $this->_name));
        $select->joinLeft(array('f' => 'flags'), 'p.flag_id = f.id');
        $select->order(array('p.flag_id', 'p.name'));
        $select->reset(Zend_Db_Table::COLUMNS);
        $select->columns(array('p.*', 'f.name AS flag_name'));
        
        return $select;
    }
}