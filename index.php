<?php

require_once 'dbconfig.php';

if (isset($_GET['delete_id'])) {
    // Selecionar Imagem do Banco para deletar
    $stmt_select = $DB_con->prepare('SELECT imagemUsuario FROM tbl_usuario WHERE idUsuario =:idU');
    $stmt_select->execute(array(':idU' => $_GET['delete_id']));
    $imgRow = $stmt_select->fetch(PDO::FETCH_ASSOC);
    unlink("imagem_usuario/" . $imgRow['imagemUsuario']);

    // Irá deletar o registro atual do Banco
    $stmt_delete = $DB_con->prepare('DELETE FROM tbl_usuario WHERE idUsuario =:idU');
    $stmt_delete->bindParam(':idU', $_GET['delete_id']);
    $stmt_delete->execute();

    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" content="pt-br"/>
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<title>Gerenciador de Currículos</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
</head>

<body>

<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">

        <div class="navbar-header">
            <a class="navbar-brand" href="#" title='Programming Blog'>Lista de Currículos</a>
            <a class="navbar-brand" href="index.php">Home</a>
        </div>

    </div>
</div>

<div class="container">

	<div class="page-header">
    	<h1 class="h2">Currículos Cadastrados <a class="btn btn-default" href="addNovo.php" style="float:right;"> <span class="glyphicon glyphicon-plus"></span> &nbsp; Adicionar Novo Currículum</a></h1>
    </div>

<br />

<div class="row">
<?php

$stmt = $DB_con->prepare('SELECT idUsuario, nome_completo, endereco, contato, imagemUsuario, idiomas, habilidades_competencias  FROM tbl_usuario ORDER BY idUsuario DESC');
$stmt->execute();

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        ?>
			<div class="col-xs-3">
				<p class="page-header"><b><?php echo $nome_completo; ?></B></p>
				<img src="imagem_usuarios/<?php echo $row['imagemUsuario']; ?>" class="img-thumbnail" style="min-height:250px;" />
				<p class="page-header">
				<span>
				<a class="btn btn-info"  href="editarForm.php?editar_id=<?php echo $row['idUsuario']; ?>" title="Clique para Editar" onclick="return confirm('Ter certeza que deseja editar ?')"><span class="glyphicon glyphicon-edit"></span> Editar</a>
				<a class="btn btn-danger" href="?delete_id=<?php echo $row['idUsuario']; ?>" title="Clique para deletar" onclick="return confirm('Tem certeza que deseja deletar ?')"><span class="glyphicon glyphicon-remove-circle"></span> Deletar</a>
                <a class="btn btn-success" href="pdf.php?gerar_pdf=<?php echo $row['idUsuario']; ?>" target="_blank" title="Gerar PDF" onclick="return confirm('Deseja gerar um PDF para o perfil ?')"><span class="glyphicon glyphicon-file"></span>PDF</a>
				</span>
				</p>
			</div>
			<?php
}
} else {
    ?>
        <div class="col-xs-12">
        	<div class="alert alert-warning">
            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; Nenhuma informação encontrada ...
            </div>
        </div>
        <?php
}

?>
</div>



<!-- <div class="navbar navbar-default navbar-static-bottom" role="navigation">
    <div class="container">

        <div class="navbar-header text-center">
        <p>Copyright 2019&copy;</p>
        </div>

    </div>
</div> -->

<nav class="navbar fixed-bottom navbar-light bg-light" style="border-top:1px solid #eee;">
  <p class="navbar-brand font-weight-light" style="margin-left:40%;" href="#">Copyright&copy; 2019</p>
</nav>




<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>


</body>
</html>