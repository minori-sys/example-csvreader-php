<?php

// CsvUtilityクラスを読み込む
require 'CsvUtility.php';

// インスタンスを生成
$csv = new CsvUtility();
 
// メソッドの呼出し 
// とりあえず、結果をprint_rで出力していますが、お好きに改造してください
print_r($csv->getCsvList());
