<?php

// Commonクラスを読み込む
require 'Common.php';
// Configクラスを読み込む
require 'Config.php';

/**
 * CsvUtility
 *
 * CSVファイルから情報を取得するクラス
 * -複数ファイル対応
 * -CSVファイルエンコード自動変換
 * -CSVファイルの項目が変更されてもconfigファイル変更のみでOK
 *
 * @access public
 */
class CsvUtility {

	// 設定情報
	private $config;

    /**
     * CsvUtility constructor.
     *
     * @access public
     */
	public function __construct() {

		// configファイルから設定情報を取得
	    $this->config = new Config();
		$this->config = $this->config->getConfigList();
	}

	/**
	 * 複数CSVファイルから情報取得
	 *
	 * @access public
	 * @return mixed
	 */
	public function getCsvList() {
		$data = array();
		// CSVファイル一覧取得
		$csvs = $this->getFileList();
        foreach ($csvs as $csv) {
            // 各CSVファイルから情報取得
			$data = array_merge($data, $this->getFile($csv));
		}
		return $data;
	}

	/**
	 * CSVファイル一覧取得
	 *
	 * @access public
	 * @return array
	 */
	public function getFileList() {
        $iterator = new RecursiveDirectoryIterator($this->config["path"]);
        $filelist = array();
        foreach ($iterator = new RecursiveIteratorIterator($iterator) as $fileinfo){
            if ($fileinfo->isFile()) {
                // CSVファイル名取得
                $filelist[] = $fileinfo->getBasename();
            }
        }
        // 昇順に並べ替える
		sort($filelist);
		// CSVファイル一覧を返す
		return $filelist;
	}

	/**
	 * CSVファイルから情報取得
	 *
	 * @access public
	 * @return array
	 */
	public function getFile($filename) {

		// CSVファイルのエンコードを変換
		$common = new Common();
		$tmpname = $common->setEncoding($this->config["path"].$filename);

		// CSVファイルを読み込む
		$csv = new SplFileObject($tmpname);
        $csv->setFlags(
            SplFileObject::DROP_NEW_LINE |  // 行末の改行無視
            SplFileObject::READ_AHEAD |     // 先読み
            SplFileObject::SKIP_EMPTY |     // 空行無視
            SplFileObject::READ_CSV         // CSVとして読み込み
        );

		// ヘッダー項目の定義読み込み
		$headers = $this->config["headers"];
		// ヘッダー項目格納配列
		$header = array();
		// CSVファイルのTB投入用データ格納配列
		$records = array();
		// 1行目判定フラグ
		$first = true;
		// headerカウンタ
		$cnt = 0;

		foreach ($csv as $row) {
		    // 1行目で列数を紐付け
		    if ($first) {
		    	// 1行目判定フラグOFF
		    	$first = false;
		    	// ヘッダー項目取得
		    	foreach ($row as $r) {
		            $headername = array_search($r, $headers);
		            if ($headername) {
		                $header[$cnt] = $headername;
		            }
		            $cnt++;
		        }
		        
		    } else {
		    	// 1行目以降
		        $record = array();
		        foreach ($header as $columnno => $columnname) {
                    // 値の入ったフィールドのみ取得
		            if (isset($row[$columnno]) && !empty($row[$columnno])) {
                        $record[$columnname] = $row[$columnno];
		            }
		        }
		        $records[] = $record;
		    }
		}
		// CSVファイルのデータを返す
		return $records;
	}

}
