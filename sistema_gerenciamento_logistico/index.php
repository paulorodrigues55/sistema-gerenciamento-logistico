<?php
require 'config.php';
$pageTitle = 'Painel';
try {
 $pdo=db();
 $counts = [
  'produtos'=>(int)$pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn(),
  'enderecos'=>(int)$pdo->query("SELECT COUNT(*) FROM enderecos")->fetchColumn(),
  'transportadores'=>(int)$pdo->query("SELECT COUNT(*) FROM transportadores")->fetchColumn(),
  'fretes'=>(int)$pdo->query("SELECT COUNT(*) FROM fretes")->fetchColumn()
 ];
 $recent=$pdo->query("SELECT f.id,f.data_frete,f.status,t.nome transportador FROM fretes f JOIN transportadores t ON t.id=f.transportador_id ORDER BY f.id DESC LIMIT 5")->fetchAll();
} catch(Throwable $e) { $counts=['produtos'=>0,'enderecos'=>0,'transportadores'=>0,'fretes'=>0]; $recent=[]; $dbError=true; }
require 'includes/header.php';
?>
<div class="page-heading"><div><h1 class="h3 fw-bold mb-1">Painel de controle</h1><div class="text-secondary">Visão geral das operações logísticas</div></div><a class="btn btn-primary" href="fretes.php?novo=1">+ Gerar frete</a></div>
<?php if (!empty($dbError)): ?><div class="alert alert-warning">Banco de dados ainda não conectado. Importe <code>database/schema.sql</code> no phpMyAdmin e atualize esta página.</div><?php endif; ?>
<div class="row g-3 mb-4">
<?php foreach(['produtos'=>'Produtos','enderecos'=>'Endereços','transportadores'=>'Transportadores','fretes'=>'Fretes'] as $k=>$label): ?>
<div class="col-6 col-lg-3"><div class="card card-stat p-3"><div class="muted-label"><?= $label ?></div><div class="display-6 fw-bold"><?= $counts[$k] ?></div><a class="small text-decoration-none" href="<?= $k ?>.php">Acessar cadastro →</a></div></div>
<?php endforeach; ?>
</div>
<div class="card p-3"><h2 class="h5 fw-bold">Fretes recentes</h2><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nº</th><th>Data</th><th>Transportador</th><th>Status</th></tr></thead><tbody>
<?php if (!$recent): ?><tr><td colspan="4" class="text-secondary">Nenhum frete cadastrado até o momento.</td></tr><?php endif; ?>
<?php foreach($recent as $r): ?><tr><td>#<?= $r['id'] ?></td><td><?= date('d/m/Y',strtotime($r['data_frete'])) ?></td><td><?= htmlspecialchars($r['transportador']) ?></td><td><span class="badge text-bg-light"><?= htmlspecialchars($r['status']) ?></span></td></tr><?php endforeach; ?>
</tbody></table></div></div>
<?php require 'includes/footer.php'; ?>
