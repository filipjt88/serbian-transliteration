<?php if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }
/**
 * Transliterating Mode by locale
 *
 * @link              http://infinitumform.com/
 * @since             1.0.0
 * @package           Serbian_Transliteration
 *
 */
if(!class_exists('Serbian_Transliteration_Transliterating')) :
class Serbian_Transliteration_Transliterating {
	// Define locale instance
	protected $get_locale;
	protected $___get_skip_words = NULL;
	protected $___get_diacritical = NULL;

	/*
	 * Serbian transliteration
	 * @since     1.0.2
	 * @verson    1.0.0
	 * @author    Ivijan-Stefan Stipic
	 */
	public static function sr_RS ($content, $translation = 'cyr_to_lat')
	{
		if(is_array($content) || is_object($content) || is_numeric($content) || is_bool($content)) return $content;
		
		$transliteration = apply_filters('rstr/inc/transliteration/sr_RS', array(
			// Variations and special characters
			"ња" => "nja", 	"ње" => "nje", 	"њи" => "nji",	"њо" => "njo",
			"њу" => "nju",	"ља" => "lja",	"ље" => "lje",	"љи" => "lji",	"љо" => "ljo",
			"љу" => "lju",	"џа" => "dža",	"џе" => "dže",	"џи" => "dži",	"џо" => "džo",
			"џу" => "džu",
			
			"Ња" => "Nja", 	"Ње" => "Nje", 	"Њи" => "Nji",	"Њо" => "Njo",
			"Њу" => "Nju",	"Ља" => "Lja",	"Ље" => "Lje",	"Љи" => "Lji",	"Љо" => "Ljo",
			"Љу" => "Lju",	"Џа" => "Dža",	"Џе" => "Dže",	"Џи" => "Dži",	"Џо" => "Džo",
			"Џу" => "Džu",
			
			'џ'=>'dž',		'Џ'=>'Dž',		'љ'=>'lj',		'Љ'=>'Lj', 		'њ'=>'nj',
			'Њ'=>'Nj',
			
			// All other letters
			'А'=>'A',	'Б'=>'B',	'В'=>'V',	'Г'=>'G',	'Д'=>'D', 
			'Ђ'=>'Đ',	'Е'=>'E',	'Ж'=>'Ž',	'З'=>'Z',	'И'=>'I',
			'Ј'=>'J',	'К'=>'K',	'Л'=>'L',	'М'=>'M',	'Н'=>'N',
			'О'=>'O',	'П'=>'P',	'Р'=>'R',	'С'=>'S',	'Ш'=>'Š',
			'Т'=>'T',	'Ћ'=>'Ć',	'У'=>'U',	'Ф'=>'F',	'Х'=>'H',
			'Ц'=>'C',	'Ч'=>'Č',	'а'=>'a',	'б'=>'b',	'в'=>'v',
			'г'=>'g',	'д'=>'d',	'ђ'=>'đ',	'е'=>'e',	'ж'=>'ž',
			'з'=>'z',	'и'=>'i',	'ј'=>'j',	'к'=>'k',	'л'=>'l',
			'м'=>'m',	'н'=>'n',	'о'=>'o',	'п'=>'p',	'р'=>'r', 
			'с'=>'s',	'ш'=>'š',	'т'=>'t',	'ћ'=>'ć',	'у'=>'u', 
			'ф'=>'f',	'х'=>'h',	'ц'=>'c',	'ч'=>'č'
		));
		
		switch($translation)
		{
			case 'cyr_to_lat' :			
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
				
			case 'lat_to_cyr' :
				$lat_to_cyr = array();
				$lat_to_cyr = array_merge($lat_to_cyr, array_flip($transliteration));
				$lat_to_cyr = array_merge($lat_to_cyr, array(
					'NJ'=>'Њ',	'LJ'=>'Љ',	'DŽ'=>'Џ',	'DJ'=>'Ђ',	'DZ'=>'Ѕ',	'dz'=>'ѕ'
				));
				$lat_to_cyr = apply_filters('rstr/inc/transliteration/sr_RS/lat_to_cyr', $lat_to_cyr);
				
			//	return str_replace(array_keys($lat_to_cyr), array_values($lat_to_cyr), $content);
				return strtr($content, $lat_to_cyr);
				break;
		}
		
		return $content;
	}

