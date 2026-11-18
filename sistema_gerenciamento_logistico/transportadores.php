<?php
require 'config.php';$pdo=db();$pageTitle='Transportadores';
if($_SERVER['REQUEST_METHOD']==='POST'){$id=(int)($_POST['id']??0);$d=[trim($_POST['nome']??''),trim($_POST['documento']??''),trim($_POST['telefone']??''),trim($_POST['veiculo']??'')];if($d[0]){if($id){$s=$pdo->prepare("UPDATE transportadores SET nome=?,documento=?,telefone=?,veiculo=? WHERE id=?");$s->execute([...$d,$id]);}else{$s=$pdo->prepare("INSERT INTO transportadores(nome,documento,telefone,veiculo) VALUES(?,?,?,?)");$s->execute($d);}$_SESSION['flash']='Transportador salvo com sucesso.';}header('Location: transportadores.php');exit;}
if(isset($_GET['excluir'])){try{$s=$pdo->prepare("DELETE FROM transportadores WHERE id=?");$s->execute([(int)$_GET['excluir']]);$_SESSION['flash']='Transportador excluído.';}catch(Throwable $e){$_SESSION['flash']='Transportador vinculado a frete: não pode ser excluído.';}header('Location: transportadores.php');exit;}
$edit=null;if(isset($_GET['editar'])){$s=$pdo->prepare("SELECT * FROM transportadores WHERE id=?");$s->execute([(int)$_GET['editar']]);$edit=$s->fetch();}
$items=$pdo->query("SELECT * FROM transportadores ORDER BY id DESC")->fetchAll();require 'includes/header.php';
?>
<div class="page-heading"><div><h1 class="h3 fw-bold">Transportadores</h1><div class="text-secondary">Cadastro de responsáveis pelo transporte</div></div></div>
<div class="card p-3 mb-4"><h2 class="h5"><?= $edit?'Editar transportador':'Novo transportador' ?></h2><form method="post" class="row g-3"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
<div class="col-md-5"><label class="form-label">Nome *</label><input required class="form-control" name="nome" value="<?= htmlspecialchars($edit['nome']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">CPF/CNPJ</label><input class="form-control" name="documento" value="<?= htmlspecialchars($edit['documento']??'') ?>"></div>
<div class="col-md-2"><label class="form-label">Telefone</label><input class="form-control" name="telefone" value="<?= htmlspecialchars($edit['telefone']??'') ?>"></div>
<div class="col-md-2"><label class="form-label">Veículo</label><input class="form-control" name="veiculo" value="<?= htmlspecialchars($edit['veiculo']??'') ?>"></div>
<div class="col-12"><button class="btn btn-primary">Salvar transportador</button> <a class="btn btn-outline-secondary" href="transportadores.php">Limpar</a></div></form></div>
<div class="card p-3"><h2 class="h5">Transportadores cadastrados</h2><div class="table-responsive"><table class="table"><thead><tr><th>Nome</th><th>Documento</th><th>Telefone</th><th>Veículo</th><th>Ações</th></tr></thead><tbody>
<?php foreach($items as $t): ?><tr><td><?= htmlspecialchars($t['nome']) ?></td><td><?= htmlspecialchars($t['documento']??'') ?></td><td><?= htmlspecialchars($t['telefone']??'') ?></td><td><?= htmlspecialchars($t['veiculo']??'') ?></td><td><a href="?editar=<?= $t['id'] ?>">Editar</a> · <a class="text-danger" data-confirm="Excluir este transportador?" href="?excluir=<?= $t['id'] ?>">Excluir</a></td></tr><?php endforeach; ?>
<?php if(!$items): ?><tr><td colspan="5" class="text-secondary">Nenhum transportador cadastrado.</td></tr><?php endif; ?></tbody></table></div></div>
<?php require 'includes/footer.php'; ?>
