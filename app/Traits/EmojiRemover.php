<?php
/**
 * Created by PhpStorm.
 * User: bhavikji
 * Date: 2/10/17
 * Time: 5:22 PM
 */

namespace App\Traits;

trait EmojiRemover{

	public function removeEmoji($text) {

		$clean_text = "";

		// Match Emoticons
		$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
		$clean_text = preg_replace($regexEmoticons, '', $text);

		// Match Miscellaneous Symbols and Pictographs
		$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
		$clean_text = preg_replace($regexSymbols, '', $clean_text);

		// Match Transport And Map Symbols
		$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
		$clean_text = preg_replace($regexTransport, '', $clean_text);

		// Match Miscellaneous Symbols
		$regexMisc = '/[\x{2600}-\x{26FF}]/u';
		$clean_text = preg_replace($regexMisc, '', $clean_text);

		// Match Dingbats
		$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);

		// Match Flags
		$regexDingbats = '/[\x{1F1E6}-\x{1F1FF}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);

		// Others
		$regexDingbats = '/[\x{1F910}-\x{1F95E}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);

		$regexDingbats = '/[\x{1F980}-\x{1F991}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);

		$regexDingbats = '/[\x{1F9C0}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);

		$regexDingbats = '/[\x{1F9F9}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);

		//objects


		return $clean_text;
	}
}