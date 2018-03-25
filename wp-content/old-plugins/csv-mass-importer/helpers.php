<?php

class CMI_Helper{

	protected $gdrive_token;
	protected $debug;

	protected function get_export_dest(){
		$type = 'leave';
		if(isset($_POST['cmi_export_dest'])){
			$type = $_POST['cmi_export_dest'];
		}
		return $type;
	}

	protected function get_file_rel($file){
		return substr($file, strlen($this->get_uploads_basedir()));
	}

	protected function get_file_rel_abs($file){
		return substr($file, strlen(ABSPATH)-1);
	}

	protected function get_uploads_basedir(){
		$upload_dir = wp_upload_dir();
		return $upload_dir['basedir'];
	}

	protected function get_current_media_dir(){
		$upload_dir = wp_upload_dir();
		return $upload_dir['path'];
	}

	protected function get_current_media_dir_url(){
		$upload_dir = wp_upload_dir();
		return $upload_dir['url'];
	}

	protected function get_uploads_dir(){
		return $this->get_uploads_basedir() . '/cmi-data';
	}

	protected function get_data_file(){
		return $this->get_uploads_dir() . '/data.csv';
	}

	protected function get_zip_file(){
		return $this->get_uploads_basedir() . '/cmi-data.zip';
	}

	protected function get_posts($args){
		$posts = array();
		$posts_q = new WP_Query($args);
		if(isset($posts_q->posts) && $posts_q->posts){
			$posts = $this->objects_to_arrs($posts_q->posts);
		}
		return $posts;
	}

	protected function group_data_byid($ungrouped_data){
        $grouped_data = array();
        if(count($ungrouped_data) < 2){
            return $grouped_data;
        }
        $headers = array_shift($ungrouped_data);
        $ungrouped_data = array_values($ungrouped_data);
        $rows_num = count($ungrouped_data);
        $group = array();
        foreach($ungrouped_data as $k => $data_row){
            $last_row = ($rows_num == ($k+1)) ? true : false;
            $data_row = array_combine($headers, $data_row);
            if(isset($data_row['ID'])){
                if($data_row['ID'] != ''){
                    // Add previous group to array if exists
                    if($group){
                        $grouped_data[] = $group;
                    }
                    // This row is last or is one and only
                    if($last_row){
                        $grouped_data[] = array($data_row);
                    }
                    // Initialize new group
                    $group = array($data_row);
                }else{
                    // Add to group
                    $group[] = $data_row;
                    // This row is last, add the group
                    if($last_row){
                        $grouped_data[] = $group;
                    }
                }
            }
        }
        return $grouped_data;
    }

    protected function get_data($separs, $ignore_empty=false){
		$import_data_csv = array();

		$separs['delim'] = $this->get_separ_by_alias($separs['delim']);
		$separs['separ'] = $this->get_separ_by_alias($separs['separ']);

		if(file_exists($this->get_data_file()) && ($handle = fopen($this->get_data_file(), "r")) !== false){
			while(($data = fgetcsv($handle, 0, $separs['delim'], $separs['separ'])) !== false){
				$import_data_csv[] = $data;
			}
		    fclose($handle);
		}
		$import_data = array();
		if($grouped_data = $this->group_data_byid($import_data_csv)){
			foreach($grouped_data as $group){
                $post_data = array();
                foreach($group as $data_row){
                    foreach($data_row as $field_name => $field){
                        if($ignore_empty){
                            if($field != ''){
                                if($field_name == 'attachments' || strpos($field_name, 'meta:') === 0 || strpos($field_name, 'taxonomy:') === 0){
                                    $post_data[$field_name][] = $field;
                                }else{
                                    $post_data[$field_name] = $field;
                                }
                            }
                        }else{
                            if($field != '' && ($field_name == 'attachments' || strpos($field_name, 'meta:') === 0 || strpos($field_name, 'taxonomy:') === 0)){
                                $post_data[$field_name][] = $field;
                            }elseif(!isset($post_data[$field_name])){
                                $post_data[$field_name] = $field;
                            }
                        }
                    }
                }
                if($post_data){
                    $import_data[] = $post_data;
                }
			}
		}

        return $import_data;
	}

