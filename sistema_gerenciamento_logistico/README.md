# LogiGest — Sistema de Gerenciamento Logístico

Projeto acadêmico desenvolvido para a disciplina **Fundamentos de Programação para Internet** (UNIPAR EAD).

## Funcionalidades
- Painel com indicadores de produtos, endereços, transportadores e fretes.
- Cadastro, edição e exclusão de produtos.
- Cadastro, edição e exclusão de endereços (origem, destino ou ambos).
- Cadastro, edição e exclusão de transportadores.
- Geração de fretes relacionando um transportador, origem, destino e um ou vários produtos.
- Relatório de fretes por data com exportação CSV.

## Tecnologias
PHP 8+, MySQL/MariaDB, HTML5, CSS3, JavaScript e Bootstrap 5.3.

## Como executar no XAMPP
1. Copie a pasta `sistema_gerenciamento_logistico` para `C:\xampp\htdocs\`.
2. Inicie Apache e MySQL no painel do XAMPP.
3. Abra `http://localhost/phpmyadmin`.
4. Importe o arquivo `database/schema.sql`.
5. Confira usuário e senha do banco em `config.php` (padrão XAMPP: usuário `root`, senha vazia).
6. Acesse `http://localhost/sistema_gerenciamento_logistico/`.

## Fluxo de demonstração
1. Cadastre um produto.
2. Cadastre dois endereços (um para origem e outro para destino).
3. Cadastre um transportador.
4. Gere um frete e associe um ou mais produtos.
5. Abra Relatórios, selecione a data do frete e baixe o CSV.

## Estrutura
- `index.php`: painel inicial.
- `produtos.php`: cadastro de produtos.
- `enderecos.php`: cadastro de endereços.
- `transportadores.php`: cadastro de transportadores.
- `fretes.php`: geração e listagem de fretes.
- `relatorios.php`: relatório diário e exportação CSV.
- `database/schema.sql`: criação do banco e tabelas.
- `includes/`: cabeçalho e rodapé compartilhados.
- `assets/`: CSS e JavaScript.

## Observação acadêmica
Este repositório contém uma implementação inicial funcional para estudo e demonstração. Revise, teste e adapte o código, compreenda as funções e siga as orientações do professor antes da entrega. O relatório é exportado em CSV, que pode ser aberto no Excel ou LibreOffice.
