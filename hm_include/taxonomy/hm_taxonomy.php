<?php
/**
 * Class này xử lý các dạng phân loại, ví dụ như danh mục
 * Nếu bạn có thêm 1 kiểu phân loại là danh mục sản phẩm chỉ chứa sản phẩm mà không có bài viết
 * bạn có thể register thêm 1 taxonomy product_category và 1 content là product
 * và kết hợp chúng lại ( sử dụng thêm 2 class không có trong trang này là content và taxonomy_relationship )
 */
if ( ! defined('BASEPATH')) exit('403');

$hmtaxonomy=array();


Class taxonomy extends MySQL{
	
	public $hmtaxonomy = array();
	
	/** Đăng ký 1 taxonomy */
	public function register_taxonomy($input=NULL){
		if( $input==NULL ) exit('Missing argument for register_taxonomy ');
		if(is_array($input)){
			return $this->register_taxonomy_by_array($input);
		}
	}
	
	/** Đăng ký 1 taxonomy bằng cách truyền vào 1 array */
	public function register_taxonomy_by_array($args=array()){
		if($args['taxonomy_name'] AND $args['taxonomy_key'] AND $args['content_key']){
			if(!$this->isset_taxonomy($args)){
				$this->set_taxonomy($args);	
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	
	/** Trả về tổng số taxonomy */
	public function total_taxonomy(){
		return sizeof($this->hmtaxonomy);
	}
	
	/** kiểm tra taxonomy đã tồn tại */
	public function isset_taxonomy($args=array()){
		
		if(is_array($args)){
			
			if($this->total_taxonomy() > 0 ){
				
				$all_taxonomy = $this->hmtaxonomy;
				$input_taxonomy_key=$args['taxonomy_key'];
				
				if( isset( $all_taxonomy[$input_taxonomy_key] ) ){
					return TRUE;
				}else{
					return FALSE;
				}

			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	/** kiểm tra taxonomy có id này tồn tại không */
	function isset_taxonomy_id($id){ 
					
		$tableName=DB_PREFIX."taxonomy";
		$whereArray=array('id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER));

		$this->SelectRows($tableName, $whereArray);
		if( $this->HasRecords() ){
			$row = $this->Row();
			$key = $row->key;
			$args =  array (
							'taxonomy_key'=>$key,
							);
			if( isset_taxonomy($args) == TRUE ){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}
	
	/** hủy 1 taxonomy */
	public function destroy_taxonomy($args=array()){
		if($this->isset_taxonomy($args)){
			$this->unset_taxonomy($args);
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/** Thêm toxonomy đã đăng ký vào biến $taxonomy */
	private function set_taxonomy($args=array()){
		$this->hmtaxonomy[$args['taxonomy_key']]=$args;
	}
	
	/** Gỡ bỏ 1 taxonomy khỏi biến $taxonomy */
	private function unset_taxonomy($args=array()){
		if( in_array($args,$this->hmtaxonomy) ){
			foreach ($this->hmtaxonomy as $key => $taxonomy){
				if(
					$this->hmtaxonomy[$key]['taxonomy_name'] == $args['taxonomy_name']
					AND
					$this->hmtaxonomy[$key]['taxonomy_key'] == $args['taxonomy_key']
				){
					unset($this->hmtaxonomy[$key]);
				}
			}
		}
	}
	
	/** Lấy giá trị 1 field của taxonomy */
	public function get_tax_val($args=array()){
					
		$name=$args['name'];
		$id=$args['id'];
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'name'=>MySQL::SQLValue($name),
							'object_type'=>MySQL::SQLValue('taxonomy'),
							'object_id'=>MySQL::SQLValue($id)
						);
		
		$this->SelectRows($tableName, $whereArray);
		$rowCount = $this->RowCount();

		if($rowCount==1){
			$row = $this->Row();
			return $row->val;
		}else{
			return NULL;
		}

	}
	
	/** Lấy giá trị field primary của taxonomy */
	public function get_primary_tax_val($id=0){
		
		$return = FALSE;
		$data = $this->taxonomy_data_by_id($id);
		if(isset($data['taxonomy'])){
			$taxonomy_key = $data['taxonomy']->key;
			if( $this->isset_taxonomy( array('taxonomy_key'=>$taxonomy_key ) ) ){
				$taxonomy =  $this->hmtaxonomy;
				if(isset($taxonomy[$taxonomy_key]['taxonomy_field']['primary_name_field']['name'])){
					$primary_name_field = $taxonomy[$taxonomy_key]['taxonomy_field']['primary_name_field']['name'];
					$return = $this->get_tax_val(array('name'=>$primary_name_field,'id'=>$id));
				}
			}
		}
		
		return $return;

	}
	
	/** Update giá trị 1 field của taxonomy */
	public function update_tax_val($args=array()){
		
		$name = $args['name'];
		$id = $args['id'];
		$value = $args['value'];
		
		$values["name"] = MySQL::SQLValue($name);
		$values["val"] = MySQL::SQLValue($value);
		$values["object_id"] = MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER);
		$values["object_type"] = MySQL::SQLValue('taxonomy');
		
		$tableName=DB_PREFIX."field";
		$whereArray = array (
							'object_id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
							'object_type'=>MySQL::SQLValue('taxonomy'),
							'name'=>MySQL::SQLValue($name),
						);
		$this->AutoInsertUpdate($tableName, $values, $whereArray);
		
		if($name=='status'){
			$tableName=DB_PREFIX."taxonomy";
			$whereArray = array (
							'id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
						);
			$values = array('status'=>MySQL::SQLValue($value));
			$this->UpdateRows($tableName, $values, $whereArray);
		}
		
	}
	
	/** Update các field của 1 taxonomy */
	public function taxonomy_update_val($args=array()){
		
		$id =  $args['id'];
		$values =  $args['value'];

		if(isset_taxonomy_id($id)){
						
			$tableName=DB_PREFIX.'taxonomy';
			$whereArray = array ('id'=>$id);
			
			return $this->UpdateRows($tableName, $values, $whereArray);

		}
		
	}
	
	/** Lấy danh sách các taxonomy theo key */
	function all_taxonomy_by_key( $key=FALSE,$parent=0 ){
		
		$return = array();
		$tableName=DB_PREFIX."taxonomy";
		$whereArray=array(
							'key'=>MySQL::SQLValue($key),
							'parent'=>MySQL::SQLValue($parent),
						);
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){
			while($row = $this->Row()){
				$return[$row->id]['id'] = $row->id;
				$return[$row->id]['name'] = $row->name;
			}
			foreach($return as $tax_id => $tax_val){
				$sub_tax = $this->all_taxonomy_by_key( $key,$tax_id );
				if(sizeof($sub_tax) > 0){
					$return[$tax_id]['sub'] = $sub_tax;
				}
			}
		}
		
		
		return $return;
		
	}
	
	/** Lấy giá trị của taxonomy */
	public function taxonomy_data_by_id($id){
		
		$return = array();
		
		/** lấy dữ liệu table taxonomy */
		$tableName=DB_PREFIX."taxonomy";
		$whereArray=array(
							'id'=>MySQL::SQLValue($id),
						);
		
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){
			$row = $this->Row();
			$return['taxonomy'] = $row;
		}
		
		/** lấy dữ liệu table field */
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('taxonomy'),
						);
		
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){
			while( $row = $this->Row() ){
				$return['field'][$row->name] = $row->val;
			}
		}

		return $return;

	}
	
	/** kiểm tra xem có relation giữa content nào với taxonomy này không */
	function taxonomy_have_content( $tax_id ){
		
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
						'target_id'=>MySQL::SQLValue($tax_id),
						'relationship'=>MySQL::SQLValue('contax'),
					);
					
		$this->SelectRows($tableName, $whereArray);
		if( $this->HasRecords() ){
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	
	/** Lấy content key của 1 taxonomy theo id */
	function taxonomy_get_content_key( $tax_id ){
		
		$tax_data = $this->taxonomy_data_by_id($tax_id);
		$tax_key = $tax_data['taxonomy']->key;
		$all_tax = $this->hmtaxonomy;
		
		if( isset($all_tax[$tax_key]) ){
			return $all_tax[$tax_key]['content_key'];
		}else{
			return FALSE;
		}
		
	}
	
	/** Lấy list taxonomy */
	function get_tax( $args = array() ){
		
		$parent=$args['parent'];
		if(!is_numeric($parent)){
			$parent = 0;
		}
		
		$order_by='name';
		if(isset($args['order_by'])){
			$order_by=$args['order_by'];
		}
		
		$order=true;
		if(isset($args['order'])){
			$order=$args['order'];
			if($order == 'ASC'){
				$order = TRUE;
			}else{
				$order = FALSE;
			}
		}
		
		$key=FALSE;
		if(isset($args['key'])){
			$key=$args['key'];
		}
		
		$tableName=DB_PREFIX."taxonomy";
		$whereArray=array();
		if($parent > 0){
			$whereArray['parent'] = MySQL::SQLValue($parent);
		}
		if($key){
			$whereArray['key'] = MySQL::SQLValue($key);
		}	
		
		$this->SelectRows($tableName, $whereArray, NULL, $order_by, $order);
		if( $this->HasRecords() ){
			$return = array();
			while( $row = $this->Row() ){
				$return[$row->id] = $row;
			}
			return $return;
		}else{
			return array();
		}
		
	}
	
}


