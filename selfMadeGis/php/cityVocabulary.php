	<?php
	//ini_set('display_errors', 1);
	//----------------------------city array--------------------------------------------------------------------
	$cities = array(
	array('volia.vn','vinnitsa','Вінниця'),
	array('volia.kh','kharkiv','Харків'),
	array('volia.sevastopol','sevastopol','Севастополь'),
	array('volia.kr','kropyvnytskyi','Кропивницький'),
	array('volia.ck','cherkassy','Черкаси'),
	array('volia.pl','poltava','Полтава'),
	array('volia.km','khmelnitsky','Хмельницький'),
	array('volia.simferopol','simferopol','Сімферополь'),
	array('volia.sumy','sumy','Суми'),
	array('volia.dn','donetsk','Донецьк'),
	array('volia.dp','dnipro','Дніпро'),
	array('volia.zp','zaporizhia','Запоріжжя'),
	array('volia.kiev','kiev','Київ'),
	array('volia.al','alchevsk','Алчевськ'),
	array('volia.cv','chernivtsi','Чернівці'),
	array('volia.kherson','kherson','Херсон'),
	array('volia.krm','kramatorsk','Краматорськ'),
	array('volia.rv','rivne','Рівне'),
	array('volia.lviv','lviv','Львів'),
	array('volia.kryvyirih','kryvyirih','Кривий Ріг'),
	array('volia.putyvl','putyvl','Путивль'),
	array('volia.kremenec','kremenets','Кременець'),
	array('volia.lutsk','lutsk','Луцьк'),
	array('volia.terebovlia','terebovlya','Теребовля'),
	array('volia.ternopil','ternopil','Тернопіль'),
	array('volia.chortkiv.chortkiv','chortkiv','Чортків'),
	array('volia.illichivsk','Chornomorsk','Іллічівськ'),
	array('volia.ovidiopol','ovidiopol','Овідіополь'),
	array('volia.karlivka','karlivka','Карлівка'),
	array('volia.fas','fastiv','Фастів'),
	array('volia.makiyivka','makiyivka','Макіївка'),
	array('volia.volochysk','volochisk','Волочиськ'),
	array('volia.solonitsevka','solonitsevka','Солоницівка'),
	array('volia.brovary','brovary', 'Бровари'),
	array('volia.zhytomyr','zhitomir','Житомир'),
	array('volia.melitopol','melitopol','Мелітополь'),
	array('volia.stebnyk','stebnyk','Стебник'),
	array('volia.odessa','odesa','Одеса'),
	array('volia.berdychiv','berdychiv', 'Бердичів'),
	array('volia.ukrainka','ukrainka','Українка'),
	array('volia.dobrotvir','dobrotvir','Добротвір'),
	array('volia.truskavets','truskavets','Трускавець'),
	array('volia.kamianets-podilskyi','kamianets-podilskyi','Кам_янець-Подільський'),
	array('volia.obukhiv','obukhiv','Обухів'),
	array('volia.bila-tserkva','bilatserkva', 'Біла Церква'),
	array('volia.nvm','novomoskovsk','Новомосковськ') 
	);
	//------------------------------------------------------------------------------------------------------------
	$feedback_sub = array(
		'питання по карті QGIS/веб-інтерфейсу',
		'питання по роботі вебінтерфейсу/QGIS plugins',
		'запит на додавання/корегування нових/старих елементів'
	);
	/*function cityVocabulary($cities, $field, $value) {
	   foreach($cities as $key => $city)
	   {
	      if ( $city[$field] === $value )
	         return $key;
	   }
	   return false;
	}

	echo cityVocabulary($cities, 1, 'melitopol');
	echo '<hr>';
	echo $cities[cityVocabulary($cities, 1, 'melitopol')][0];*/
?>