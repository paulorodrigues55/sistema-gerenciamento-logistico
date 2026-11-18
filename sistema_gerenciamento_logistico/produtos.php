<?php
require 'config.php'; $pdo=db(); $pageTitle='Produtos';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $id=(int)($_POST['id']??0); $data=[trim($_POST['nome']??''),trim($_POST['descricao']??''),(float)($_POST['peso']??0),(int)($_POST['quantidade']??1),trim($_POST['codigo']??'')];
 if ($data[0] !== '') {
  if ($id) { $s=$pdo->prepare("UPDATE produtos SET nome=?,descricao=?,peso=?,quantidade=?,codigo=? WHERE id=?"); $s->execute([...$data,$id]); }
  else { $s=$pdo->prepare("INSERT INTO produtos(nome,descricao,peso,quantidade,codigo) VALUES(?,?,?,?,?)"); $s->execute($data); }
  $_SESSION['flash']='Produto salvo com sucesso.';
 }
 header('Location: produtos.php'); exit;
}
if (isset($_GET['excluir'])) { $s=$pdo->prepare("DELETE FROM produtos WHERE id=?"); try{$s->execute([(int)$_GET['excluir']]);$_SESSION['flash']='Produto excluído.';}catch(Throwable $e){$_SESSION['flash']='Não é possível excluir: produto vinculado a frete.';} header('Location: produtos.php');exit; }
$edit=null; if(isset($_GET['editar'])){$s=$pdo->prepare("SELECT * FROM produtos WHERE id=?");$s->execute([(int)$_GET['editar']]);$edit=$s->fetch();}
$items=$pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
require 'includes/header.php';
?>
<div class="page-heading"><div><h1 class="h3 fw-bold">Produtos</h1><div class="text-secondary">Cadastro e edição de mercadorias</div></div></div>
<div class="card p-3 mb-4"><h2 class="h5"><?= $edit?'Editar produto':'Novo produto' ?></h2><form method="post" class="row g-3"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
<div class="col-md-6"><label class="form-label">Nome *</label><input required class="form-control" name="nome" value="<?= htmlspecialchars($edit['nome']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">Código</label><input class="form-control" name="codigo" value="<?= htmlspecialchars($edit['codigo']??'') ?>"></div>
<div class="col-md-3"><label class="form-label">Peso (kg)</label><input type="number" step="0.01" min="0" class="form-control" name="peso" value="<?= htmlspecialchars($edit['peso']??'0') ?>"></div>
<div class="col-md-3"><label class="form-label">Quantidade</label><input type="number" min="1" class="form-control" name="quantidade" value="<?= htmlspecialchars($edit['quantidade']??'1') ?>"></div>
<div class="col-md-9"><label class="form-label">Descrição</label><input class="form-control" name="descricao" value="<?= htmlspecialchars($edit['descricao']??'') ?>"></div>
<div class="col-12"><button class="btn btn-primary">Salvar produto</button> <a class="btn btn-outline-secondary" href="produtos.php">Limpar</a></div></form></div>
<div class="card p-3"><h2 class="h5">Produtos cadastrados</h2><div class="table-responsive"><table class="table"><thead><tr><th>Nome</th><th>Código</th><th>Peso</th><th>Quantidade</th><th>Ações</th></tr></thead><tbody>
<?php foreach($items as $p): ?><tr><td><?= htmlspecialchars($p['nome']) ?></td><td><?= htmlspecialchars($p['codigo']??'') ?></td><td><?= number_format($p['peso'],2,',','.') ?> kg</td><td><?= (int)$p['quantidade'] ?></td><td><a href="?editar=<?= $p['id'] ?>">Editar</a> · <a class="text-danger" data-confirm="Excluir este produto?" href="?excluir=<?= $p['id'] ?>">Excluir</a></td></tr><?php endforeach; ?>
<?php if(!$items): ?><tr><td colspan="5" class="text-secondary">Nenhum produto cadastrado.</td></tr><?php endif; ?></tbody></table></div></div>
<?php require 'includes/footer.php'; ?>
