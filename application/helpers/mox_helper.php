<?php if(!defined('BASEPATH')) exit ('No Direct Script Access Allowed');

if(!function_exists('action_log')){
    function action_log($action, $db, $val = '#', $name, $desc)
	{
		$ci =& get_instance();
		
		$data = array(
				'log_admin_id' => /*$ci->session->userdata('admin_id')*/'1',
				'log_action' => $action,
				'log_db' => $db,
				'log_value' => $val,
				'log_name' => $name,
				'log_desc' => $desc,
				'log_ip' => $ci->input->ip_address()
			);
	
		$result = $ci->db->insert('log', $data);
        return $result;
	}
}

if(!function_exists('save_history')){
    function save_history($data=array(), $table, $id=null)
    {
        if(!$id){ 
            action_log('ADD', $table, $data[$table .'_id'], $data[$table .'_name'], 'ADDED ' .$table .' ( ' .$data[$table .'_name'] .' )');
        }else{ 
            //log updated
            if($data['flag'] == 3){
                action_log('UPDATED', $table, $data[$table .'_id'], $data[$table .'_name'], 'DELETED ' .$table .' ( ' .$data[$table .'_name'] .' )');
            }else{
                action_log('UPDATED', $table, $data[$table .'_id'], $data[$table .'_name'], 'MODIFY ' .$table .' ( ' .$data[$table .'_name'] .' )');
            }
        }    
    }
}

if(!function_exists('unique_id')){
	function unique_id($table_name)
	{
		$ci =& get_instance();
		$last_entry = $ci->db->select('unique_id')->order_by('unique_id', 'desc')->get($table_name)->row_array();
		
		$unique_id = ($last_entry) ? $last_entry['unique_id'] + 1 : 1;
		
		return $unique_id;
	}
}

if(!function_exists('pre')){
    function pre($array = array()){
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        exit;
    }
}

if(!function_exists('mox_upload')){
    function mox_upload($file, $folder, $type='jpg|jpeg|png'){
        $ci = & get_instance();
        
        $config = array(
            'upload_path'       => $folder,
            'allowed_types'     => $type
        );
        $ci->load->library('upload');
        $ci->upload->initialize($config);
        
        if($ci->upload->do_upload($file)){
            return $ci->upload->data();
        }
        else
        {
            exit();
        }
    }
}

if ( ! function_exists('mox_resize')){
	function mox_resize($image, $width, $height, $folder = '', $orientation = 'single', $keep_ratio = TRUE){
		if ($image){
            if($orientation=='single'){
                if ($image['image_width'] > $width || $image['image_height'] > $height){
                    $config = array(
                    	'height'		=> $height,
                    	'width'			=> $width,
                    	'source_image'	=> $image['full_path'],
                    	'new_image'		=> $image['file_path'].$folder,
                    	'maintain_ratio'=> $keep_ratio
                    );
                }else{
                    $config = array(
                    	'height'		=> $image['image_height'],
                    	'width'			=> $image['image_width'],
                    	'source_image'	=> $image['full_path'],
                    	'new_image'		=> $image['file_path'].$folder,
                    	'maintain_ratio'=> $keep_ratio
                    );
                }
            }else{
                if($image['image_width']>$image['image_height']){
                    if ($image['image_width'] > $width || $image['image_height'] > $height){
                        $config = array(
                        	'height'		=> $height,
                        	'width'			=> $width,
                        	'source_image'	=> $image['full_path'],
                        	'new_image'		=> $image['file_path'].$folder,
                        	'maintain_ratio'=> $keep_ratio
                        );
                    }else{
                        $config = array(
                        	'height'		=> $image['image_height'],
                        	'width'			=> $image['image_width'],
                        	'source_image'	=> $image['full_path'],
                        	'new_image'		=> $image['file_path'].$folder,
                        	'maintain_ratio'=> $keep_ratio
                        );
                    }
                }else{
                    if ($image['image_width'] > $height || $image['image_height'] > $width){
                        $config = array(
                			'height'		=> $width,
                			'width'			=> $height,
                			'source_image'	=> $image['full_path'],
                			'new_image'		=> $image['file_path'].$folder,
                			'maintain_ratio'=> $keep_ratio
                		);
                    }else{
                        $config = array(
                        	'height'		=> $image['image_height'],
                        	'width'			=> $image['image_width'],
                        	'source_image'	=> $image['full_path'],
                        	'new_image'		=> $image['file_path'].$folder,
                        	'maintain_ratio'=> $keep_ratio
                        );
                    }
                }
            }
            if($config){
                $ci =& get_instance();
    			$ci->load->library('image_lib');
                $ci->image_lib->clear();
    			$ci->image_lib->initialize($config);
    			$ci->image_lib->resize();
    			return $image;
            }
		}
	}
}