	/*
	 * Russian transliteration
	 * @since     1.0.2
	 * @verson    1.0.0
	 * @author    Ivijan-Stefan Stipic
	 */
	public static function ru_RU ($content, $translation = 'cyr_to_lat')
	{
		if(is_array($content) || is_object($content) || is_numeric($content) || is_bool($content)) return $content;
		
		$transliteration = apply_filters('rstr/inc/transliteration/ru_RU', array(
			// Variations and special characters
			'Ё'=>'Yo',	'Ж'=>'Zh',	'Х'=>'Kh',	'Ц'=>'Ts',	'Ч'=>'Ch',
			'Ш'=>'Sh',	'Щ'=>'Shch','Ю'=>'Ju',	'Я'=>'Ja',	'ё'=>'yo',
			'ж'=>'zh',	'х'=>'kh',	'ц'=>'ts',	'ч'=>'ch',	'ш'=>'sh',
			'щ'=>'shch','ю'=>'ju',	'я'=>'ja',
			
			// All other letters
			'А'=>'A',	'Б'=>'B',	'В'=>'V',	'Г'=>'G',	'Д'=>'D', 
			'Е'=>'E',	'З'=>'Z',	'И'=>'I',	'Й'=>'J',	'К'=>'K',
			'Л'=>'L',	'М'=>'M',	'Н'=>'N',	'О'=>'O',	'П'=>'P',
			'Р'=>'R',	'С'=>'S',	'Т'=>'T',	'У'=>'U',	'Ф'=>'F',
			'Ъ'=>'',	'Ы'=>'Y',	'Ь'=>'',	'Э'=>'E',	'а'=>'a',
			'б'=>'b',	'в'=>'v',	'г'=>'g',	'д'=>'d',	'е'=>'e',	 
			'з'=>'z',	'и'=>'i',	'й'=>'j',	'к'=>'k',	'э'=>'e',
			'л'=>'l',	'м'=>'m',	'н'=>'n',	'о'=>'o',	'п'=>'p', 
			'р'=>'r',	'с'=>'s',	'т'=>'t',	'у'=>'u',	'ф'=>'f', 
			'ъ'=>'',	'ы'=>'y',	'ь'=>''
		));
		
		switch($translation)
		{
			case 'cyr_to_lat' :
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
				
			case 'lat_to_cyr' :
				$transliteration = array_filter($transliteration, function($t){
					return $t != '';
				});
				$transliteration = array_flip($transliteration);
				$transliteration = array_merge($transliteration, array(
					'CH'=>'Ч',	'YO'=>'Ё',	'ZH'=>'Ж',	'KH'=>'Х',	'TS'=>'Ц',	'Sh'=>'Ш',	'SCH'=>'Щ',	'YU'=>'Ю',	'YA'=>'Я'
				));
				$transliteration = apply_filters('rstr/inc/transliteration/ru_RU/lat_to_cyr', $transliteration);
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
		}
		
		return $content;
	}
	
