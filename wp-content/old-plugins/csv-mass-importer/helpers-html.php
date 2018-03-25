<?php

class CMI_Helper_Html extends CMI_Helper{
	protected function get_export_progress_download_html($status){
		if($status){
			return '<p style="color: green;">' . __('Data file has been downloaded.', 'cmi') . '</p>';
		}
		return $this->get_errors_html(array(__('Failed to export data!', 'cmi')));
	}

	protected function get_export_progress_leave_html($status){
		if($status){
			return '<p style="color: green;">' . __('Data exported to', 'cmi') . ': ' . $this->get_file_rel_abs($this->get_uploads_dir()) . '</p>';
		}
		return $this->get_errors_html(array(__('Failed to export data!', 'cmi')));
	}

	protected function get_preimport_html($check){

		$html  = '';

		if($check['errors']){
			$html .= $this->get_errors_html($check['errors']);
		}

		if(!$check['status']){
			return $html;
		}

		$html .= '<h4>' . __('Pre-import summary', 'cmi') . ':</h4>';
		$html .= '<p>';
		$html .= '<span>' . __('Posts to import', 'cmi') . ':</span> <strong>' . $check['summary']['posts_count'] . '</strong><br />';
		$html .= '<span>' . __('Attachments to import', 'cmi') . ':</span> <strong>' . $check['summary']['attachments_count'] . '</strong>';
		$html .= '</p>';

		return $html;
	}

	protected function get_import_progress_html($import_progress){

		$html  = '<h4>' . __('Import progress', 'cmi') . ':</h4>';
		$html .= '<p>';
		$html .= '<span>' . __('Importing posts', 'cmi') . ':</span> ';
		$html .= '<strong>' . $import_progress['report']['posts_data_progress'] . '/' . $import_progress['report']['posts_data_length'] . '</strong><br />';

		$html .= '<span>' . __('Importing attachments', 'cmi') . ':</span> ';
		$html .= '<strong>' . $import_progress['report']['attachments_data_progress'] . '/' . $import_progress['report']['attachments_data_length'] . '</strong><br />';

		$html .= $this->get_errors_html($import_progress['report']['errors']);

		if($import_progress['report']['finished']){
			$html .= '<p style="color: green; font-weight: bold;">' . __('Import finished!', 'cmi') . '</p>';
		}

		return $html;
	}

	protected function get_errors_html($errors){
		$html = '';
		if($errors){
			$html .= '<p style="color: red;">';
			foreach($errors as $error){
				$html .= '<span>' . $error . '</span><br />';
			}
			$html .= '</p>';
		}

		return $html;
	}

    protected function build_table($data, $cols=3, $field_group=''){
        $data_count = count($data);
        $html = '<table><tbody>';

        $bulk_sel_html .= '<tr><td>';
        $bulk_sel_html .= '<span class="sel-all" data-field-group="' . $field_group . '">' . __('select all', 'cmi') . '</span> ';
        $bulk_sel_html .= '<span class="des-all" data-field-group="' . $field_group . '">' . __('deselect all', 'cmi') . '</span>';
        $bulk_sel_html .= '</td></tr>';

        if($data_count >= $cols){
            $html .= $bulk_sel_html;

            $column_count = ceil($data_count/$cols);
            $data_chunked = array_chunk($data, $column_count);
            $rows_html = '';
            for($i=0; $i<$column_count; $i++){
                $html .= '<tr>';
                foreach($data_chunked as $chunk){
                    $coldata = '';
                    if(isset($chunk[$i])){
                        $coldata = $chunk[$i];
                    }
                    $html .= '<td>' . $coldata . '</td>';
                }
                $html .= '</tr>';
            }
        }elseif($data_count){
            if($data_count > 1){
                $html .= $bulk_sel_html;
            }

            $html .= '<tr><td>' . implode('<br />', $data) . '</td></tr>';
        }else{
            $html .= '<tr><td>' . __('No fields found', 'cmi') . '</td></tr>';
        }
        return $html .= '</tbody></table>';
    }
}
