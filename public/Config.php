<?php

/** 
 * Config
 *
 * configファイル(config.json)から設定情報を取得するクラス
 * -CSVファイルの項目が変更されてもconfigファイルの「headers」を変更のみでOK
 * -headers= key:TBのフィールド名 val:CSVの項目名（1行目)
 *
 * @access public
 */
class Config {

	// configファイルのパス
	private $path = "../config/config.json";
	// デフォルトエンコード
	private $encode = "UTF-8";

	/**
	 * configファイルから設定情報を取得
	 *
     * @access public
	 * @return arrray
	 * @throws RuntimeException configファイルが存在しない場合は例外を返す
	 */
	public function getConfigList() {

		// JSONファイルの存在チェック
		if(file_exists($this->path)){

			// JSONファイルのエンコードを変換
			$common = new Common();
			$tmpname = $common->setEncoding($this->path);

			// JSONファイルを読み込む
			$json = file_get_contents($tmpname);
	  		$json = mb_convert_encoding($json, $this->encode, $this->encode);

			// 設定情報を返す
			return json_decode($json, true);

		} else {
			// configファイルが存在しない場合は、例外処理
			throw new RuntimeException('config file does not exist');
		}
		
	}

}
