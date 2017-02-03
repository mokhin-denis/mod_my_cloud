<?php
/**
*
* MyCloud generates a Cloud or a List of the meta keywords of your content
*
* Copyright (C) 2008-2017 Denis Mokhin. All rights reserved. 
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

defined('_JEXEC') or die('Restricted access');

class modMyCloudHelper
{
	static function getMetaKeys($limit,$categories_inc,$categories_excl,$lang_to_sep,$sep_by_lang)
	{
		$db = JFactory::getDbo();
		$result	= null;
		
		$query_1 ='';
		$query_2 ='';

		if($categories_inc)
		{
			$query_1 = $query_1.' AND catid IN ('.$categories_inc.') ';
		}
		if($categories_excl)
		{
			$query_1 = $query_1.' AND catid NOT IN ('.$categories_excl.') ';
		}
		
		if($sep_by_lang == 1)
		{
			$query_1 = $query_1.' AND language="'.$lang_to_sep.'" ';
		}
		
		if($limit)
			$query_2=" LIMIT 0,".$limit;

	  	$query=$db->getQuery(true);
		$query->select('metakey');
		$query->from('#__content');
		$query->where('state = 1 '.$query_1.$query_2);		
		$db->setQuery($query);
		$result = $db->loadObjectList();				
		
		if ($db->getErrorNum()) {
			JError::raiseWarning( 500, $db->stderr(true) );
		}

		return $result;
  }
}
?>