	/*
	 * Belarusian transliteration
	 * @since     1.0.2
	 * @verson    1.0.0
	 * @author    Ivijan-Stefan Stipic
	 */
	public static function bel ($content, $translation = 'cyr_to_lat')
	{
		if(is_array($content) || is_object($content) || is_numeric($content) || is_bool($content)) return $content;
		
		$transliteration = apply_filters('rstr/inc/transliteration/bel', array (
			// Variations and special characters
			'ДЖ'=>'Dž',	'ДЗ'=>'Dz',	'Ё'=>'Io',	'Е'=>'Ie',
			'Х'=>'Ch',	'Ю'=>'Iu',	'Я'=>'Ia',	'дж'=>'dž',
			'дз'=>'dz',	'е'=>'ie',	'ё'=>'io',	'х'=>'ch',
			'ю'=>'iu',	'я'=>'ia',	
			
			// All other letters
			'А'=>'A',	'Б'=>'B',	'В'=>'V',	'Г'=>'H',
			'Д'=>'D',	'Ж'=>'Ž',	'З'=>'Z',	'І'=>'I',
			'Й'=>'J',	'К'=>'K',	'Л'=>'L',	'М'=>'M',
			'Н'=>'N',	'О'=>'O',	'П'=>'P',	'Р'=>'R',
			'СЬ'=>'Ś',	'С'=>'S',	'Т'=>'T',	'У'=>'U',
			'Ў'=>'Ǔ',	'Ф'=>'F',	'Ц'=>'C',	'э'=>'e',
			'Ч'=>'Č',	'Ш'=>'Š',	'Ы'=>'Y',	'Ь'=>'\'',
			'а'=>'a',	'б'=>'b',	'в'=>'v',	'г'=>'h',
			'ж'=>'ž',	'з'=>'z',	'і'=>'i',	'Э'=>'E',
			'й'=>'j',	'к'=>'k',	'л'=>'l',	'м'=>'m',
			'н'=>'n',	'о'=>'o',	'п'=>'p',	'р'=>'r',
			'сь'=>'ś',	'с'=>'s',	'т'=>'t',	'у'=>'u',
			'ў'=>'ǔ',	'ф'=>'f',	'ц'=>'c',	'д'=>'d',
			'ч'=>'č',	'ш'=>'š',	'ы'=>'y',	'ь'=>'\''
		));
		
		switch($translation)
		{
			case 'cyr_to_lat' :
				$sRe = '/(?<=^|\s|\'|’|[IЭЫAУО])';
				$content = preg_replace(
					// For е, ё, ю, я, the digraphs je, jo, ju, ja are used
					// word-initially, and after a vowel, apostrophe (’),
					// separating ь, or ў.
					array (
						$sRe . 'Е/i', $sRe . 'Ё/i', $sRe . 'Ю/i', $sRe . 'Я/i',
						$sRe . 'е/i', $sRe . 'ё/i', $sRe . 'ю/i', $sRe . 'я/i',
					),
					array (
						'Je',	'Jo',	'Ju',	'Ja',	'je',	'jo',	'ju',	'ja',
					),
					$content
				);
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
				
			case 'lat_to_cyr' :
				$transliteration = array_filter($transliteration, function($t){
					return $t != '';
				});
				$transliteration = array_flip($transliteration);
				$transliteration = array_merge($transliteration, array(
					'CH'=>'Х',	'DŽ'=>'ДЖ',	'DZ'=>'ДЗ',	'IE'=>'Е',	'IO'=>'Ё',	'IU'=>'Ю',	'IA'=>'Я'
				));
				$transliteration = apply_filters('rstr/inc/transliteration/bel/lat_to_cyr', $transliteration);
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
		}
		
		return $content;
	}
	
