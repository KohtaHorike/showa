<?php
session_start();
	$company ="";
	$kname="";
	$email="";
	$tel="";
	$inquiry="";
	$postflg="0";

function connectDb(){
	return new PDO("mysql:dbname=showa;host=localhost;charset=utf8","root","root");
}
function h($str){
	return htmlspecialchars($str,ENT_QUOTES);
}
function kana($str){
	return mb_convert_kana($str,"rna");
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
	$postflg="1";
	$dbh = connectDb();
//	var_dump($_POST);
	if(isset($_POST["submit"])){
		$company= h($_POST["company"]);
		$kname= h($_POST["kname"]);
		$email= kana(h($_POST["email"]));
		$tel= h($_POST["tel"]);
		$memo= h($_POST["memo"]);
	//登録開始
		try{
			$dbh->beginTransaction();//開始
			$sql = "INSERT INTO inquiry(name,company,email,phone,memo,created,modified) 
			VALUES(:name,:company,:email,:phone,:memo,now(),now())";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":name",$kname,PDO::PARAM_STR);
			$stmt->bindParam(":company",$company,PDO::PARAM_STR);
			$stmt->bindParam(":email",$email,PDO::PARAM_STR);
			$stmt->bindParam(":phone",$tel,PDO::PARAM_STR);
			$stmt->bindParam(":memo",$memo,PDO::PARAM_STR);
			$stmt->execute();
			$dbh->commit();//終了
			$_SESSION["inquiry"]=$_POST;
			mb_send_mail($email,"【自動返信】お問い合わせありがとうございます", "この度はお問い合わせありがとうございます。担当者より折り返しご連絡させsていただきます。");
			header("Location:submit.php");
		}catch(PDOException $e){
			echo $e->getMessage();
			exit;
		}
	}else{
		echo "不正な送信です";
	}
}


?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="大阪,金型,プラスチック,樹脂,インジェクション,成型">
    <meta name="author" content="Kohta Horike works">
    <meta name="generator" content="coda2">

    <title>お問い合わせ | 昭和金型製作所</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/common.css" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  
  
	<div class="header" >
	<div class="container">
	  <div class="row">
				<h1 style="font-size:12px;">大阪　樹脂・プラスティック　金型</h1>
	  </div>
	  <div class="row">
				<div class="col-md-offset-1 col-md-4">
				<img src="../image/logo_001.gif">
				</div>
				
				<div class="col-md-offset-2 col-md-5">
				<p class="h3">お問い合わせ：06-6961-4728</p>
				</div>
				
	  </div>
	</div>
	</div> <!-- header end-->
	
	<div class="menu">  
		<div class="container">
		<div class="row">
			<div class="col-md-offset-1 col-md-10 col-md-offset-1">
				<ul class="nav nav-pills nav-justified">
					<li><a href="../index_01.html">TOP</a></li>
					<li><a href="../company/index.html" class="active">会社案内</a></li>
					<li><a href="../facility/index.html">導入設備</a></li>
					<li><a href="../biography/index.html">沿革</a></li>
					<li class="active"><a href="../inquiry/index.php">お問い合わせ</a></li>
				</ul>
			</div>
		</div>
		</div>
	</div><!-- menu end-->
	<div class="company">
		<div class="container">
		<div class="row">
		
			<div class="row">
			<div class="col-md-offset-1 col-md-10">
			<ol class="breadcrumb">
			<li><a href="../index.html">Home</a></li>
			<li class="active">お問い合わせ</li>
			</ol>
			</div>
			</div>

			
			<div class="col-md-offset-1 col-md-10">
				<div class="page-header">
				<h2>お問い合わせ</h2>
				<p><span class="red">*</span>は必須記入項目です</p>
				</div>
				<div class="panel-info panel-default">
					<div class="panel-heading">お問い合わせ</div>
					<form action="<?php echo $_SERVER["SCRIPT_NAME"];?>" method="POST" onsubmit="check();">
					<table class="table table-bordered table-hover">
						<tr>
						<th>貴社名<span class="red">*</span></th>
						<td><input type="text" name="company" placeholder="例）ABC株式会社" 
						value="<?php if(isset($_POST["company"])){echo $_POST["company"];}?>" required autofocus></td></tr>

						<tr>
						<th>お名前<span class="red">*</span></th>
						<td><input type="name" name="kname" placeholder="例）昭和　太郎" 
						value="<?php if(isset($_POST["kname"])){echo $_POST["kname"];}?>" required></td>
						</tr>

						<tr><th>メールアドレス<span class="red">*</span></th>
						<td><input type="email" name="email" placeholder="例）showa@showa.com" 
						value="<?php if(isset($_POST["email"])){echo $_POST["email"];}?>" required></td>
						</tr>

						<tr><th>お電話番号<span class="red">*</span></th>
						<td><input type="tel" name="tel" placeholder="例）123-4567-8901" 
						value="<?php if(isset($_POST["tel"])){echo $_POST["tel"];}?>" required></td>
						</tr>
						<tr>

						<th>お問い合わせ内容<span class="red">*</span></th>
						<td><textarea name="memo" id="" cols="30" rows="10" required></textarea></td>
						</tr>

						<tr><td colspan="2"><button type="submit" name="submit">送信</button></td></tr>
					</table>
					</form>
					</div>

			</div>			
		</div><!-- row -->
		</div><!-- container -->
	</div><!-- company ends-->
	<!-- FOOTER AREA --> 
		<div class="footer">
		<div id="text">
			<p>All contents reserved by Showa MOLD Prv.,Ltd.</p>
		</div>
		</div><!-- footer end--> 


  	</div>
  	<script>
 	function check(){
 	alert("送信しますか？");
 	}
  	</script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>