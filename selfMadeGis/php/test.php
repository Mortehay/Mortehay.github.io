<?php
	ini_set('display_errors', 1);
	include('classFunctionStorage.php');
	$newDBrequest = new dbConnSetClass;
	$query = "select u_n.city, array_agg(u_n.ip_address) as ips from (select  distinct unnest(i_n.cities) as city,  i_n.ip_address from (select l.e_mail, l.login_time::date, l.ip_address, a.restriction, case when a.restriction = 'admin' then (select array_agg(city_eng) from public.links where links is not null) when a.restriction = 'full' then (select array_agg(city_eng) from public.links  where links is not null) when a.restriction = 'central' then (select array_agg(city_eng) from public.links  where links is not null and region = 'central') when a.restriction = 'eastern' then (select array_agg(city_eng) from public.links  where links is not null and region = 'eastern') when a.restriction = 'western' then (select array_agg(city_eng) from public.links  where links is not null and region = 'western') when a.restriction in(select distinct city_eng from public.links where city_eng is not null) then (select array_agg(city_eng) from public.links  where links is not null and city_eng = a.restriction) else null end as cities from public.login l join public.access a on l.e_mail = a.e_mail where l.login_time::date = now()::date group by l.e_mail, l.ip_address, l.login_time::date, a.restriction) i_n group by i_n.cities, i_n.ip_address) u_n group by u_n.city;";
	//echo $query;
	$folderTypes = array('/cc/','/air/','/she/','/topology/');
	$queryArrayKeys = array('city', 'ips');
	$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
	$sumObjectsArray = $retuenedArray;
	//print_r($sumObjectsArray);
	$arr_response = array('response' => array());
	

	foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
		foreach ($folderTypes as $folderType) {
			$folderLink = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$sumObjectsArray[$sumObjectsArrayKey]['city'].$folderType;
			if(file_exists($folderLink)){
				echo '<hr>';
				echo $folderLink.'<br>';
				print_r(postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['ips']));
				$allowedIps ="Deny from all\n";
				//$allowedIps = implode("\n",postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['ips']))."\n";

				foreach (postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['ips']) as $allowedIp) {
					$allowedIps .="Allow from ".$allowedIp. "\n";
				}
				$accessFileTemplate = "RewriteEngine On
					RewriteBase /
					Options +Indexes
					Options +FollowSymLinks
					IndexOptions Charset=UTF8
					$allowedIps
					<Files ~ '^.*\.([Hh][Tt][Aa])'>
						order allow,deny\n
						deny from all\n
					</Files>\n
					<IfModule mod_autoindex.c>
						IndexOptions IgnoreCase FancyIndexing FoldersFirst NameWidth=* DescriptionWidth=* XHTML HTMLtable SuppressHTMLPreamble SuppressRules SuppressLastModified
						IndexOrderDefault Ascending Name
						HeaderName /путь/dirlist_header.shtml
						ReadmeName /путь/dirlist_footer.shtml
						IndexIgnore .htaccess .ftpquota .DS_Store
					</IfModule>";
				echo $accessFileTemplate.'<br>';
				echo '<hr>';
				if(file_exists($folderLink.'.htaccess')){unlink($folderLink.'.htaccess');}
				$accessFile = fopen($folderLink.".htaccess", "w") or die("Unable to open file!");
				fwrite($accessFile, $accessFileTemplate);
				fclose($accessFile);
			}
		}
	  
	  
	}


?>