	/*
	 * Bulgarian transliteration
	 * @since     1.0.7
	 * @verson    1.0.0
	 * @author    Ivijan-Stefan Stipic
	 */
	public static function bg_BG ($content, $translation = 'cyr_to_lat')
	{
		if(is_array($content) || is_object($content) || is_numeric($content) || is_bool($content)) return $content;
		
		$transliteration = apply_filters('rstr/inc/transliteration/bg_BG', array (
			// Variations and special characters
			'Ж' => 'Zh',	'ж' => 'zh',	'Ц' => 'Ts',	'ц' => 'ts',	'Ч' => 'Ch',
			'ч' => 'ch',	'Ш' => 'Sh',	'ш' => 'sh',	'Щ' => 'Sht',	'щ' => 'sht',
			'Ю' => 'Yu',	'ю' => 'yu',	'Я' => 'Ya',	'я' => 'ya',
			
			// All other letters
			'А' => 'A',		'а' => 'a',		'Б' => 'B',		'б' => 'b',		'В' => 'V',
			'в' => 'v',		'Г' => 'G',		'г' => 'g',		'Д' => 'D',		'д' => 'd',
			'Е' => 'E',		'е' => 'e',		'З' => 'Z',		'з' => 'z',		'И' => 'I',
			'и' => 'i',		'Й' => 'J',		'й' => 'j',		'К' => 'K',		'к' => 'k',
			'Л' => 'L',		'л' => 'l',		'М' => 'M',		'м' => 'm',		'Н' => 'N',
			'н' => 'n',		'О' => 'O',		'о' => 'o',		'П' => 'P',		'п' => 'p',
			'Р' => 'R',		'р' => 'r',		'С' => 'S',		'с' => 's',		'Т' => 'T',
			'т' => 't',		'У' => 'U',		'у' => 'u',		'Ф' => 'F',		'ф' => 'f',
			'Х' => 'H',		'х' => 'h',		'Ъ' => 'Ǎ',		'ъ' => 'ǎ',		'Ь' => '',
			'ь' => ''
		));
		
		switch($translation)
		{
			case 'cyr_to_lat' :
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
				
			case 'lat_to_cyr' :
				$transliteration = array_filter($transliteration, function($t){
					return $t != '';
				});
				$transliteration = array_flip($transliteration);
				$transliteration = array_merge($transliteration, array(
					'ZH'=>'Ж',	'TS'=>'Ц',	'CH'=>'Ч',	'SH'=>'Ш',	'SHT'=>'Щ',	'YU'=>'Ю',	'YA'=>'Я'
				));
				$transliteration = apply_filters('rstr/inc/transliteration/bg_BG/lat_to_cyr', $transliteration);
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
		}
		
		return $content;
	}
	
	/*
	 * Macedonian transliteration
	 * @since     1.0.7
	 * @verson    1.0.0
	 * @author    Ivijan-Stefan Stipic
	 */
	public static function mk_MK ($content, $translation = 'cyr_to_lat')
	{
		if(is_array($content) || is_object($content) || is_numeric($content) || is_bool($content)) return $content;
		
		$transliteration = apply_filters('rstr/inc/transliteration/mk_MK', array (
			// Variations and special characters
			'Ѓ' => 'Gj',	'ѓ' => 'gj',	'Ѕ' => 'Dz',	'ѕ' => 'dz',	'Њ' => 'Nj',
			'њ' => 'nj',	'Љ' => 'Lj',	'љ' => 'lj',	'Ќ' => 'Kj',	'ќ' => 'kj',
			'Ч' => 'Ch',	'ч' => 'ch',	'Џ' => 'Dj',	'џ' => 'dj',	'Ж' => 'Zh',
			'ж' => 'Zh',	'Ш' => 'Sh',	'ш' => 'sh',
			
			// All other letters
			'А' => 'A',		'а' => 'a',		'Б' => 'B',		'б' => 'b',		'В' => 'V',
			'в' => 'v',		'Г' => 'G',		'г' => 'g',		'Д' => 'D',		'д' => 'd',
			'Е' => 'E',		'е' => 'e',		'З' => 'Z',		'з' => 'z',		'И' => 'I',
			'и' => 'i',		'J' => 'J',		'j' => 'j',		'К' => 'K',		'к' => 'k',
			'Л' => 'L',		'л' => 'l',		'М' => 'M',		'м' => 'm',		'Н' => 'N',
			'н' => 'n',		'О' => 'O',		'о' => 'o',		'П' => 'P',		'п' => 'p',
			'Р' => 'R',		'р' => 'r',		'С' => 'S',		'с' => 's',		'Т' => 'T',
			'т' => 't',		'У' => 'U',		'у' => 'u',		'Ф' => 'F',		'ф' => 'f',
			'Х' => 'H',		'х' => 'h',		'Ъ' => 'Ǎ',		'ъ' => 'ǎ'
		));
		
		switch($translation)
		{
			case 'cyr_to_lat' :
				$sRe = '/(?<=^|\s|\'|’|[IЭЫAУО])';
//				return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
				
			case 'lat_to_cyr' :
				$transliteration = array_filter($transliteration, function($t){
					return $t != '';
				});
				$transliteration = array_flip($transliteration);
				$transliteration = array_merge($transliteration, array(
					'ZH'=>'Ж', 'GJ' => 'Ѓ', 'CH'=>'Ч', 'SH'=>'Ш', 'Dz' => 'Ѕ', 'Nj' => 'Њ', 'Lj' => 'Љ', 'KJ' => 'Ќ', 'DJ' => 'Џ' 
				));
				$transliteration = apply_filters('rstr/inc/transliteration/mk_MK/lat_to_cyr', $transliteration);
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
		}
		
		return $content;
	}
	
