CREATE DATABASE IF NOT EXISTS gerenciador_logistico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gerenciador_logistico;

CREATE TABLE IF NOT EXISTS produtos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(120) NOT NULL,
 descricao TEXT NULL,
 peso DECIMAL(10,2) DEFAULT 0,
 quantidade INT DEFAULT 1,
 codigo VARCHAR(40) NULL,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enderecos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 identificacao VARCHAR(80) NOT NULL,
 tipo ENUM('Origem','Destino','Ambos') NOT NULL DEFAULT 'Ambos',
 cep VARCHAR(12) NULL,
 rua VARCHAR(150) NOT NULL,
 numero VARCHAR(20) NULL,
 complemento VARCHAR(100) NULL,
 cidade VARCHAR(100) NOT NULL,
 estado CHAR(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS transportadores (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(120) NOT NULL,
 documento VARCHAR(20) NULL,
 telefone VARCHAR(30) NULL,
 veiculo VARCHAR(80) NULL
);

CREATE TABLE IF NOT EXISTS fretes (
 id INT AUTO_INCREMENT PRIMARY KEY,
 transportador_id INT NOT NULL,
 origem_id INT NOT NULL,
 destino_id INT NOT NULL,
 data_frete DATE NOT NULL,
 status ENUM('Pendente','Em trânsito','Concluído') DEFAULT 'Pendente',
 observacoes TEXT NULL,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_frete_transportador FOREIGN KEY (transportador_id) REFERENCES transportadores(id),
 CONSTRAINT fk_frete_origem FOREIGN KEY (origem_id) REFERENCES enderecos(id),
 CONSTRAINT fk_frete_destino FOREIGN KEY (destino_id) REFERENCES enderecos(id)
);

CREATE TABLE IF NOT EXISTS frete_produtos (
 frete_id INT NOT NULL,
 produto_id INT NOT NULL,
 PRIMARY KEY (frete_id, produto_id),
 FOREIGN KEY (frete_id) REFERENCES fretes(id) ON DELETE CASCADE,
 FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
