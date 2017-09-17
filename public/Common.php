<?php

/** 
 * Common
 *
 * 共通処理クラス
 *
 * @access public
 */
class Common {

	/**
	 * 文字化け対策用にファイルのエンコードを変換する
	 *
	 * @access public
	 * @return string
	 * @throws RuntimeException エンコード検出に失敗した場合は例外を返す
	 */
	public function setEncoding($fullpath) {
		$tmpname = $fullpath;
		$detect = 'ASCII,JIS,UTF-8,CP51932,SJIS-win';
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		$buffer = file_get_contents($tmpname);

		// ファイルのエンコードを検出する
		if (!$encoding = mb_detect_encoding($buffer, $detect, true)) {

			// 検出に失敗した場合は、例外処理
			unset($buffer);
			throw new RuntimeException('Character set detection failed');
		}

		// ファイルのエンコードを「UTF-8」へ変換する
		file_put_contents($tmpname, mb_convert_encoding($buffer, 'UTF-8', $encoding));
		unset($buffer);

		// 変換したファイル名を返す
		return $tmpname;
	}

}