	/*
	 * Kazakh transliteration
	 * @since     1.0.7
	 * @verson    1.0.0
	 * @author    Ivijan-Stefan Stipic
	 */
	public static function kk ($content, $translation = 'cyr_to_lat')
	{
		if(is_array($content) || is_object($content) || is_numeric($content) || is_bool($content)) return $content;
		
		$transliteration = apply_filters('rstr/inc/transliteration/kk', array (
			// Variations and special characters
			'Ғ' => 'Gh',	'ғ' => 'gh',		'Ё' => 'Yo',		'ё' => 'yo',		'Ж' => 'Zh',
			'ж' => 'zh',	'Ң' => 'Ng',		'ң' => 'ng',		'Х' => 'Kh',		'х' => 'kh',
			'Ц' => 'Ts',	'ц' => 'ts',		'Ч' => 'Ch',		'ч' => 'ch',		'Ш' => 'Sh',
			'ш' => 'sh',	'Щ' => 'Shch',		'щ' => 'shch',		'Ю' => 'Yu',		'ю' => 'yu',
			'Я' => 'Ya',	'я' => 'ya',
			
			// All other letters
			'А' => 'A',		'а' => 'a',		'Б' => 'B',		'б' => 'b',		'В' => 'V',
			'в' => 'v',		'Г' => 'G',		'г' => 'g',		'Д' => 'D',		'д' => 'd',
			'Е' => 'E',		'е' => 'e',		'З' => 'Z',		'з' => 'z',		'И' => 'Ī',
			'и' => 'ī',		'Й' => 'Y',		'й' => 'y',		'К' => 'K',		'к' => 'k',
			'Л' => 'L',		'л' => 'l',		'М' => 'M',		'м' => 'm',		'Н' => 'N',
			'н' => 'n',		'О' => 'O',		'о' => 'o',		'П' => 'P',		'п' => 'p',
			'Р' => 'R',		'р' => 'r',		'С' => 'S',		'с' => 's',		'Т' => 'T',
			'т' => 't',		'У' => 'Ū',		'у' => 'ū',		'Ф' => 'F',		'ф' => 'f',
			'Ү' => 'Ü',		'ү' => 'ü',		'Һ' => 'H',		'һ' => 'h',		'Э' => 'Ė',
			'э' => 'ė',		'Ұ' => 'U',		'ұ' => 'u',		'Ө' => 'Ö',		'ө' => 'ö',
			'Қ' => 'Q',		'қ' => 'q',		'ь' => '',		'І' => 'I',		'і' => 'i',
			'Ъ' => '',		'ъ' => '',		'Ь' => '',		'ь' => ''
		));
		
		switch($translation)
		{
			case 'cyr_to_lat' :
//				return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
				
			case 'lat_to_cyr' :
				$transliteration = array_filter($transliteration, function($t){
					return $t != '';
				});
				$transliteration = array_merge($transliteration, array(
					'SHCH'=>'Щ', 'GH' => 'Ғ', 'YO' => 'Ё', 'ZH'=>'Ж', 'NG'=>'Ң', 'KH'=>'Х', 'SH'=>'Ш', 'YA'=>'Я', 'YU'=>'Ю', 
					'CH'=>'Ч', 'TS'=>'Ц', 'SHCH'=>'Щ', 'J'=>'Й', 'j' => 'й', 'I'=>'И', 'i' => 'и'
				));
				$transliteration = array_flip($transliteration);
				$transliteration = apply_filters('rstr/inc/transliteration/kk/lat_to_cyr', $transliteration);
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
		}
		
		return $content;
	}
	