	protected function save_data($export_data, $exp_fields){
		if(empty($export_data) || empty($exp_fields)){
			return false;
		}

		if(!file_exists($this->get_uploads_dir())){
			mkdir($this->get_uploads_dir());
		}

		$file_handle = fopen($this->get_data_file(), 'w');
		fputcsv($file_handle, $exp_fields);
		foreach($export_data as $row){
			$row_expand = 1;
			foreach($row as $field){
				if(is_array($field) && ($arr_count = count($field)) > $row_expand){
					$row_expand = $arr_count;
				}
			}
			for($i=0; $i<$row_expand; $i++){
				$row_ready = array();
				foreach($exp_fields as $header){
					if(isset($row[$header]) && is_array($row[$header]) && isset($row[$header][$i])){
						if(strpos($header, 'taxonomy:') === 0 && isset($row[$header][$i]['slug'])){
							$row_ready[] = $row[$header][$i]['slug'];
						}elseif(is_array($row[$header][$i]) || is_object($row[$header][$i])){
							$row_ready[] = json_encode($row[$header][$i]);
						}else{
							$row_ready[] = $row[$header][$i];
						}
					}elseif(!$i && isset($row[$header]) && $row[$header] != ''){
						$row_ready[] = $row[$header];
					}else{
						$row_ready[] = '';
					}
				}
				if($row_ready){
					fputcsv($file_handle, $row_ready);
				}
			}
		}
		fclose($file_handle);
		return true;
	}

	protected function get_progress_file(){
		return $this->get_uploads_dir() . '/import-progress';
	}

	protected function get_progress(){
		if(file_exists($this->get_progress_file())){
			return json_decode(file_get_contents($this->get_progress_file()), true);
		}
		return false;
	}

	protected function save_progress($import_progress){
		if(empty($import_progress)){
			return false;
		}
		return file_put_contents($this->get_progress_file(), json_encode($import_progress));
	}

	protected function del_progress(){
		if(file_exists($this->get_progress_file())){
			return unlink($this->get_progress_file());
		}
		return false;
	}

	protected function get_img_dir(){
		return $this->get_uploads_dir() . '/images';
	}

	protected function get_post_meta($post_id, $meta_key, $all_metas, $single=true){
		if(isset($all_metas[$post_id][$meta_key])){
			if($single && isset($all_metas[$post_id][$meta_key][0])){
				return $all_metas[$post_id][$meta_key][0];
			}else{
				return $all_metas[$post_id][$meta_key];
			}
		}
		return false;
	}

	protected function mkdir_r($path){
		if(file_exists($path)){
			return true;
		}
		if(!substr_count($path, '/')){
			return false;
		}
		$path_e = explode('/', $path);
		$path_r = array();
		foreach($path_e as $ek => $e){
			if($e){
				$path_r[] = $e;
			}
		}

		if(!$path_r){
			return false;
		}

		$path_line = '';
		foreach($path_r as $d){
			$path_line .= '/' . $d;
			if(!file_exists($path_line) && !@mkdir($path_line)){
				return false;
			}
		}

		return true;
	}

