<?php
/**
*
* MyCloud generates a Cloud or a List of the meta keywords of your content
*
* Copyright (C) 2008-2016 Denis Mokhin. All rights reserved. 
*
* Author is:
* Denis Mokhin < denis@mokhin-tech.ru >
* http://mokhin-tech.ru
*
* @license GNU GPL, see http://www.gnu.org/licenses/gpl-2.0.html
* 
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
**/

	// no direct access
	defined('_JEXEC') or die('Restricted access');

    if($itemid!='') $itemid='&Itemid='.$itemid;

	if($separator=='')	$separator='&nbsp;';
	else
						$separator=" ".$separator." ";
		

	switch($show_rate)
	{
		case 1:
			$left_part="<sup>";
			$right_part="</sup>";
			break;
		case 2:
			$left_part="&nbsp;(";
			$right_part=")";
			break;
		case 0:
		default:
			$left_part="";
			$right_part="";
   }
	echo '<div class="mod_my_cloud'.$moduleclass_sfx.'">';
	if($add_nofollow==1)
		echo "<noindex>";
		
	if($format_type==1)	echo "<ul>";	

	if(isset($output_r))
		foreach($output_r as $k=>$v)
		{
			if($v)
			{
				if($show_rate)
					$v_s=$v;
				else
					$v_s="";
				
				if($rank==0)
					$size=$font_max;
				else
					$size=(int)($font_min+($font_rank*(($v-$minimum_count)/$rank)));
					
				if($use_opacity==1) {		
					if($rank==0)
						$opacity=1;
					else
						$opacity=(1-$min_opacity)*($v-$minimum_count)/$rank+$min_opacity;
					$opacity_s=" opacity: $opacity;";
				}
				else {
					$opacity_s="";
				}
				if($format_type==1) echo "<li>";			
				if($com2use==0)
					echo '<a '.(($add_nofollow==0)?'':'rel="nofollow"').' href="'.JRoute::_('index.php?option=com_search&searchword='.$k.'&searchphrase=exact&ordering=newest'.$itemid).'" style="font-size: '.$size.'%;'.$opacity_s.'" title="'.JText::_('tag').": ".$k.'">'.$k.$left_part.$v_s.$right_part."</a>\n";
				else
					echo '<a '.(($add_nofollow==0)?'':'rel="nofollow"').' href="'.JRoute::_('index.php?option=com_finder&q='.$k.$itemid).'" style="font-size: '.$size.'%;'.$opacity_s.'" title="'.JText::_('tag').": ".$k.'">'.$k.$left_part.$v_s.$right_part."</a>\n";
				
                if($format_type==0) echo $separator;
				if($format_type==1) echo "</li>"; 
			}
		}
	
	if($format_type==1)
		echo "</ul>";
	if($add_nofollow==1)
		echo "</noindex>";
	echo "</div>";
	
?>