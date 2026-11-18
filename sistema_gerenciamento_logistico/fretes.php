<?php
require 'config.php';$pdo=db();$pageTitle='Fretes';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $transportador=(int)($_POST['transportador_id']??0);$origem=(int)($_POST['origem_id']??0);$destino=(int)($_POST['destino_id']??0);$data=$_POST['data_frete']??date('Y-m-d');$status=$_POST['status']??'Pendente';$obs=trim($_POST['observacoes']??'');$produtos=array_values(array_unique(array_filter(array_map('intval',$_POST['produtos']??[]))));
 if($transportador&&$origem&&$destino&&$origem!==$destino&&count($produtos)>0){
  $pdo->beginTransaction();try{$s=$pdo->prepare("INSERT INTO fretes(transportador_id,origem_id,destino_id,data_frete,status,observacoes) VALUES(?,?,?,?,?,?)");$s->execute([$transportador,$origem,$destino,$data,$status,$obs]);$freteId=(int)$pdo->lastInsertId();$link=$pdo->prepare("INSERT INTO frete_produtos(frete_id,produto_id) VALUES(?,?)");foreach($produtos as $pid)$link->execute([$freteId,$pid]);$pdo->commit();$_SESSION['flash']='Frete #'.$freteId.' gerado com sucesso.';}catch(Throwable $e){$pdo->rollBack();$_SESSION['flash']='Não foi possível gerar o frete. Confira os dados selecionados.';}
 }else{$_SESSION['flash']='Preencha transportador, origem, destino e ao menos um produto. Origem e destino devem ser diferentes.';}
 header('Location: fretes.php');exit;
}
$transportadores=$pdo->query("SELECT id,nome FROM transportadores ORDER BY nome")->fetchAll();$enderecos=$pdo->query("SELECT id,identificacao,tipo,cidade,estado FROM enderecos ORDER BY identificacao")->fetchAll();$produtos=$pdo->query("SELECT id,nome,codigo FROM produtos ORDER BY nome")->fetchAll();
$items=$pdo->query("SELECT f.*,t.nome transportador,o.identificacao origem,d.identificacao destino,(SELECT COUNT(*) FROM frete_produtos fp WHERE fp.frete_id=f.id) total_produtos FROM fretes f JOIN transportadores t ON t.id=f.transportador_id JOIN enderecos o ON o.id=f.origem_id JOIN enderecos d ON d.id=f.destino_id ORDER BY f.id DESC")->fetchAll();
require 'includes/header.php';
?>
<div class="page-heading"><div><h1 class="h3 fw-bold">Fretes</h1><div class="text-secondary">Relacione transportador, rota e vários produtos em um único frete</div></div></div>
<div class="card p-3 mb-4"><h2 class="h5">Gerar novo frete</h2>
<?php if(!$transportadores||count($enderecos)<2||!$produtos): ?><div class="alert alert-warning mb-0">Antes de gerar um frete, cadastre ao menos um transportador, dois endereços e um produto.</div><?php else: ?>
<form method="post" class="row g-3">
<div class="col-md-4"><label class="form-label">Transportador *</label><select required class="form-select" name="transportador_id"><option value="">Selecione</option><?php foreach($transportadores as $t): ?><option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-4"><label class="form-label">Origem *</label><select required class="form-select" name="origem_id"><option value="">Selecione</option><?php foreach($enderecos as $a): ?><option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['identificacao'].' — '.$a['cidade'].'/'.$a['estado']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-4"><label class="form-label">Destino *</label><select required class="form-select" name="destino_id"><option value="">Selecione</option><?php foreach($enderecos as $a): ?><option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['identificacao'].' — '.$a['cidade'].'/'.$a['estado']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-3"><label class="form-label">Data do frete *</label><input required type="date" class="form-control" name="data_frete" value="<?= date('Y-m-d') ?>"></div>
<div class="col-md-3"><label class="form-label">Status</label><select class="form-select" name="status"><option>Pendente</option><option>Em trânsito</option><option>Concluído</option></select></div>
<div class="col-md-6"><label class="form-label">Observações</label><input class="form-control" name="observacoes"></div>
<div class="col-md-8"><label class="form-label">Adicionar produtos *</label><div class="input-group"><select class="form-select" id="produto-select"><option value="">Selecione um produto</option><?php foreach($produtos as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome'].($p['codigo']?' — '.$p['codigo']:'')) ?></option><?php endforeach; ?></select><button type="button" class="btn btn-outline-primary" id="adicionar-produto">Adicionar</button></div><div id="lista-produtos" class="mt-2"></div></div>
<div class="col-12"><button class="btn btn-primary">Gerar frete</button></div></form><?php endif; ?></div>
<div class="card p-3"><h2 class="h5">Fretes cadastrados</h2><div class="table-responsive"><table class="table"><thead><tr><th>Nº</th><th>Data</th><th>Transportador</th><th>Origem</th><th>Destino</th><th>Produtos</th><th>Status</th></tr></thead><tbody>
<?php foreach($items as $f): ?><tr><td>#<?= $f['id'] ?></td><td><?= date('d/m/Y',strtotime($f['data_frete'])) ?></td><td><?= htmlspecialchars($f['transportador']) ?></td><td><?= htmlspecialchars($f['origem']) ?></td><td><?= htmlspecialchars($f['destino']) ?></td><td><?= (int)$f['total_produtos'] ?></td><td><?= htmlspecialchars($f['status']) ?></td></tr><?php endforeach; ?>
<?php if(!$items): ?><tr><td colspan="7" class="text-secondary">Nenhum frete cadastrado.</td></tr><?php endif; ?></tbody></table></div></div>
<?php require 'includes/footer.php'; ?>
