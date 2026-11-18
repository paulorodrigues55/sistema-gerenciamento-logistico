<?php
require 'config.php'; $pdo=db(); $pageTitle='Endereços';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)($_POST['id']??0);$d=[trim($_POST['identificacao']??''),$_POST['tipo']??'Ambos',trim($_POST['cep']??''),trim($_POST['rua']??''),trim($_POST['numero']??''),trim($_POST['complemento']??''),trim($_POST['cidade']??''),strtoupper(substr(trim($_POST['estado']??''),0,2))];
 if($d[0]&&$d[3]&&$d[6]&&$d[7]){if($id){$s=$pdo->prepare("UPDATE enderecos SET identificacao=?,tipo=?,cep=?,rua=?,numero=?,complemento=?,cidade=?,estado=? WHERE id=?");$s->execute([...$d,$id]);}else{$s=$pdo->prepare("INSERT INTO enderecos(identificacao,tipo,cep,rua,numero,complemento,cidade,estado) VALUES(?,?,?,?,?,?,?,?)");$s->execute($d);}$_SESSION['flash']='Endereço salvo com sucesso.';}
 header('Location: enderecos.php');exit;
}
if(isset($_GET['excluir'])){try{$s=$pdo->prepare("DELETE FROM enderecos WHERE id=?");$s->execute([(int)$_GET['excluir']]);$_SESSION['flash']='Endereço excluído.';}catch(Throwable $e){$_SESSION['flash']='Endereço vinculado a frete: não pode ser excluído.';}header('Location: enderecos.php');exit;}
$edit=null;if(isset($_GET['editar'])){$s=$pdo->prepare("SELECT * FROM enderecos WHERE id=?");$s->execute([(int)$_GET['editar']]);$edit=$s->fetch();}
$items=$pdo->query("SELECT * FROM enderecos ORDER BY id DESC")->fetchAll();require 'includes/header.php';
?>
<div class="page-heading"><div><h1 class="h3 fw-bold">Endereços</h1><div class="text-secondary">Cadastre locais de origem e destino</div></div></div>
<div class="card p-3 mb-4"><h2 class="h5"><?= $edit?'Editar endereço':'Novo endereço' ?></h2><form method="post" class="row g-3"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
<div class="col-md-4"><label class="form-label">Identificação *</label><input required class="form-control" name="identificacao" placeholder="Ex.: Depósito central" value="<?= htmlspecialchars($edit['identificacao']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">Tipo</label><select class="form-select" name="tipo"><?php foreach(['Origem','Destino','Ambos'] as $t): ?><option <?= (($edit['tipo']??'Ambos')===$t)?'selected':'' ?>><?= $t ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><label class="form-label">CEP</label><input class="form-control" name="cep" value="<?= htmlspecialchars($edit['cep']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">Estado (UF) *</label><input required maxlength="2" class="form-control text-uppercase" name="estado" value="<?= htmlspecialchars($edit['estado']??'') ?>"></div>
<div class="col-md-6"><label class="form-label">Rua *</label><input required class="form-control" name="rua" value="<?= htmlspecialchars($edit['rua']??'') ?>"></div>
<div class="col-md-2"><label class="form-label">Número</label><input class="form-control" name="numero" value="<?= htmlspecialchars($edit['numero']??'') ?>"></div>
<div class="col-md-4"><label class="form-label">Complemento</label><input class="form-control" name="complemento" value="<?= htmlspecialchars($edit['complemento']??'') ?>"></div>
<div class="col-md-8"><label class="form-label">Cidade *</label><input required class="form-control" name="cidade" value="<?= htmlspecialchars($edit['cidade']??'') ?>"></div>
<div class="col-12"><button class="btn btn-primary">Salvar endereço</button> <a class="btn btn-outline-secondary" href="enderecos.php">Limpar</a></div></form></div>
<div class="card p-3"><h2 class="h5">Endereços cadastrados</h2><div class="table-responsive"><table class="table"><thead><tr><th>Identificação</th><th>Tipo</th><th>Endereço</th><th>Ações</th></tr></thead><tbody>
<?php foreach($items as $a): ?><tr><td><?= htmlspecialchars($a['identificacao']) ?></td><td><?= htmlspecialchars($a['tipo']) ?></td><td><?= htmlspecialchars($a['rua'].', '.$a['numero'].' - '.$a['cidade'].'/'.$a['estado']) ?></td><td><a href="?editar=<?= $a['id'] ?>">Editar</a> · <a class="text-danger" data-confirm="Excluir este endereço?" href="?excluir=<?= $a['id'] ?>">Excluir</a></td></tr><?php endforeach; ?>
<?php if(!$items): ?><tr><td colspan="4" class="text-secondary">Nenhum endereço cadastrado.</td></tr><?php endif; ?></tbody></table></div></div>
<?php require 'includes/footer.php'; ?>
