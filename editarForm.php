<?php

	error_reporting( ~E_NOTICE );
	
	require_once 'dbconfig.php';
	
	if(isset($_GET['editar_id']) && !empty($_GET['editar_id']))
	{
		$id = $_GET['editar_id'];
		$stmt_edit = $DB_con->prepare('SELECT nome_completo, endereco, contato, imagemUsuario, idiomas, habilidades_competencias, experiencias FROM tbl_usuario WHERE idUsuario =:idU');
		$stmt_edit->execute(array(':idU'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: index.php");
	}
	
	
	
	if(isset($_POST['btn_save_updates']))
	{
		$nomeCompleto = $_POST['nome_completo'];// Nome Completo
		$endereco = $_POST['endereco'];// Endereço
		$contato = $_POST['contato'];// Contato
		$educacao = $_POST['educacao']; // Educação
		$idiomas = $_POST['idiomas'];// Idiomas
		$habilidades_competencias = $_POST['habilidades_competencias'];// Habilidades e Competências
		$experiencias = $_POST['experiencias'];// Experiências

			
		$arquivoImagem = $_FILES['imagem_usuario']['name'];  // Imagem
		$dir_tmp = $_FILES['imagem_usuario']['tmp_name'];
		$tamanhoImagem = $_FILES['imagem_usuario']['size'];
					
		if($arquivoImagem)
		{
			$dir_upload = 'imagem_usuarios/'; // Diretório de upload
			$ext_imagem = strtolower(pathinfo($arquivoImagem,PATHINFO_EXTENSION)); // Pega extensão da Imagem
			$extensoes_validas = array('jpeg', 'jpg', 'png', 'gif'); // Extensões Válidas
			$imgUsuario = rand(1000,1000000).".".$ext_imagem;
			if(in_array($ext_imagem, $extensoes_validas))
			{			
				if($tamanhoImagem < 5000000)
				{
					unlink($dir_upload.$edit_row['imagemUsuario']);
					move_uploaded_file($dir_tmp,$dir_upload.$imgUsuario);
				}
				else
				{
					$msgErro = "Desculpe, Seu arquivo é muito grande, deve ter no máximo 5MB";
				}
			}
			else
			{
				$msgErro = "Desculpe, Somente são permitidos arquivos JPG, JPEG, PNG & GIF.";		
			}	
		}
		else
		{
			// Se a imagem não for trocada, permanece a mesma
			$imgUsuario = $edit_row['imagemUsuario']; // imagem antiga do banco
		}	
						
		
		// Se não tiver erros, continuar ....
		if(!isset($msgErro))
		{
			$stmt = $DB_con->prepare('UPDATE tbl_usuario 
									     SET nome_completo=:nomeU, 
										     endereco=:endU, 
										     contato=:contU,
											 imagemUsuario=:imgU,
											 educacao=:edU,
											 idiomas=:idiomasU,
											 habilidades_competencias=:hab_compU,
											 experiencias=:expU
								       WHERE idUsuario=:idU');
			$stmt->bindParam(':nomeU',$nomeCompleto);
			$stmt->bindParam(':endU',$endereco);
			$stmt->bindParam(':contU',$contato);
			$stmt->bindParam(':imgU',$imgUsuario);
			$stmt->bindParam(':edU', $educacao);
			$stmt->bindParam(':idiomasU',$idiomas);
			$stmt->bindParam(':hab_compU',$habilidades_competencias);
			$stmt->bindParam(':expU',$experiencias);
			$stmt->bindParam(':idU',$id);
				
			if($stmt->execute()){
				?>
                <script>
				alert('Atualizado com Sucesso ...');
				window.location.href='index.php';
				</script>
                <?php
			}
			else{
				$msgErro = "Não foi possível atualizar os dados !";
			}
		
		}
		
						
	}
	
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" content="pt-br"/>
<title>Gerenciador de Currículos</title>

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

<!-- custom stylesheet -->
<link rel="stylesheet" href="style.css">

<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<script src="jquery-1.11.3-jquery.min.js"></script>
</head>
<body>

<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
 

		<div class="navbar-header">
            <a class="navbar-brand" href="#" title='Programming Blog'>Projeto PHP</a>
            <a class="navbar-brand" href="index.php">Home</a>
        </div>
        </div>
 
    </div>
</div>


<div class="container">


	<div class="page-header">
    	<h1 class="h2">Atualizar Curriculum <a class="btn btn-default" href="index.php"> Todos os Perfis </a></h1>
    </div>

<div class="clearfix"></div>

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	
    
    <?php
	if(isset($msgErro)){
		?>
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $msgErro; ?>
        </div>
        <?php
	}
	?>
   
    
	<table class="table table-bordered table-responsive">
	
    <tr>
    	<td><label class="control-label">Nome Completo</label></td>
        <td><input class="form-control" type="text" name="nome_completo" value="<?php echo $nome_completo; ?>" required /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Endereço</label></td>
        <td><input class="form-control" type="text" name="endereco" value="<?php echo $endereco; ?>" required /></td>
    </tr>

	<tr>
    	<td><label class="control-label">Contato</label></td>
        <td><input class="form-control" type="text" name="contato" value="<?php echo $contato; ?>" required /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Imagem de Perfil</label></td>
        <td>
        	<p><img src="imagem_usuarios/<?php echo $imagemUsuario; ?>" height="150" width="150" style="margin-left:80px;"/>
        	<input class="input-group" type="file" name="imagem_usuario" accept="image/*" style="float:left;top:3.5vw;"  /></p>
        </td>
    </tr>

	<tr>
    	<td><label class="control-label">Educação</label></td>
        <td><input class="form-control" type="text" name="educacao" value="<?php echo $educacao; ?>" required /></td>
    </tr>

	<tr>
    	<td><label class="control-label">Idiomas</label></td>
        <td><textarea class="form-control" name="idiomas" wrap="soft" required rows="5"><?php echo $idiomas; ?></textarea></td>
    </tr>

	<tr>
    	<td><label class="control-label">Habilidades e Competências</label></td>
        <td><textarea class="form-control" name="habilidades_competencias" wrap="soft" required rows="5"><?php echo $habilidades_competencias; ?></textarea></td>
    </tr>

	<tr>
    	<td><label class="control-label">Experiências</label></td>
        <td><textarea class="form-control" name="experiencias" wrap="soft" required rows="5"><?php echo $experiencias; ?></textarea></td>
    </tr>
    
    <tr>
        <td colspan="2"><button type="submit" name="btn_save_updates" class="btn btn-default">
        <span class="glyphicon glyphicon-save"></span> Atualizar
        </button>
        
        <a class="btn btn-default" href="index.php"> <span class="glyphicon glyphicon-backward"></span> Cancelar </a>
        
        </td>
    </tr>
    
    </table>
    
</form>

</div>
</body>
</html>