	/*
	 * Ukrainian transliteration
	 * @since     1.2.5
	 * @verson    1.0.0
	 * @author    Ivijan-Stefan Stipic
	 */
	public static function uk ($content, $translation = 'cyr_to_lat')
	{
		if(is_array($content) || is_object($content) || is_numeric($content) || is_bool($content)) return $content;
		
		$transliteration = apply_filters('rstr/inc/transliteration/uk', array (
			// Variations and special characters
			'Є' => 'Je',	'є' => 'je',	'Ї' => 'Ji',	'ї' => 'ji',	'Щ' => 'Šč',
			'щ' => 'šč',	'Ю' => 'Ju',	'ю' => 'ju',	'Я' => 'Ja',	'я' => 'ja',

			// All other letters
			'А' => 'A',		'а' => 'a',		'Б' => 'B',		'б' => 'b',		'В' => 'V',
			'в' => 'v',		'Г' => 'H',		'г' => 'h',		'Д' => 'D',		'д' => 'd',
			'Е' => 'E',		'е' => 'e',		'Ж' => 'Ž',		'ж' => 'ž',		'З' => 'Z',
			'з' => 'z',		'И' => 'Y',		'и' => 'y',		'I' => 'I',		'i' => 'i',
			'Й' => 'J',		'й' => 'j',		'К' => 'K',		'к' => 'k',		'Л' => 'L',
			'л' => 'l',		'М' => 'M',		'м' => 'm',		'Н' => 'N',		'н' => 'n',
			'О' => 'O',		'о' => 'o',		'П' => 'P',		'п' => 'p',		'Р' => 'R',
			'р' => 'r',		'С' => 'S',		'с' => 's',		'Т' => 'T',		'т' => 't',
			'У' => 'U',		'у' => 'u',		'Ф' => 'F',		'ф' => 'f',		'Х' => 'h',
			'х' => 'h',		'Ц' => 'C',		'ц' => 'c',		'Ч' => 'Č',		'ч' => 'č',
			'Ш' => 'Š',		'ш' => 'š',		'Ґ' => 'G',		'ґ' => 'g',		'Ь' => '\'',
			'ь' => '\''
		));
		
		switch($translation)
		{
			case 'cyr_to_lat' :
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
				
			case 'lat_to_cyr' :
				$transliteration = array_filter($transliteration, function($t){
					return $t != '';
				});
				$transliteration = array_merge($transliteration, array(
					'ŠČ' => 'Щ',	'JE' => 'Є',	'JU' => 'Ю',	'JA' => 'Я',	'JI' => 'Ї',
					'KH' => 'Х',	'Kh' => 'Х',	'kh' => 'х'
				));
				$transliteration = array_flip($transliteration);
				$transliteration = apply_filters('rstr/inc/transliteration/uk/lat_to_cyr', $transliteration);
			//	return str_replace(array_keys($transliteration), array_values($transliteration), $content);
				return strtr($content, $transliteration);
				break;
		}
		
		return $content;
	}

	/*
	 * Get latin letters in array
	 * @return        array
	 * @author        Ivijan-Stefan Stipic
	*/
	public function lat()
	{
		return apply_filters('rstr_lat_letters', array(
			// Variations and special characters
			'nj', 'NJ', 'Nj', 'Lj', 'Dž', 'Dj', 'DJ', 'dj', 'dz', 'JU', 'ju', 'JA', 'ja' ,'ŠČ' ,'šč',
			// Big letters
			'A', 'B', 'V', 'G', 'D', 'Đ', 'E', 'Ž', 'Z', 'I', 'J', 'K', 'L', 'LJ', 'M',
			'N', 'O', 'P', 'R', 'S', 'T', 'Ć', 'U', 'F', 'H', 'C', 'Č', 'DŽ', 'Š',
			// Small letters
			'a', 'b', 'v', 'g', 'd', 'đ', 'e', 'ž', 'z', 'i', 'j', 'k', 'l', 'lj', 'm',
			'n', 'o', 'p', 'r', 's', 't', 'ć', 'u', 'f', 'h', 'c', 'č', 'dž', 'š',
		));
	}
	
