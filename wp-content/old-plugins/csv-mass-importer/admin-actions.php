<?php

class CMI_Action extends CMI_Helper_Html{

	protected $csv_fields = array(
		'ID',
        'post_author',
		'post_type',
		'post_name',
		'post_title',
		'post_content',
        'post_content_filtered',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_parent',
        'menu_order',
        'post_mime_type',
        'post_date',
        'post_date_gmt',
        'guid'
	);

    protected $debug = array();

	public function __construct(){
		add_action('wp_ajax_cmi_get_fields', array($this, 'get_fields_ajax'));
		add_action('wp_ajax_cmi_export', array($this, 'export_ajax'));
		add_action('wp_ajax_cmi_export_download', array($this, 'export_download_ajax'));
		add_action('wp_ajax_cmi_preimport', array($this, 'preimport_ajax'));
		add_action('wp_ajax_cmi_import', array($this, 'import_ajax'));
		add_action('admin_init', array($this, 'import_upload'));
	}

    public function get_fields_ajax(){
        global $wpdb;

		$response = array('html' => '');
        $post_type = 'post';
        if(isset($_POST['cmi_export_type']) && $this->is_valid_export_type($_POST['cmi_export_type'])){
            $post_type = $_POST['cmi_export_type'];
        }

        // Get data fields
        $data_inputs = array();
        foreach($this->csv_fields as $data_field){
            if($data_field != 'ID'){
                $data_inputs[] = '<input type="checkbox" name="cmi_selected_data[]" class="selected-data" value="' . $data_field . '" /> ' . $data_field;
            }
        }
        $response['html'] .= '<strong>' . __('Entity fields', 'cmi') . '</strong>';
        $response['html'] .= $this->build_table($data_inputs, 3, 'selected-data');

        // Get meta fiels
        $selected_metas = array();
        $post_ids = $wpdb->get_col('SELECT ID FROM ' . $wpdb->posts . ' WHERE post_type="' . $post_type . '"');
        $all_metas = $wpdb->get_results('SELECT post_id, meta_key FROM ' . $wpdb->postmeta);
        if($all_metas && $post_ids){
            foreach($all_metas as $meta){
                if(in_array($meta->post_id, $post_ids) && !in_array($meta->meta_key, $selected_metas)){
                    $selected_metas[] = $meta->meta_key;
                }
            }
        }
        if(count($selected_metas) > 1){
            sort($selected_metas, SORT_STRING);
        }
        if($selected_metas){
            $meta_inputs = array();
            foreach($selected_metas as $meta){
                $meta_inputs[] = '<input type="checkbox" name="cmi_selected_metas[]" class="selected-metas" value="' . $meta . '" /> ' . $meta;
            }
            $response['html'] .= '<strong>' . __('Meta fields', 'cmi') . '</strong>';
            $response['html'] .= $this->build_table($meta_inputs, 3, 'selected-metas');
        }

        // Get taxonomy fields
        if($post_taxonomies = get_object_taxonomies($post_type, 'objects')){
            $taxonomy_inputs = array();
            foreach($post_taxonomies as $tax_name => $taxonomy){
				$taxonomy_inputs[] = '<input type="checkbox" name="cmi_selected_taxonomies[]" class="selected-taxonomies" value="' . $tax_name . '" /> ' . $tax_name;
            }
            $response['html'] .= '<strong>' . __('Taxonomies', 'cmi') . '</strong>';
            $response['html'] .= $this->build_table($taxonomy_inputs, 3, 'selected-taxonomies');
        }

        echo json_encode($response);
        exit;
    }

	public function export_ajax(){
		$response = array(
							'status' => 0,
							'html' => '',
							'exportDest' => $this->get_export_dest(),
							'downloadReady' => 0
							);

		switch($response['exportDest']){
			case 'download':
				$response['status'] = (int)$this->export();
				$response['html'] = $this->get_export_progress_download_html($response['status']);
				if($response['status']){
					$response['downloadReady'] = 1;
				}
				break;
			case 'leave':
				$response['status'] = (int)$this->export();
				$response['html'] = $this->get_export_progress_leave_html($response['status']);
				break;
		}

		$response['debug'] = $this->debug;
        $this->respond_json($response);
	}

