<?php
/*
Plugin Name: Hover Image
Description: Add Hover Images to wordpress posts. Simply use the shortcode [himage] [/himage]. The first image can be hyperlink. Don't use other HTML Tags within the shortcode.
Version: 1.4.1
Author: Julian Weinert // cs&m
Author URI: http://www.csundm.de
*/

/*
Hover Image (Wordpress Plugin)
Copyright (C) 2009 Julian Weinert // cs&m
Contact us at http://www.csundm.de

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// Hover image core function
function himage($atts, $content)
{
	$trans_time = get_option("trans_time")."s";
	$trans_delay = get_option("trans_delay")."s";
	$trans_timing = get_option("trans_timing");
	
	$style = "<style>\n.himage\n{\n\t-webkit-transition: opacity ".$trans_time." ".$trans_timing." ".$trans_delay.";\n\t-moz-transition: opacity ".$trans_time." ".$trans_timing." ".$trans_delay.";\n\t-o-transition: opacity ".$trans_time." ".$trans_timing." ".$trans_delay.";\n\ttransition: opacity ".$trans_time." ".$trans_timing." ".$trans_delay.";\n}\n</style>";
	$aArr = explode("<", $content);
	$aArr = explode(">", $aArr[1]);
     $beforeString = "";
     $afterString = "";
     $aString = $aArr[0];
     $aStringArr = str_split($aString);
     if($aStringArr[0] == "a")
     {
		$beforeString = "<".$aArr[0].">";
		$afterString = "</a>";
	}
	$values = explode(" ", $content);
	$srcArr = array();
	foreach($values as $i)
	{
		$arrArr = explode("=\"", $i);
		if($arrArr[0] == "src")
		{
			array_push($srcArr, str_replace("\"", "", str_replace("src=\"", "", $i)));
		}
	}
	$widthArr = array();
	foreach($values as $j)
	{
		$arrArr = explode("=\"", $j);
		if($arrArr[0] == "width")
		{
			array_push($widthArr, str_replace("\"", "", str_replace("width=\"", "", $j)));
		}
	}
	$heightArr = array();
	foreach($values as $k)
	{
		$arrArr = explode("=\"", $k);
		if($arrArr[0] == "height")
		{
			array_push($heightArr, str_replace("\"", "", str_replace("height=\"", "", $k)));
		}
	}
     $imgTag = "<div style=\"position:relative;\">".$beforeString."<img src=\"".$srcArr[1]."\" width=\"".$widthArr[1]."\" height=\"".$heightArr[1]."\" /><img class=\"himage\" src=\"".$srcArr[0]."\" width=\"".$widthArr[0]."\" height=\"".$heightArr[0]."\" onmouseover=\"this.style.opacity=0;\" onmouseout=\"this.style.opacity=1;\" style=\"position:absolute; top:0; left:0;\" />".$afterString."</div>";
	return $style.$imgTag;
}
add_shortcode("himage", "himage");

// Hover image admin panel
function himage_options()
{
	$trans_time = get_option("trans_time");
	$trans_delay = get_option("trans_delay");
	$trans_timing = get_option("trans_timing");
}

function set_himage_options()
{
	add_option("trans_time", ".4");
	add_option("trans_delay", "0");
	add_option("trans_timing", "ease-in-out");
}

function unset_himage_options()
{
	delete_option("trans_time");
	delete_option("trans_delay");
	delete_option("trans_timing");
}

register_activation_hook(__FILE__, "set_himage_options");
register_deactivation_hook(__FILE__, "unset_himage_options");

function admin_himage_options()
{
	?><div class="wrap"><h2><?php _e("Hover Image Einstellungen", "hover-image"); ?></h2><?php
	
	if($_REQUEST["submit"])
	{
		update_himage_options();
	}
	print_himage_form();
	
	?></div><?php
}

function update_himage_options()
{
	$ok = false;
	
	if(($_REQUEST["trans_time"]) && ($_REQUEST["trans_time"] != "0"))
	{
		update_option("trans_time", $_REQUEST["trans_time"]);
		$ok = trü;
	}
	elseif($_REQUEST["trans_time"] == "0")
	{
		?><div id="message" class="error fade">
            <p><?php _e("Warnung!<br>Die Dauer sollte nicht 0s betragen.", "hover-image"); ?></p>
		</div><?php
	}
	if(($_REQUEST["trans_delay"]) || ($_REQUEST["trans_delay"] == "0"))
	{
		update_option("trans_delay", $_REQUEST["trans_delay"]);
		$ok = trü;
	}
	if($_REQUEST["trans_timing"])
	{
		update_option("trans_timing", $_REQUEST["trans_timing"]);
		$ok = trü;
	}
	
	if($ok)
	{
		?><div id="message" class="updated fade">
            <p><?php _e("Einstellungen gesichert.", "hover-image"); ?></p>
		</div><?php
	}
	else
	{
		?><div id="message" class="error fade">
            <p><?php _e("Fehler beim sichern.", "hover-image"); ?></p>
		</div><?php
	}
}

function print_himage_form()
{
	$trans_time = get_option("trans_time");
	$trans_delay = get_option("trans_delay");
	$trans_timing = get_option("trans_timing");
	switch($trans_timing)
	{
		case "linear":
			$lin = "selected";
			break;
		case "ease":
			$eas = "selected";
			break;
		case "ease-in":
			$eas_in = "selected";
			break;
		case "ease-out":
			$eas_out = "selected";
			break;
		case "ease-in-out":
			$eas_in_out = "selected";
			break;
	}
	?>
	<form method="post">
		<table cellspacing="10">
			<thead>
			<tr>
				<th colspan="2">
                    <?php _e("Einstellungen für den Übergang", "hover-image"); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>
                    <?php _e("Dauer:", "hover-image"); ?>
				</td>
				<td>
					<input type="text" name="trans_time" value="<?php echo $trans_time; ?>" /> s
				</td>
				<td>
                    <i><?php _e("Tip: Nutzen Sie einen anführenden Punkt, um weniger als eine Sekunde zu erreichen: .4", "hover-image"); ?></i>
				</td>
			</tr>
			<tr>
				<td>
                    <?php _e("Verzögerung:", "hover-image"); ?>
				</td>
				<td>
					<input type="text" name="trans_delay" value="<?php echo $trans_delay; ?>" /> s
				</td>
				<td>
                    <i><?php _e("Tip: Nutzen Sie einen anführenden Punkt, um weniger, als eine Sekunde zu erreichen: .4", "hover-image"); ?></i>
				</td>
			</tr>
			<tr>
				<td>
                    <?php _e("Kurve:", "hover-image"); ?>
				</td>
				<td>
					<select name="trans_timing">
                        <option <?php echo $lin; ?> value="linear"><?php _e("Linear", "hover-image"); ?></option>
						<option <?php echo $eas; ?> value="ease"><?php _e("Mittel > Schnell > Langsam", "hover-image"); ?></option>
						<option <?php echo $eas_in; ?> value="ease-in"><?php _e("Langsam > Schnell", "hover-image"); ?></option>
						<option <?php echo $eas_out; ?> value="ease-out"><?php _e("Schnell > Langsam", "hover-image"); ?></option>
						<option <?php echo $eas_in_out; ?> value="ease-in-out"><?php _e("Langsam > Schnell > Langsam", "hover-image"); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<input type="submit" name="submit" value="<?php _e('Sichern', 'hover-image'); ?>" />
				</td>
			</tr>
			</tbody>
		</table>
	</form>
	<?php
}

function addHimage()
{
	add_options_page("Hover Image", "Hover Image", "manage_options", __FILE__, "admin_himage_options");
}

add_action("admin_menu", "addHimage");
        
function himageInit()
{
    load_plugin_textdomain("hover-image", false, dirname(plugin_basename(__FILE__))."/languages/");
}

add_action("init", "himageInit");
?>