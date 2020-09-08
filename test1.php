<?php
include_once ('global.php');
if (isset($_POST["send"])) {
$leadExcel=$_POST["leadExcel"];;
ini_set("max_execution_time", "300");
ini_set('memory_limit', '1024M');
if($leadExcel == "true"){

//獲取上傳的文件名
$filename = $_FILES['inputExcel']['name'];

//上傳到服務器上的臨時文件名
$tmp_name = $_FILES['inputExcel']['tmp_name'];
$msg = uploadFile($filename,$tmp_name);
}
}
if (isset($_POST["clear"])) {
$sql = "TRUNCATE TABLE net_mailuser";
if(!mysql_query($sql)){
return false;
}
echo '<script>alert(\'電子報會員資料已清空！\');window.location=\'test1.php\';</script>';
}
?>

<form name="form2" method="post" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<input type="hidden" name="leadExcel" value="true">
<table align="center" width="90%" border="0">
<tr>
<td>
<input type="file" name="inputExcel"><input type="submit" value="go" name="send">
</td>
</tr>
<tr>
<td>
<input type="submit" value="rest" name="clear">
</td>
</tr>
</table>
</form>


<?
//導入Excel文件
function uploadFile($file,$filetempname) {
	//自己設置的上傳文件存放路徑
	$filePath = 'uploads/';
	$str = "";

	require_once("PHPExcel/Classes/PHPExcel/IOFactory.php");

	$filename=explode(".",$file);//把上傳的文件名以「.」好為準做一個數組。
	$time=date("y-m-d-H-i-s");//去當前上傳的時間
	$filename[0]=$time;//取文件名t替換
	$name=implode(".",$filename); //上傳後的文件名
	$uploadfile=$filePath.$name;//上傳後的文件名地址

	//move_uploaded_file() 函數將上傳的文件移動到新位置。若成功，則返回 true，否則返回 false。
	$result=move_uploaded_file($filetempname,$uploadfile);//假如上傳到當前目錄下
	if($result) { //如果上傳文件成功，就執行導入excel操作
		
		$objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		$highestRow = $sheet->getHighestRow(); // 取得總行數
		$highestColumn = $sheet->getHighestColumn(); // 取得總列數

		//循環讀取excel文件,讀取一條,插入一條
		for($j=2;$j<=$highestRow;$j++){
			for($k='A';$k<=$highestColumn;$k++){
				$str .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';
			//讀取單元格
			}
		//explode:函數把字符串分割為數組。
		$strs = explode("\\",$str);
		$sql = "INSERT INTO colour_manage(pan,guest,Book_Name,content,number,proportion) VALUES('$strs[0]','$strs[1]','$strs[2]','$strs[3]','$strs[4]','$strs[5]')";
		if(!mysql_query($sql)){
			return false;
		}
			$str = "";
		}

		unlink($uploadfile); //刪除上傳的excel文件
		echo '<script>alert(\'ok\');window.location=\'test1.php\';</script>';
	}else{
		echo '<script>alert(\'no\');window.location=\'test1.php\';</script>';
	}
}
?>