	/*
	 * Get cyrillic letters in array
	 * @return        array
	 * @author        Ivijan-Stefan Stipic
	*/
	public function cyr()
	{
		return apply_filters('serbian_transliteration_cyr_letters', array(
			// Variations and special characters
			'њ', 'Њ', 'Њ', 'Љ', 'Џ', 'Ђ', 'Ђ', 'ђ', 'ѕ', 'Ю', 'ю', 'Я', 'я' ,'Щ' ,'щ',
			// Big letters
			'А', 'Б', 'В', 'Г', 'Д', 'Ђ', 'Е', 'Ж', 'З', 'И', 'Ј', 'К', 'Л', 'Љ', 'М',
			'Н', 'О', 'П', 'Р', 'С', 'Т', 'Ћ', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Џ', 'Ш',
			// Small letters
			'а', 'б', 'в', 'г', 'д', 'ђ', 'е', 'ж', 'з', 'и', 'ј', 'к', 'л', 'љ', 'м',
			'н', 'о', 'п', 'р', 'с', 'т', 'ћ', 'у', 'ф', 'х', 'ц', 'ч', 'џ', 'ш'			
		));
	}
	
	
	/*
	 * Get locale
	 * @return        string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function get_locale(){
		if(!$this->get_locale){
			$this->get_locale = get_locale();
		}
        return $this->get_locale;
	}
	
	/*
	 * Get list of available locales
	 * @return        bool false, array or string on needle
	 * @author        Ivijan-Stefan Stipic
	*/
	public function get_locales( $needle = NULL ){
		$locales = array();
		$locale_file=apply_filters('rstr/init/libraries/file/locale', RSTR_ROOT.'/libraries/locale.lib');
		
		if(file_exists($locale_file))
		{
			if($fopen_locale=fopen($locale_file, 'r'))
			{
				$contents = fread($fopen_locale, filesize($locale_file));
				fclose($fopen_locale);
				
				if(!empty($contents))
				{
					$locales = explode("\n", $contents);
					$locales = array_unique($locales);
					$locales = array_filter($locales);
					$locales = array_map('trim', $locales);
				} else return false;
			} else return false;
		} else return false;
		
		if($needle) {
			return (in_array($needle, $locales, true) !== false ? $needle : false);
		} else {
			return $locales;
		}
	}
	
	/*
	 * Exclude words or sentences for Cyrillic
	 * @return        array
	 * @author        Ivijan-Stefan Stipic
	 * @contributor   Slobodan Pantovic
	*/
	public function cyr_exclude_list(){
		$cyr_exclude_list = apply_filters('rstr/init/exclude/cyr', array());
		
		$content = ob_get_status() ? ob_get_contents() : false;
		if ( false !== $content ){
			if ( preg_match_all('/\\\u[0-9a-f]{4}/i', $content, $exclude_unicode)){
				$cyr_exclude_list = array_merge($cyr_exclude_list, $exclude_unicode);
			}
		}
		
		$cyr_exclude_list = array_filter($cyr_exclude_list);
		
		return $cyr_exclude_list;
	}
	
	/*
	 * Exclude words or sentences for Latin
	 * @return        array
	 * @author        Ivijan-Stefan Stipic
	*/
	public function lat_exclude_list(){
		return apply_filters('rstr/init/exclude/lat', array());
	}
	