	protected function rmdir_r($dir){
		if(is_dir($dir) && ($objects = scandir($dir))){
			foreach($objects as $object){
				if($object != '.' && $object != '..'){
					if(filetype($dir . '/' . $object) == 'dir'){
						$this->rmdir_r($dir . '/' . $object);
					}else{
						unlink($dir . '/' . $object);
					}
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	protected function objects_to_arrs($objects_arr){
		if(!$objects_arr){
			return $objects_arr;
		}
		foreach($objects_arr as $ok => &$object){
			if(is_object($object)){
				$object = (array)$object;
			}
		}
		return $objects_arr;
	}

	protected function get_arr_loop_sector($loop_arr, $start=0){
		return array_slice($loop_arr, $start, 2, true);
	}

	protected function zip_files($files=array(), $destination){
		if(empty($files)){
			return false;
		}
		if(!class_exists('ZipArchive')){
			return false;
		}
		$valid_files = array();
		foreach($files as $file){
			if(file_exists($file)){
				$valid_files[] = $file;
			}
		}
		if(empty($valid_files)){
			return false;
		}
		$zip = new ZipArchive();
		if(!$zip->open($destination, ZIPARCHIVE::OVERWRITE)){
			return false;
		}
		foreach($valid_files as $file) {
			$zip->addFile($file, $this->get_file_rel($file));
		}
		$zip->close();
		return file_exists($destination);
	}

	protected function respond_json($response){
		header('Content-Type: application/json');
		echo json_encode($response);
		wp_die();
	}

	protected function is_source_uploaded(){
		if(isset($_POST['cmi_import_source']) && $_POST['cmi_import_source'] == 'upload'
		&& isset($_FILES['cmi_import_upload']) && in_array($_FILES['cmi_import_upload']['type'], array('application/zip', 'text/csv'))){
			return true;
		}
		return false;
	}

	protected function get_valid_export_types(){
		global $wp_post_types;
		$valid_types = array();
		$banned_post_types = array('attachment', 'revision', 'nav_menu_item');
		if($wp_post_types){
			foreach($wp_post_types as $post_type_code => $post_type){
				if(!in_array($post_type_code, $banned_post_types)){
					$valid_types[$post_type_code] = $post_type;
				}
			}
		}
		return $valid_types;
	}

	protected function is_valid_export_type($type){
		return array_key_exists($type, $this->get_valid_export_types());
	}

	protected function count_terms($import_data){
		$terms = array();
		foreach($import_data as $post_data){
			foreach($post_data as $key => $data){
				if(strpos($key, 'taxonomy:') === 0 && $data != ''){
					if(is_array($data)){
						 $terms = array_merge($terms, $data);
					}else{
						 $terms[] = $data;
					}
				}
			}
		}
		return count(array_unique($terms));
	}

	protected function count_attachments($import_data){
		$attachments = array();
		foreach($import_data as $post_data){
			if(isset($post_data['attachments']) && $post_data['attachments'] != ''){
				if(is_array($post_data['attachments'])){
					 $attachments = array_merge($attachments, $post_data['attachments']);
				}else{
					 $attachments[] = $post_data['attachments'];
				}
			}
		}
		return count(array_unique($attachments));
	}

	protected function extract_post_data($import_item, $new_post=true){
		$post = array();
		foreach($import_item as $key => $data){
			if(strpos($key, 'meta:') === false && strpos($key, 'taxonomy:') === false && $data != ''){
				$post[$key] = $data;
			}
		}
		if(isset($post['attachments'])){
			unset($post['attachments']);
		}
		if(isset($post['thumbnail'])){
			unset($post['thumbnail']);
		}
		$defaults = array(
				'post_content' => '&nbsp;',
				'post_status' => 'publish',
				'post_type' => 'post'

		);
		if($new_post){
			foreach($defaults as $key => $def){
				if(!isset($post[$key]) || $post[$key] == ''){
					$post[$key] = $def;
				}
			}
		}
		return $post;
	}

	protected function extract_post_metas($import_item){
		$metas = array();
		foreach($import_item as $key => $data){
			if(strpos($key, 'meta:') === 0){
				$meta_name = substr($key, strlen('meta:'));
				if($meta_name != ''){
					$metas[$meta_name] = $data;
				}
			}
		}
		return $metas;
	}

	protected function term_slugs_to_ids($slugs, $tax_name){
		if(!is_array($slugs)){
			$slugs = array($slugs);
		}
		$ids = array();
		foreach($slugs as $slug){
			if($term = get_term_by('slug', $slug, $tax_name)){
				$ids[] = $term->term_id;
			}
		}
		return $ids;
	}

	protected function extract_post_terms($import_item, $slugs_to_ids=true){
		$terms = array();
		foreach($import_item as $key => $data){
			if(strpos($key, 'taxonomy:') === 0){
				$tax_name = substr($key, strlen('taxonomy:'));
				if($tax_name != ''){
					if($slugs_to_ids){
						$terms[$tax_name] = $this->term_slugs_to_ids($data, $tax_name);
					}else{
						$terms[$tax_name] = $data;
					}
				}
			}
		}
		return $terms;
	}

	protected function get_separ_by_alias($alias){
		$separ = '';
		switch($alias){
			case 'comma':
				$separ = ',';
				break;
			case 'tab':
				$separ = "\t";
				break;
			case 'semicolon':
				$separ = ';';
				break;
			case 'space':
				$separ = ' ';
				break;
			case '2quote':
				$separ = '"';
				break;
			case 'quote':
				$separ = "'";
				break;
		}
		return $separ;
	}

	protected function get_csv_separs(){
		$user_delim = '';
        // Use get_separ_by_alias($alias) as validation function so we can safely pass $_POST variable
		if(isset($_POST['cmi_csv_delim']) && $this->get_separ_by_alias($_POST['cmi_csv_delim']) != ''){
			$user_delim = $_POST['cmi_csv_delim'];
		}
		$user_separ = '';
        // Use get_separ_by_alias($alias) as validation function so we can safely pass $_POST variable
		if(isset($_POST['cmi_csv_separ']) && $this->get_separ_by_alias($_POST['cmi_csv_separ']) != ''){
			$user_separ = $_POST['cmi_csv_separ'];
		}

		$delim = ($user_delim != '') ? $user_delim : 'comma';
		$separ = ($user_separ != '') ? $user_separ : '2quote';

		return array('delim' => $delim, 'separ' => $separ);
	}

	protected function get_import_opts(){
		$opts = array();
		$opts['separs'] = $this->get_csv_separs();
		$opts['safe'] = (empty($_POST) || isset($_POST['cmi_import_safe'])) ? true : false;
		return $opts;
	}

}

$cmi_helper = new CMI_Helper();
