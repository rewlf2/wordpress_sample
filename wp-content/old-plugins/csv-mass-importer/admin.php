<?php

class CMI_Admin extends CMI_Action{

	public function __construct(){
		add_action('admin_menu', array($this, 'add_submenu_page'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
	}

	public function add_submenu_page(){
		add_management_page(
			'CSV Mass Importer',
			'CSV Mass Importer',
			'manage_options',
			'cmi-tool',
			array($this, 'display_tool_page')
		);
	}

	public function enqueue_admin_scripts($hook){
		if($hook != 'tools_page_cmi-tool'){
			return;
		}
		wp_enqueue_style('cmi-admin-css', CMI_INDEX . '/admin.css');
		wp_enqueue_style('jquery-ui-datepicker-css', CMI_INDEX . '/addons/jquery-datepicker/jquery-ui.min.css');
		wp_enqueue_style('jquery-ui-datepicker-structure-css', CMI_INDEX . '/addons/jquery-datepicker/jquery-ui.structure.min.css');
		wp_enqueue_style('jquery-ui-datepicker-theme-css', CMI_INDEX . '/addons/jquery-datepicker/jquery-ui.theme.min.css');

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('cmi-admin-js', CMI_INDEX . '/admin.js', array('jquery'));
	}

	public function display_tool_page(){ ?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h1>CSV Mass Importer</h1>
			<table id="cmi-actions-table" class="form-table"><tbody>
				<tr>
					<th scope="row"><img src="<?php echo CMI_INDEX; ?>/images/icon_export.png" alt="export" /> <?php _e('Export', 'cmi'); ?></th>
					<td><?php $this->display_export_btn(); ?></td>
				</tr>
				<tr>
					<th scope="row"><img src="<?php echo CMI_INDEX; ?>/images/icon_import.png" alt="import" /> <?php _e('Import', 'cmi'); ?></th>
					<td><?php $this->display_import_btn(); ?></td>
				</tr>
			</tbody></table>
		</div>
		<?php
	}

	protected function display_export_btn(){ ?>
		<form id="cmi-export-options">
			<?php if($export_types = $this->get_valid_export_types()){ ?>
			<p>
				<label><?php _e('Post type', 'cmi'); ?></label><br />
				<select id="cmi-export-type" name="cmi_export_type">
					<?php foreach($export_types as $post_type_code => $post_type){ ?>
						<option value="<?php echo $post_type_code; ?>"><?php echo $post_type_code . ' (' . $post_type->labels->name . ')'; ?></option>
					<?php } ?>
				</select>
			</p>
			<?php } ?>
			<p>
				<button id="cmi-getfields-btn" class="button" type="button">
					<?php _e('Load fields', 'cmi'); ?>
					<span id="cmi-getfields-loader" style="display: none;"> ...</span>
				</button>
				<small>(<?php _e('Select only desired fields for export or leave them unchecked for complete export', 'cmi'); ?>)</small>
			</p>
			<p id="cmi-getfields-wrapper"></p>
			<p>
				<label><?php _e('Dates', 'cmi'); ?></label><br />
				<input type="text" id="cmi-export-date-from" name="cmi_export_date_from" placeholder="<?php _e('From yyyy/mm/dd', 'cmi'); ?>" /> -
				<input type="text" id="cmi-export-date-to" name="cmi_export_date_to" placeholder="<?php _e('To yyyy/mm/dd', 'cmi'); ?>" />
			</p>
			<p>
				<label><?php _e('Limit', 'cmi'); ?></label><br />
				<input type="number" name="cmi-export-limit" name="cmi_export_limit" min="0" step="1" />
			</p>
			<p>
				<label><?php _e('Destination', 'cmi'); ?></label><br />
				<select name="cmi_export_dest">
					<option value="download"><?php _e('Download', 'cmi'); ?></option>
					<option value="leave"><?php _e('Leave on server uncompressed', 'cmi'); ?></option>
				</select>
			</p>
			<p>
				<input type="hidden" name="action" value="cmi_export" />
				<button id="cmi-export-btn" class="button button-primary" type="button">
					<?php _e('Export', 'cmi'); ?>
					<span class="cmi-loader" style="display: none;"> ...</span>
				</button>
			</p>
		</form>
		<div id="cmi-export-results"></div>
		<?php
	}

	protected function display_import_btn(){ ?>
		<form id="cmi-import-options" action="<?php echo admin_url('tools.php?page=cmi-tool'); ?>" enctype="multipart/form-data" method="post">

			<?php
			$import_progress = $this->get_progress();
			$import_opts = (isset($import_progress['opts'])) ? $import_progress['opts'] : $this->get_import_opts();
			?>

			<p>
				<label><?php _e('Source', 'cmi'); ?></label><br />
				<select id="cmi-import-source" name="cmi_import_source">
					<option value="upload">
						<?php echo __('Upload', 'cmi') . ' (' . ini_get('upload_max_filesize') . ' max)'; ?>
					</option>
					<option value="server"<?php if($import_progress) echo ' selected'; ?>>
						<?php _e('From server location', 'cmi'); ?>
					</option>
				</select>
			</p>
			<p>
				<label><?php _e('CSV field delimiter', 'cmi'); ?></label><br />

				<?php
				$delim = 'comma';
				if(isset($_POST['cmi_csv_delim'])){
					$delim = $_POST['cmi_csv_delim'];
				}elseif(isset($import_progress['opts'])){
					$delim = $import_progress['opts']['separs']['delim'];
				}
				?>

				<select name="cmi_csv_delim">
					<option value="comma"<?php if($delim == 'comma') echo ' selected'; ?>>
						<?php echo __('Comma', 'cmi'); ?>
					</option>
					<option value="tab"<?php if($delim == 'tab') echo ' selected'; ?>>
						<?php echo __('Tab', 'cmi'); ?>
					</option>
					<option value="semicolon"<?php if($delim == 'semicolon') echo ' selected'; ?>>
						<?php echo __('Semicolon', 'cmi'); ?>
					</option>
					<option value="space"<?php if($delim == 'space') echo ' selected'; ?>>
						<?php echo __('Space', 'cmi'); ?>
					</option>
				</select>
			</p>
			<p>
				<label><?php _e('CSV text separator', 'cmi'); ?></label><br />

				<?php
				$separ = '2quote';
				if(isset($_POST['cmi_csv_separ'])){
					$separ = $_POST['cmi_csv_separ'];
				}elseif(isset($import_progress['opts'])){
					$separ = $import_progress['opts']['separs']['separ'];
				}
				?>

				<select name="cmi_csv_separ">
					<option value="2quote"<?php if($separ == '2quote') echo ' selected'; ?>>"</option>
					<option value="quote"<?php if($separ == 'quote') echo ' selected'; ?>>'</option>
				</select>
			</p>
			<p>

				<?php
				$safe_mode = ' checked';
				if(isset($import_progress['opts']) && !$import_progress['opts']['safe']){
					$safe_mode = '';
				}
				?>

				<input type="checkbox" name="cmi_import_safe" value="1"<?php echo $safe_mode; ?>/> <?php echo __('Safe mode', 'cmi'); ?>
				<small>(<?php _e('Update only, empty cells will not cause data removal', 'cmi'); ?>)</small>
			</p>
			<p>
				<input type="file" id="cmi-import-upload" name="cmi_import_upload" class="import-input" />

				<?php
				$server_input_title = __('Import data found in', 'wppr') . ': ' . $this->get_file_rel_abs($this->get_uploads_dir());
				if(!file_exists($this->get_data_file())){
					$server_input_title = __('No import data found in', 'wppr') . ': ' . $this->get_file_rel_abs($this->get_uploads_dir());
				} ?>
				<span id="cmi-import-server" class="import-input hidden"><?php echo $server_input_title; ?></span>

				<input type="hidden" name="action" value="cmi_preimport" />
                <button id="cmi-preimport-btn" class="button button-primary" type="submit"><?php _e('Import', 'cmi'); ?><span class="cmi-loader" style="display: none;"> ...</span></button>
			</p>
		</form>
		<div id="cmi-import-results">
		<?php
		$check_status = 0;
		if($import_progress = $this->get_progress()){
			echo $this->get_import_progress_html($import_progress);
		}elseif($this->is_source_uploaded()){
			$check = $this->preimport_check();
			$check_status = $check['status'];
			echo $this->get_preimport_html($check);
		}
		?>
		</div>

		<?php
			$import_btn_title = __('Continue', 'cmi');
			$import_btn_hidden_class = ' hidden';
			if($this->get_progress()){
				$import_btn_title = __('Continue importing', 'cmi');
				$import_btn_hidden_class = '';
			}elseif($this->is_source_uploaded() && $check_status){
				$import_btn_hidden_class = '';
			}
		?>
		<p>
			<button id="cmi-import-btn" class="button button-primary<?php echo $import_btn_hidden_class; ?>" type="button"><?php echo $import_btn_title; ?><span id="cmi-import-loader" style="display: none;"> ...</span></button>
		</p>
		<?php
	}
}

add_action('init', 'rv_init_cmi_admin');
function rv_init_cmi_admin(){
	$cmi_admin = new CMI_Admin();
}