	public function export_download_ajax(){
		if(file_exists($this->get_data_file())){
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . basename($this->get_data_file()) . '"');
			header('Content-Transfer-Encoding: binary');
			header('Cache-Control: must-revalidate');
			header('Content-Length: ' . filesize($this->get_data_file()));
			readfile($this->get_data_file());
			unlink($this->get_data_file());
			wp_die();
		}
		echo '';
		wp_die();
	}

	public function preimport_ajax(){
		$check = $this->preimport_check();
		$this->respond_json(array(
			'status' => $check['status'],
			'html' => $this->get_preimport_html($check)
			));
	}

	public function import_ajax(){
		$import_progress = $this->import();
		if(!$import_progress['report']['finished']){
			$this->save_progress($import_progress);
		}else{
			//$this->rmdir_r($this->get_uploads_dir());
			unlink($this->get_progress_file());
		}
		$this->respond_json(array(
			'status' => $import_progress['report']['status'],
			'html' => $this->get_import_progress_html($import_progress),
			'finished' => (int)$import_progress['report']['finished'],
            'debug' => $this->debug
		));
	}

	protected function export(){

		$post_type = (isset($_POST['cmi_export_type']) && $this->is_valid_export_type($_POST['cmi_export_type'])) ? $_POST['cmi_export_type'] : 'post';

		$post_num = (isset($_POST['cmi_export_limit']) && $_POST['cmi_export_limit']) ? (int)$_POST['cmi_export_limit'] : -1;

		$date_from = (isset($_POST['cmi_export_date_from']) && $_POST['cmi_export_date_from']) ? $this->parse_date(sanitize_text_field($_POST['cmi_export_date_from'])) : array();

		$date_to = (isset($_POST['cmi_export_date_to']) && $_POST['cmi_export_date_to']) ? $this->parse_date(sanitize_text_field($_POST['cmi_export_date_to'])) : array();

		$date_arg = array();
		if($date_from || $date_to){
			if($date_from){
				$date_arg['after'] = $date_from;
			}
			if($date_to){
				$date_arg['before'] = $date_to;
			}
		}

		$export_data = array();
		$export_args = array(
								'post_type' => $post_type,
								'post_status' => 'publish',
								'posts_per_page' => $post_num
								);
		if($date_arg){
			$export_args['date_query'] = $date_arg;
		}

		$export_data = $this->get_posts($export_args);

		$this->rmdir_r($this->get_uploads_dir());

		if($export_data){

			$selected_data = (isset($_POST['cmi_selected_data']) && $_POST['cmi_selected_data']) ? true : false;

			$selected_meta = (isset($_POST['cmi_selected_metas']) && $_POST['cmi_selected_metas']) ? true : false;

			$selected_taxonomies = (isset($_POST['cmi_selected_taxonomies']) && $_POST['cmi_selected_taxonomies']) ? true : false;

			$selected_fields = ($selected_data || $selected_meta|| $selected_taxonomies) ? true : false;

			// Leave selected data fields only
			if($selected_data){
				$data_fields = $this->csv_fields;
				foreach($data_fields as $k => $field_key){
					if($field_key != 'ID' && !in_array($field_key, $_POST['cmi_selected_data'])){
						unset($this->csv_fields[$k]);
					}
				}
			// Leave ID field for export anyway
			}elseif($selected_fields){
				$this->csv_fields = array('ID');
			}

			foreach($export_data as &$post){
				// Get selected metas only
				if($selected_meta){
					foreach($_POST['cmi_selected_metas'] as $pmetak){
						$pmetak = sanitize_text_field($pmetak);
						if($pmeta = get_post_meta($post['ID'], $pmetak)){
                            if(!in_array('meta:' . $pmetak, $this->csv_fields)){
                                $this->csv_fields[] = 'meta:' . $pmetak;
                            }
                            $post['meta:' . $pmetak] = $pmeta;
						}
					}
				// Get all metas
				}elseif(!$selected_fields && ($pmetas = get_post_meta($post['ID']))){
					foreach($pmetas as $pmetak => $pmeta){
                        if(!in_array('meta:' . $pmetak, $this->csv_fields)){
                            $this->csv_fields[] = 'meta:' . $pmetak;
                        }
                        $post['meta:' . $pmetak] = $pmeta;
					}
				}

				// Get selectes taxonomies only
				if($selected_taxonomies){
					foreach($_POST['cmi_selected_taxonomies'] as $ptax){
						$ptax = sanitize_text_field($ptax);
                        if(($pterms = $this->objects_to_arrs(get_the_terms($post['ID'], $ptax))) && !is_wp_error($pterms)){
		                    if(!in_array('taxonomy:' . $ptax, $this->csv_fields)){
		                        $this->csv_fields[] = 'taxonomy:' . $ptax;
		                    }
                            $post['taxonomy:' . $ptax] = $pterms;
						}
					}
				// Get all taxonomies
				}elseif(!$selected_fields && ($ptaxes = get_post_taxonomies($post['ID']))){
					foreach($ptaxes as $ptax){
                        if(($pterms = $this->objects_to_arrs(get_the_terms($post['ID'], $ptax))) && !is_wp_error($pterms)){
		                    if(!in_array('taxonomy:' . $ptax, $this->csv_fields)){
		                        $this->csv_fields[] = 'taxonomy:' . $ptax;
		                    }
                            $post['taxonomy:' . $ptax] = $pterms;
						}
					}
				}
			}
		}

		return $this->save_data($export_data, $this->csv_fields);
	}

	protected function parse_date($date_str, $sep='/'){
		$date_exploded = explode($sep, trim($date_str));
		if(count($date_exploded) != 3){
			return array();
		}
		return array('year' => $date_exploded[0], 'month' => $date_exploded[1], 'day' => $date_exploded[2]);
	}

	protected function preimport_check(){

		$check = array('summary' => array(), 'errors' => array(), 'status' => 1);
		if(!$import_data = $this->get_data($this->get_csv_separs())){
			$check['errors'][] = __('Could not load data file or file is empty!', 'cmi');
			$check['status'] = 0;
			return $check;
		}
		$check['summary']['posts_count'] = count($import_data);
		$check['summary']['terms_count'] = $this->count_terms($import_data);
		$check['summary']['attachments_count'] = $this->count_attachments($import_data);

		return $check;
	}

	public function import_upload(){
		if($this->is_source_uploaded()){
			$this->rmdir_r($this->get_uploads_dir());

			if($_FILES['cmi_import_upload']['type'] == 'text/csv'){
				$this->mkdir_r($this->get_uploads_dir());
				move_uploaded_file($_FILES['cmi_import_upload']['tmp_name'], $this->get_data_file());
			}elseif($_FILES['cmi_import_upload']['type'] == 'application/zip'
			&& move_uploaded_file($_FILES['cmi_import_upload']['tmp_name'], $this->get_zip_file())){
				WP_Filesystem();
				unzip_file($this->get_zip_file(), $this->get_uploads_dir());
				unlink($this->get_zip_file());
			}
		}
	}

	protected function import(){
		global $sitepress;

		if(!$import_progress = $this->get_progress()){
			$import_progress = array(
										'report' => array(
											'posts_data_length' => 0,
											'posts_data_progress' => 0,
											'posts_data_progress_sys' => 0,

											'attachments_data_length' => 0,
											'attachments_data_progress' => 0,
											'attachments_data_progress_sys' => 0,

											'errors' => array(),

											'status' => 1,
											'finished' => false
											),

										'orig_post_id' => 0,
										'opts' => $this->get_import_opts()
										);
		}

		if(!$import_data = $this->get_data($import_progress['opts']['separs'], $import_progress['opts']['safe'])){
			$import_progress['report']['errors'][] = __('Could not load data file or file is empty!', 'cmi');
			$import_progress['report']['status'] = 0;
			return $import_progress;
		}

		//Define default language for multilingual import data
		$default_lang = (isset($import_data[0]['lang']) && $import_data[0]['lang'] != '') ? $import_data[0]['lang'] : '';

		$import_progress['report']['posts_data_length'] = count($import_data);
		$import_progress['report']['attachments_data_length'] = $this->count_attachments($import_data);

		if(!$import_progress['report']['posts_data_length']){
			$import_progress['report']['errors'][] = __('No posts to import!', 'cmi');
			$import_progress['report']['status'] = 0;
			return $import_progress;
		}

        // Import post data, meta and terms
		if($import_progress['report']['posts_data_progress_sys'] < $import_progress['report']['posts_data_length']){
			$loop_start = $import_progress['report']['posts_data_progress_sys'];
			foreach($this->get_arr_loop_sector($import_data, $loop_start) as $import_item){
				$new_post = false;
				$pid = 0;
				$post = array();
				if($import_item['ID'] == 'new'){
					$new_post = true;
					unset($import_item['ID']);
					$post = $this->extract_post_data($import_item);
				}else{
					$import_item['ID'] = (int)$import_item['ID'];
					$pid = $import_item['ID'];
					$post = $this->extract_post_data($import_item, false);
				}
				if($post){
					if($new_post){
						$pid = wp_insert_post($post);
					}elseif(count($post) > 1){
						wp_update_post($post);
					}
					if($pid){

						if(isset($import_item['lang']) && ($import_item['lang'] == $default_lang)){
							$import_progress['orig_post_id'] = $pid;
						}
						$is_translation = false;
						if(isset($import_item['lang']) && $import_item['lang'] != '' && $import_item['lang'] != $default_lang && $import_progress['orig_post_id']){
							$is_translation = true;
						}

                        if($post_terms = $this->extract_post_terms($import_item)){
                            foreach($post_terms as $taxonomy => $terms){
                                wp_set_object_terms($pid, $terms, $taxonomy, false);
                            }
                        }

						if($metas = $this->extract_post_metas($import_item)){
							foreach($metas as $meta_key => $meta){
								if(!$new_post){
                                    delete_post_meta($pid, $meta_key);
                                }
                                if($meta){
                                    foreach($meta as $meta_item){
                                        add_post_meta($pid, $meta_key, $meta_item);
                                    }
                                }
							}
						}

						$imported_attachments = array();

						if(isset($import_item['attachments']) && $import_item['attachments'] != ''){
							$thumbnail_file = '';
							if(isset($import_item['thumbnail']) && $import_item['thumbnail'] != ''){
								$thumbnail_file = $import_item['thumbnail'];
							}

							foreach($import_item['attachments'] as $file){

								$file_path = $this->get_img_dir() . '/' . $file;
								$file_path_real = $this->get_current_media_dir() . '/' . $file;
								if(file_exists($file_path) && copy($file_path, $file_path_real)){
									$file_type = wp_check_filetype($file_path_real);
									$attachment_data = array(
																'post_mime_type' => $file_type['type'],
																'post_title' => preg_replace('/\.[^.]+$/', '', $file),
																'post_content' => '',
																'post_status' => 'inherit',
																);

									if($aid = wp_insert_attachment($attachment_data, $file_path_real, $pid)){
										if($attachment_meta = wp_generate_attachment_metadata($aid, $file_path_real)){
											wp_update_attachment_metadata($aid, $attachment_meta);
										}
										if($thumbnail_file == $file){
											set_post_thumbnail($pid, $aid);
										}

										$imported_attachments[$aid] = $file;

										$import_progress['report']['attachments_data_progress']++;
									}
								}

								$import_progress['report']['attachments_data_progress_sys']++;
							}
						}

						// Post language
						if($is_translation && isset($sitepress)){

							$post_type = (isset($import_item['post_type']) && $import_item['post_type'] != '') ? $import_item['post_type'] : 'post';

							$wpml_post_type = apply_filters('wpml_element_type', $post_type);

							$orig_post_lang_info = apply_filters('wpml_element_language_details', null, array(
								'element_id' => (int)$import_progress['orig_post_id'],
								'element_type' => $post_type
								));
							$lang_args = array(
								'element_id' => $pid,
								'element_type' => $wpml_post_type,
								'trid' => $orig_post_lang_info->trid,
								'language_code' => $import_item['lang'],
								'source_language_code' => $orig_post_lang_info->language_code
								);

							do_action('wpml_set_element_language_details', $lang_args);
							//$sitepress->set_element_language_details_action($lang_args);

						}

						$import_progress['report']['posts_data_progress']++;
					}

					$import_progress['report']['posts_data_progress_sys']++;
				}
			}
			return $import_progress;
		}

		$import_progress['report']['finished'] = true;
		return $import_progress;
	}
}

add_action('init', 'rv_init_cmi_action');
function rv_init_cmi_action(){
	$cmi_action = new CMI_Action();
}