	/*
	 * Create only diacritical library
	 * THIS IS TEST FUNCTION, NOT FOR THE PRODUCTION
	 * @author        Ivijan-Stefan Stipic
	*/
/*
	private function create_only_diacritical($file, $new_file){
		
		if(file_exists($file) || empty($new_file)) return;
		if(preg_match('/(\.lib)/i', $new_file) === false) return;
		
		$filesize = filesize(RSTR_ROOT.'/libraries/' . $file);
		$fp = @fopen($file, "r");
		$chunk_size = (1<<24); // 16MB arbitrary
		$position = 0;
		
		$new_file = fopen(RSTR_ROOT.'/libraries/' . $new_file, "w");
		
		// if handle $fp to file was created, go ahead
		if ($fp)
		{
			while(!feof($fp))
			{
				// move pointer to $position in file
				fseek($fp, $position);
				
				// take a slice of $chunk_size bytes
				$chunk = fread($fp,$chunk_size);
				
				// searching the end of last full text line
				$last_lf_pos = strrpos($chunk, "\n");
				
				// $buffer will contain full lines of text
				// starting from $position to $last_lf_pos
				$buffer = mb_substr($chunk,0,$last_lf_pos);
				
				$words = explode("\n", $buffer);
				$words = array_unique($words);
				$words = array_filter($words);
				$words = array_map('trim', $words);
				
				$save = array();
				foreach($words as $word) {
					if(preg_match('/[čćžšđ]/i', $word)){
						$save[]= $word;
					}
				}
				fwrite($new_file, join("\n", $save)) . "\n";
				
				// Move $position
				$position += $last_lf_pos;
				
				// if remaining is less than $chunk_size, make $chunk_size equal remaining
				if(($position+$chunk_size) > $filesize) $chunk_size = ($filesize-$position);
				$buffer = NULL;
			}
			fclose($fp);
			fclose($new_file);
		}
	}
*/
	/*
	 * Get list of diacriticals
	 * @return        bool false, array or string on needle
	 * @author        Ivijan-Stefan Stipic
	*/
	public function get_diacritical( $needle = NULL ){
		
		if(NULL === $this->___get_diacritical)
		{
			$file_name=apply_filters('rstr/init/libraries/file/get_diacritical', $this->get_locale().'.diacritical.words.lib');
			$this->___get_diacritical = $this->parse_library($file_name);
		}
		
		if($needle) {
			return (in_array($needle, $this->___get_diacritical, true) !== false ? $needle : false);
		}
		
		return $this->___get_diacritical;
	}
	
	/*
	 * Get skip words
	 * @return        bool false, array or string on needle
	 * @author        Ivijan-Stefan Stipic
	*/
	public function get_skip_words( $needle = NULL ){
		
		if(NULL === $this->___get_skip_words)
		{
			$file_name=apply_filters('rstr/init/libraries/file/skip-words', $this->get_locale().'.skip.words.lib');
			$this->___get_skip_words = $this->parse_library($file_name);
		}
		
		if($needle) {
			return (in_array($needle, $this->___get_skip_words, true) !== false ? $needle : false);
		}
		
		return $this->___get_skip_words;
	}
	
	/*
	 * Parse library
	 * @return        bool false, array or string on needle
	 * @author        Ivijan-Stefan Stipic
	*/
	public function parse_library($file_name, $needle = NULL) {
		
		$words = array();
		$words_file=apply_filters('rstr/init/libraries/file', RSTR_ROOT . '/libraries/' . $file_name);
		
		if(file_exists($words_file))
		{
			if($fopen_locale=fopen($words_file, 'r'))
			{
				$contents = fread($fopen_locale, filesize($words_file));
				fclose($fopen_locale);
				
				if(!empty($contents))
				{
					$words = explode("\n", $contents);
					$words = array_unique($words);
					$words = array_filter($words);
					$words = array_map('trim', $words);
				} else return false;
			} else return false;
		} else return false;
		
		if($needle) {
			return (in_array($needle, $words, true) !== false ? $needle : false);
		} else {
			return $words;
		}
	}
}
endif;