<?php
/**
*
* MyCloud generates a Cloud or a List of the meta keywords of your content
*
* Copyright (C) 2008-2013 my-j.ru. All rights reserved. 
*
* Author is:
* Denis E Mokhin < denis[at]my-j.ru >
* http://my-j.ru
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
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$limit = intval( $params->get( 'maxcount', 50 ) );
$ordering = intval($params->get( 'ordering', 1 ) );
$separator = $params->get( 'separator');
$format_type = intval( $params->get( 'format_type', 0 ) );

//groups setup
$category_inc = $params->get( 'category_inc');
$category_excl = $params->get( 'category_excl');

//which component to use
$com2use = $params->get( 'using_component');

 //lists
$whitelist = $params->get( 'whitelist' );
$blacklist = $params->get( 'blacklist' );

//format
$font_max   = intval( $params->get( 'font_max', 160 ) );
$font_min   = intval( $params->get( 'font_min', 80 ) );
$show_rate  = intval( $params->get( 'show_rate', 0 ) );
$show_from  = intval( $params->get( 'show_from', 1 ) );
	
//SEO params
$add_nofollow = intval( $params->get( 'add_nofollow', 0 ) );

//Opacity
$use_opacity  = intval(  $params->get( 'use_opacity', 1   ) );
$min_opacity  = floatval($params->get( 'opacity_min', 0.3 ) );
if($min_opacity < 0) $min_opacity = 0;

//Lang
$sep_by_lang	= intval(  $params->get( 'separate_lang', 0   ) );
$lang_to_sep 	= $params->get( 'lang_to_sep','*' );
	
//Other
$moduleclass_sfx=$params->get('moduleclass_sfx');

////////////////////////////////////////////
//print_r($menu);
$itemid = JFactory::getApplication()->getMenu()->getActive()->id;
///////////////////////////////////////////

//get all metakeys from DB
$all_metakeys = modMyCloudHelper::getMetaKeys($limit,$category_inc,$category_excl,$lang_to_sep,$sep_by_lang);

   if($all_metakeys)
   {
		//create metakeys array
		foreach($all_metakeys as $item)
			$metakeys_r[]=$item->metakey;

		$all_metakeys_s=implode(',',$metakeys_r);	//combine all metakeys to string, separated by comma
		$words_r=preg_split("/\s*,+\s*/", $all_metakeys_s,-1,PREG_SPLIT_NO_EMPTY);	//getting meta words array
		$words_grades=array_count_values( $words_r );	//getting repeations of meta words

		//exclude blacklist and include whitelist
		if($blacklist)
		{
			$stopwords=preg_split("/\s*,+\s*/", $blacklist,-1,PREG_SPLIT_NO_EMPTY);	//getting blockwords array
			foreach($stopwords as $word)
			{
				if(isset($words_grades[$word]))
					unset($words_grades[$word]);
			}
		}
		if($whitelist)
		{
			$forcewords=preg_split("/\s*,+\s*/", $whitelist,-1,PREG_SPLIT_NO_EMPTY);	//getting forcewords array
			foreach($forcewords as $pair)
			{
				$pair_r=preg_split("/\s*=+\s*/", $pair,-1,PREG_SPLIT_NO_EMPTY);
				$output_r[$pair_r[0]]=$pair_r[1];
			}
		}

		//form output array
		foreach($words_grades as $k=>$v)
			$output_r[$k]=$v;

		$minimum_count = 0;
		$maximum_count = 0;
		
		if(isset($output_r)) {
			//exclude tags having small rate
			foreach($output_r as $k=>$v)
				if($v<=$show_from)
					unset($output_r[$k]);
		}

  		if(isset($output_r)) {
			$minimum_count = min(array_values($output_r));
			$maximum_count = max(array_values($output_r));
		}

  		$font_rank = $font_max - $font_min;
		
		$rank = $maximum_count - $minimum_count;

		//ordering
		if(isset($output_r))
			switch ($ordering)
			{
				case 1:
					ksort($output_r);
					break;
				case 2:
					krsort($output_r);
					break;
				case 3:
					arsort($output_r);
					break;
				case 4:
					asort($output_r);
					break;
				case 0:
				default:
					break;
			}
   }
require JModuleHelper::getLayoutPath('mod_my_cloud', $params->get('layout', 'default'));