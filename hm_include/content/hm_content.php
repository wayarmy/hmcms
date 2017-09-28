<?php
/**
 * Class này xử lý các dạng phân loại, ví dụ như danh mục
 * Nếu bạn có thêm 1 kiểu phân loại là danh mục sản phẩm chỉ chứa sản phẩm mà không có bài viết
 * bạn có thể register thêm 1 content product_category và 1 content là product
 * và kết hợp chúng lại ( sử dụng thêm 2 class không có trong trang này là content và content_relationship )
 */
if ( ! defined('BASEPATH')) exit('403');


Class content extends MySQL{
	
	public $hmcontent=array();
	public $hmcontent_val=array();
	
	public function set_val($args){
		$this->hmcontent_val[$args['key']]=$args['val'];
	}
	
	public function get_val($key){
		if(isset($this->hmcontent_val[$key])){
			return $this->hmcontent_val[$key];
		}else{
			return FALSE;
		}
	}
	
	/** Đăng ký 1 content */
	public function register_content($input=NULL){
		if( $input==NULL ) exit('Missing argument for register_content ');
		if(is_array($input)){
			return $this->register_content_by_array($input);
		}
	}
	
	/** Đăng ký 1 content bằng cách truyền vào 1 array */
	public function register_content_by_array($args=array()){
		if( $args['content_name'] AND $args['content_key'] ){
			if(!$this->isset_content($args)){
				$this->set_content($args);	
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	
	/** Cập nhật 1 content */
	public function update_content($args=NULL){
		if( $args==NULL ) exit('Missing argument for register_content ');
		if(is_array($args)){
			if( $args['content_name'] AND $args['content_key'] ){
				if($this->isset_content($args)){
					$this->set_content($args);	
					return TRUE;
				}else{
					return FALSE;
				}
			}
		}
	}
	
	
	
	/** Trả về tổng số content */
	public function total_content(){
		return sizeof($this->hmcontent);
	}
	
	/** kiểm tra content đã tồn tại */
	public function isset_content($args=array()){

		if(is_array($args)){
			
			if($this->total_content() > 0 ){
				
				$all_content = $this->hmcontent;
				$input_content_key=$args['content_key'];
				
				if( isset( $all_content[$input_content_key] ) ){
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
	
	/** kiểm tra content có id này tồn tại không */
	function isset_content_id($id){
				
		$tableName=DB_PREFIX."content";
		$whereArray=array('id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER));

		$this->SelectRows($tableName, $whereArray);
		
		if( $this->HasRecords() ){
			$row = $this->Row();
			$key = $row->key;
			$args =  array (
							'content_key'=>$key,
							);
			if( $this->isset_content($args) == TRUE ){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}
	
	/** hủy 1 content */
	public function destroy_content($args=array()){
		if($this->isset_content($args)){
			$this->unset_content($args);
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/** Thêm content đã đăng ký vào biến $hmcontent */
	private function set_content($args=array()){
		$this->hmcontent[$args['content_key']]=$args;
	}
	
	/** Gỡ bỏ 1 content khỏi biến $hmcontent */
	private function unset_content($args=array()){
		if( in_array($args,$this->hmcontent) ){
			foreach ($this->hmcontent as $key => $content){
				if(
					$this->hmcontent[$key]['content_name'] == $args['content_name']
					AND
					$this->hmcontent[$key]['content_key'] == $args['content_key']
				){
					unset($this->hmcontent[$key]);
				}
			}
		}
	}
	
	/** Lấy giá trị 1 field của content */
	public function get_con_val($args=array()){
					
		$name=$args['name'];
		$id=$args['id'];
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'name'=>MySQL::SQLValue($name),
							'object_type'=>MySQL::SQLValue('content'),
							'object_id'=>MySQL::SQLValue($id)
						);
		
		$this->SelectRows($tableName, $whereArray);
		if ($this->HasRecords()) {
			$row = $this->Row();
			return $row->val;
		}else{
			return NULL;
		}

	}
	
	/** Lấy giá trị field primary của content */
	public function get_primary_con_val($id=0){
		
		$return = FALSE;
		$data = $this->content_data_by_id($id);
		if(isset($data['content'])){
			$content_key = $data['content']->key;
			if( $this->isset_content( array('content_key'=>$content_key ) ) ){
				$content =  $this->hmcontent;
				if(isset($content[$content_key]['content_field']['primary_name_field']['name'])){
					$primary_name_field = $content[$content_key]['content_field']['primary_name_field']['name'];
					$return = $this->get_con_val(array('name'=>$primary_name_field,'id'=>$id));
				}
			}
		}
		
		return $return;

	}
	
	/** Update giá trị 1 field của content */
	public function update_con_val($args=array()){
		
		$name = $args['name'];
		$id = $args['id'];
		$value = $args['value'];
		
		$values["name"] = MySQL::SQLValue($name);
		$values["val"] = MySQL::SQLValue($value);
		$values["object_id"] = MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER);
		$values["object_type"] = MySQL::SQLValue('content');
		
		$tableName=DB_PREFIX."field";
		$whereArray = array (
							'object_id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
							'object_type'=>MySQL::SQLValue('content'),
							'name'=>MySQL::SQLValue($name),
						);
		$this->AutoInsertUpdate($tableName, $values, $whereArray);
		
		if($name=='status'){
			$tableName=DB_PREFIX."content";
			$whereArray = array (
							'id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
						);
			$values = array('status'=>MySQL::SQLValue($value));
			$this->UpdateRows($tableName, $values, $whereArray);
		}
		
	}

	/** Lấy các taxonomy của content */
	public function get_con_tax($args=array()){
		
		$id=$args['id'];
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
							'relationship'=>MySQL::SQLValue('contax'),
							'object_id'=>MySQL::SQLValue($id)
						);
		$this->SelectRows($tableName, $whereArray);
		$rowCount = $this->RowCount();
		if($rowCount!=0){
			while($row = $this->Row()){
				$tax[] = $row->target_id;
			}
			return $tax;
		}else{
			return NULL;
		}
	}
	
	/** Lấy các tag của content */
	public function get_con_tag($args=array()){
		
		$id=$args['id'];
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
							'relationship'=>MySQL::SQLValue('contag'),
							'object_id'=>MySQL::SQLValue($id)
						);
		$this->SelectRows($tableName, $whereArray);
		if ($this->HasRecords()) {
			while($row = $this->Row()){
				$tag[] = $row->target_id;
			}
			return $tag;
		}else{
			return NULL;
		}
	}
	
	/** Lấy các tag của content dạng string */
	public function get_con_tag_string($args=array()){
		
		if(is_array($args)){
			
			@$tag_array=$args['tags']?$args['tags']:array();
			@$sep=$args['sep']?$args['sep']:', ';
			$tag_name = array();
			foreach($tag_array as $tag_id){
				if(is_numeric($tag_id)){
					
					$tableName=DB_PREFIX."taxonomy";
					$whereArray=array(
										'key'=>MySQL::SQLValue('tag'),
										'id'=>MySQL::SQLValue($tag_id)
									);
					$this->SelectRows($tableName, $whereArray);
					if ($this->HasRecords()) {
						$row = $this->Row();
						$tag_name[] = $row->name;
					}
					
				}
				
			}
			return implode($sep,$tag_name);
			
		}

	}
	
	/** Lấy dữ liệu của content */
	public function content_data_by_id($id){
		
		$return = array();
		
		/** lấy dữ liệu table content */
		$tableName=DB_PREFIX."content";
		$whereArray=array(
							'id'=>MySQL::SQLValue($id),
						);
		
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){
			$row = $this->Row();
			$return['content'] = $row;
		}
		
		/** lấy dữ liệu table content */
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('content'),
						);
		
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){
			while( $row = $this->Row() ){
				$return['field'][$row->name] = $row->val;
			}
		}
		
		/** lấy dữ liệu relationship con-tax */
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'relationship'=>MySQL::SQLValue('contax'),
						);
		
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){
			while( $row = $this->Row() ){
				$return['taxonomy'][] = $row->target_id;
			}
		}
		
		/** lấy dữ liệu relationship con-tag */
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'relationship'=>MySQL::SQLValue('contag'),
						);
		
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){
			while( $row = $this->Row() ){
				$return['tag'][] = $row->target_id;
			}
		}
		
		return $return;

	}
	
	/** Lấy số bản revision của 1 content theo id */
	public function content_number_revision($id){
		
		$tableName=DB_PREFIX."content";
		$whereArray=array(
							'id'=>MySQL::SQLValue($id),
						);
		
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){

			$whereArray=array(
							'parent'=>MySQL::SQLValue($id),
							'status'=>MySQL::SQLValue('revision'),
						);
			
			$this->SelectRows($tableName, $whereArray);
			if($this->HasRecords()){
				return $this->RowCount();
			}else{
				return 0;
			}
			
		}else{
			return 0;
		}
		
	}
	
	/** Lấy số bản chapter của 1 content theo id */
	public function content_number_chapter($id){
		
		$tableName=DB_PREFIX."content";
		$whereArray=array(
							'id'=>MySQL::SQLValue($id),
						);
		
		$this->SelectRows($tableName, $whereArray);
		if($this->HasRecords()){

			$whereArray=array(
							'parent'=>MySQL::SQLValue($id),
							'status'=>MySQL::SQLValue('chapter'),
						);
			
			$this->SelectRows($tableName, $whereArray);
			if($this->HasRecords()){
				return $this->RowCount();
			}else{
				return 0;
			}
			
		}else{
			return 0;
		}
		
	}
	
	/** Tạo bản xem nội dung các field của 1 content */
	public function build_field_content_blockquote($args=array()){
		
		if(isset($args['id'])){$id = $args['id'];}else{$id = NULL;}
		if(isset($args['default_value'])){$default_value = $args['default_value'];}else{$default_value = array();}
		$return = array();

		if(isset_content_id($id)){
			
			$data = $this->content_data_by_id($id);
			
			$content_key = $data['content']->key;
			
			if( $this->isset_content( array('content_key'=>$content_key ) ) ){
				
				$content =  $this->hmcontent;
				$i=1;
				foreach($content[$content_key]['content_field'] as $field){
					
					$return[$i]['field_name'] = $field['nice_name'];
					
						if( isset( $default_value[$field['name']] ) ){
							
							$return[$i]['field_val'] =  $default_value[$field['name']];
						
						}else{
							
							$return[$i]['field_val'] = get_con_val(array('name'=>$field['name'],'id'=>$id));
							
						}	
							
				$i++;	
				}
				
				
			}
			
		}
		return $return;
	}
	
	/** Update các field của 1 content */
	public function content_update_val($args=array()){
		
		$id =  $args['id'];
		$values =  $args['value'];
		if(isset_content_id($id)){
			
			foreach($values as $update_key => $update_val){
				
				update_con_val( array( 'id'=>$id,'name'=>$update_key,'value'=>$update_val ) );
				
			}
			
			$tableName=DB_PREFIX.'content';
			$whereArray = array ('id'=>$id);
			return $this->UpdateRows($tableName, $values, $whereArray);

		}
		
	}
}


