-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.5.24-log - MySQL Community Server (GPL)
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              8.0.0.4396
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura do banco de dados para finances
CREATE DATABASE IF NOT EXISTS `finances` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `finances`;


-- Copiando estrutura para tabela finances.banco
CREATE TABLE IF NOT EXISTS `banco` (
  `id_banco` int(11) NOT NULL AUTO_INCREMENT,
  `nome_banco` varchar(200) DEFAULT NULL,
  `logo_banco` varchar(200) DEFAULT NULL,
  `ativo_banco` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_banco`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.banco: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `banco` DISABLE KEYS */;
INSERT INTO `banco` (`id_banco`, `nome_banco`, `logo_banco`, `ativo_banco`) VALUES
	(1, 'Banco Bradesco S/A', NULL, 1),
	(2, 'Banco do Brasil S/A', NULL, 1),
	(3, 'Caixa Econômica Federal S/A', '', 1);
/*!40000 ALTER TABLE `banco` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.cartao
CREATE TABLE IF NOT EXISTS `cartao` (
  `id_cartao` int(11) NOT NULL AUTO_INCREMENT,
  `bandeira_cartao` varchar(45) DEFAULT NULL,
  `limite_cartao` float DEFAULT NULL,
  `fechamento_cartao` int(11) DEFAULT NULL,
  `vencimento_cartao` int(11) DEFAULT NULL,
  `ativo_cartao` tinyint(1) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `descricao_cartao` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cartao`,`id_usuario`),
  KEY `fk_cartao_usuario1` (`id_usuario`),
  CONSTRAINT `fk_cartao_usuario1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.cartao: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `cartao` DISABLE KEYS */;
INSERT INTO `cartao` (`id_cartao`, `bandeira_cartao`, `limite_cartao`, `fechamento_cartao`, `vencimento_cartao`, `ativo_cartao`, `id_usuario`, `descricao_cartao`) VALUES
	(4, 'visa', 1599.99, 16, 25, 1, 1, 'Bradesco Internacional'),
	(5, 'visa', 1000, 20, 28, 1, 1, 'OuroCard'),
	(8, 'american_express', 5000, 20, 29, 0, 1, 'American EG'),
	(11, 'mastercard', 1500, 8, 15, 0, 1, 'Master International');
/*!40000 ALTER TABLE `cartao` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_categoria` varchar(200) DEFAULT NULL,
  `ativo_categoria` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.categoria: ~14 rows (aproximadamente)
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` (`id_categoria`, `descricao_categoria`, `ativo_categoria`) VALUES
	(1, 'Moradia', 1),
	(2, 'Saúde', 1),
	(3, 'Transporte', 1),
	(4, 'Salário', 1),
	(5, 'Alimentação', 1),
	(6, 'Lazer', 1),
	(7, 'Supermercado', 1),
	(8, 'Títulos', 1),
	(9, 'Outros', 1),
	(10, 'Tarifas', 1),
	(15, 'Investimentos', 1),
	(16, 'Juros', 0),
	(17, 'Presentes', 1),
	(18, 'Higiene', 1);
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.cidade
CREATE TABLE IF NOT EXISTS `cidade` (
  `id_cidade` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_cidade` varchar(500) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  PRIMARY KEY (`id_cidade`,`id_estado`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `fk_cidade_estado1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.cidade: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `cidade` DISABLE KEYS */;
INSERT INTO `cidade` (`id_cidade`, `descricao_cidade`, `id_estado`) VALUES
	(1, 'Belo Horizonte', 13),
	(2, 'Santa Luzia', 13),
	(3, 'São Paulo', 25),
	(4, 'Rio de Janeiro', 19),
	(5, 'Vespasiano', 13),
	(6, 'Itabuna', 5);
/*!40000 ALTER TABLE `cidade` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.conta
CREATE TABLE IF NOT EXISTS `conta` (
  `id_conta` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_conta` varchar(200) DEFAULT NULL,
  `id_tipo_conta` int(11) NOT NULL,
  `saldo_inicial` float DEFAULT NULL,
  `id_banco` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `ativo_conta` tinyint(1) DEFAULT NULL,
  `data_inclusao` datetime DEFAULT NULL,
  PRIMARY KEY (`id_conta`,`id_tipo_conta`,`id_usuario`),
  KEY `fk_conta_tipo_conta1` (`id_tipo_conta`),
  KEY `fk_conta_banco1` (`id_banco`),
  KEY `fk_conta_usuario1` (`id_usuario`),
  CONSTRAINT `fk_conta_banco1` FOREIGN KEY (`id_banco`) REFERENCES `banco` (`id_banco`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_conta_tipo_conta1` FOREIGN KEY (`id_tipo_conta`) REFERENCES `tipo_conta` (`id_tipo_conta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_conta_usuario1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.conta: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `conta` DISABLE KEYS */;
INSERT INTO `conta` (`id_conta`, `descricao_conta`, `id_tipo_conta`, `saldo_inicial`, `id_banco`, `id_usuario`, `ativo_conta`, `data_inclusao`) VALUES
	(7, 'Correntista BB', 1, 4.87, 1, 1, 1, '2013-11-04 15:02:21'),
	(8, 'Poupança Bradesco', 2, 1800.1, 1, 1, 1, '2013-11-04 15:02:50'),
	(9, 'Correntista Bradesco', 1, 1010.31, 1, 1, 1, '2013-11-04 15:03:21'),
	(10, 'Carteira', 4, 117.4, NULL, 1, 1, '2013-11-04 15:03:38'),
	(14, '', 1, 0, NULL, 1, 0, '2013-11-20 12:39:00'),
	(15, 'Carteira', 4, 155.5, NULL, 3, 1, '2013-12-20 10:42:45'),
	(16, 'Conta Corrente BB', 1, 7845.21, 2, 5, 1, '2013-12-20 15:36:22');
/*!40000 ALTER TABLE `conta` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.cron
CREATE TABLE IF NOT EXISTS `cron` (
  `id_cron` int(10) NOT NULL AUTO_INCREMENT,
  `descricao_cron` varchar(150) DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  `registros_afetados` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_cron`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.cron: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `cron` DISABLE KEYS */;
INSERT INTO `cron` (`id_cron`, `descricao_cron`, `data_inicio`, `data_fim`, `registros_afetados`) VALUES
	(1, 'cron_emails_lancamentos_a_vencer', '2013-12-16 19:12:25', '2013-12-16 19:12:26', 4),
	(2, 'cron_emails_lancamentos_a_vencer', '2013-12-18 12:12:20', '2013-12-18 12:12:20', 0),
	(3, 'cron_emails_lancamentos_a_vencer', '2013-12-18 12:12:49', '2013-12-18 12:12:50', 0);
/*!40000 ALTER TABLE `cron` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.cupom_desconto
CREATE TABLE IF NOT EXISTS `cupom_desconto` (
  `id_cupom_desconto` int(10) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) DEFAULT NULL,
  `cupom` varchar(10) DEFAULT NULL,
  `porcentagem` int(10) DEFAULT NULL,
  `utilizado` tinyint(4) DEFAULT NULL,
  `data_utilizado` datetime DEFAULT NULL,
  `validade` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cupom_desconto`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fk_cupom_desconto_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.cupom_desconto: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `cupom_desconto` DISABLE KEYS */;
INSERT INTO `cupom_desconto` (`id_cupom_desconto`, `id_usuario`, `cupom`, `porcentagem`, `utilizado`, `data_utilizado`, `validade`) VALUES
	(2, 1, 'DESC-01', 30, 0, NULL, '2013-12-31 00:00:00'),
	(4, 3, 'DESC75', 75, 0, NULL, '2013-12-20 00:00:00');
/*!40000 ALTER TABLE `cupom_desconto` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.estado
CREATE TABLE IF NOT EXISTS `estado` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_estado` varchar(100) DEFAULT NULL,
  `sigla_estado` char(2) DEFAULT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.estado: ~27 rows (aproximadamente)
/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
INSERT INTO `estado` (`id_estado`, `descricao_estado`, `sigla_estado`) VALUES
	(1, 'Acre', 'AC'),
	(2, 'Alagoas', 'AL'),
	(3, 'Amapá ', 'AP'),
	(4, 'Amazonas ', 'AM'),
	(5, 'Bahia', 'BA'),
	(6, 'Ceará', 'CE'),
	(7, 'Distrito Federal', 'DF'),
	(8, 'Espírito Santo', 'ES'),
	(9, 'Goiás', 'GO'),
	(10, 'Maranhão', 'MA'),
	(11, 'Mato Grosso', 'MT'),
	(12, 'Mato Grosso do Sul', 'MS'),
	(13, 'Minas Gerais', 'MG'),
	(14, 'Pará', 'PA'),
	(15, 'Paraíba', 'PB'),
	(16, 'Paraná', 'PR'),
	(17, 'Pernambuco', 'PE'),
	(18, 'Piauí', 'PI'),
	(19, 'Rio de Janeiro', 'RJ'),
	(20, 'Rio Grande do Norte', 'RN'),
	(21, 'Rio Grande do Sul', 'RS'),
	(22, 'Rondônia', 'RO'),
	(23, 'Roraima', 'RR'),
	(24, 'Santa Catarina', 'SC'),
	(25, 'São Paulo', 'SP'),
	(26, 'Sergipe', 'SE'),
	(27, 'Tocantins', 'TO');
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.funcionalidade
CREATE TABLE IF NOT EXISTS `funcionalidade` (
  `id_funcionalidade` int(10) NOT NULL AUTO_INCREMENT,
  `module` varchar(100) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `subtitulo` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `auth` tinyint(1) DEFAULT NULL,
  `id_menu_posicao` int(11) NOT NULL,
  `texto_menu` varchar(200) DEFAULT NULL,
  `ativo_menu` tinyint(1) NOT NULL DEFAULT '0',
  `descricao_permissao` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_funcionalidade`,`id_menu_posicao`),
  KEY `id_menu_posicao` (`id_menu_posicao`),
  CONSTRAINT `fk_menu_posicao_1` FOREIGN KEY (`id_menu_posicao`) REFERENCES `menu_posicao` (`id_menu_posicao`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.funcionalidade: ~55 rows (aproximadamente)
/*!40000 ALTER TABLE `funcionalidade` DISABLE KEYS */;
INSERT INTO `funcionalidade` (`id_funcionalidade`, `module`, `controller`, `action`, `titulo`, `subtitulo`, `ativo`, `auth`, `id_menu_posicao`, `texto_menu`, `ativo_menu`, `descricao_permissao`) VALUES
	(1, 'cliente', 'index', 'index', 'Início', NULL, 1, 1, 1, 'Início', 1, 'Página inicial do cliente'),
	(2, 'cliente', 'movimentacoes', 'index', 'Movimentações', NULL, 1, 1, 1, 'Lançamentos', 1, 'Página inicial Movimentações'),
	(3, 'cliente', 'movimentacoes', 'nova-receita', 'Nova Receita', NULL, 1, 1, 1, NULL, 0, 'Lançar Receitas'),
	(4, 'cliente', 'movimentacoes', 'nova-despesa', 'Nova Despesa', NULL, 1, 1, 1, NULL, 0, 'Lançar Despesas'),
	(5, 'cliente', 'movimentacoes', 'transferencia', 'Tranferência', NULL, 1, 1, 1, NULL, 0, 'Transferências'),
	(6, 'cliente', 'movimentacoes', 'status', 'Status Movimentação (Ajax)', NULL, 1, 1, 1, NULL, 0, 'Alterar status Movimentação'),
	(7, 'cliente', 'movimentacoes', 'editar-movimentacao', 'Editar Movimentação', NULL, 1, 1, 1, NULL, 0, 'Editar Movimentação'),
	(8, 'cliente', 'movimentacoes', 'excluir-movimentacao', 'Excluir Movimentação', NULL, 1, 1, 1, NULL, 0, 'Excluir Movimentação'),
	(9, 'cliente', 'configuracoes', 'index', 'Configurações', NULL, 1, 1, 1, 'Configurações', 1, 'Página inicial de Configurações'),
	(10, 'cliente', 'configuracoes', 'novo-cartao', 'Novo Cartão', NULL, 1, 1, 1, NULL, 0, 'Cadastrar Novo Cartão'),
	(11, 'cliente', 'configuracoes', 'nova-conta', 'Nova Conta', NULL, 1, 1, 1, NULL, 0, 'Cadastrar Nova Conta'),
	(12, 'cliente', 'configuracoes', 'editar-conta', 'Editar Conta', '', 1, 1, 1, '', 0, 'Editar Conta'),
	(13, 'cliente', 'error', 'error', 'Erro', '', 1, 1, 1, '', 0, 'Página de Erro Cliente'),
	(14, 'cliente', 'metas', 'index', 'Metas', '', 1, 1, 1, 'Orçamento', 1, 'Página Incial de Orçamentos'),
	(15, 'cliente', 'metas', 'editar-meta', 'Editar Meta', '', 1, 1, 1, '', 0, 'Editar um Orçamento'),
	(16, 'cliente', 'metas', 'excluir-meta', 'Excluir Meta', '', 1, 1, 1, '', 0, 'Excluir um Orçamento'),
	(17, 'cliente', 'usuarios', 'novo-usuario', 'Novo Usuário', '', 1, 0, 1, '', 0, 'Cadastrar Novo Usuário (Cliente)'),
	(18, 'cliente', 'usuarios', 'logout', 'Sair', '', 1, 1, 2, 'Sair', 1, 'Fazer Logout do Sistema'),
	(19, 'cliente', 'usuarios', 'login', 'NewFinances', '', 1, 0, 1, '', 0, 'Página de Login'),
	(20, 'cliente', 'planos', 'plano-usuario', 'Escolher Plano', '', 1, 1, 1, '', 0, 'Página de Escolha de Plano'),
	(21, 'cliente', 'cidades', 'busca-cidades', 'Busca Cidades (Ajax)', '', 1, 0, 1, '', 0, 'Busca de Cidades (Ajax)'),
	(22, 'cliente', 'cartoes', 'ver-fatura', 'Ver Fatura', '', 1, 1, 1, '', 0, 'Ver Faturas Cartão de Crédito'),
	(23, 'cliente', 'cartoes', 'pagar-fatura', 'Pagar Fatura', '', 1, 1, 1, '', 0, 'Pagar Fatura do Cartão de Crédito'),
	(24, 'cliente', 'relatorios', 'index', 'Relatórios', '', 1, 1, 1, 'Relatórios', 1, 'Página Inicial de Relatórios'),
	(25, 'gestor', 'categorias', 'index', 'Categorias', '', 1, 1, 1, '', 0, 'Página Inicial de Categorias'),
	(26, 'gestor', 'error', 'error', 'Erro', '', 1, 1, 1, '', 0, 'Página de Erro Gestor'),
	(27, 'gestor', 'index', 'index', 'Home Gestor', '', 1, 1, 1, 'Painel', 1, 'Página Inicial do Gestor'),
	(28, 'gestor', 'usuarios', 'index', 'Usuários', '', 1, 1, 1, '', 0, 'Página Inicial de Usuários (Gestor)'),
	(29, 'gestor', 'usuarios', 'buscar-usuario', 'Buscar Usuário', '', 1, 1, 1, '', 0, 'Buscar um Usuário'),
	(30, 'gestor', 'usuarios', 'novo-usuario', 'Novo Usuário', '', 1, 1, 1, '', 0, 'Cadastrar Novo Usuário (Gestor)'),
	(31, 'cliente', 'politica', 'index', 'Nossa Política', '', 1, 0, 1, '', 0, 'Política de Privacidade'),
	(32, 'cliente', 'planos', 'plano-usuario', 'Alterar Plano', '', 1, 1, 2, 'Alterar Plano', 1, 'Alterar Plano Usuário'),
	(33, 'cliente', 'usuarios', 'meus-dados', 'Meus Dados', '', 1, 1, 2, 'Meus Dados', 1, 'Alterar Dados do Usuário (Cliente)'),
	(34, 'cliente', 'planos', 'success', 'Plano Atualizado', '', 1, 1, 1, '', 0, 'Página de Plano Atualizado'),
	(35, 'cliente', 'configuracoes', 'excluir-conta', 'Excluir Conta', '', 1, 1, 1, '', 0, 'Excluir Conta'),
	(36, 'cliente', 'configuracoes', 'editar-cartao', 'Editar Cartão', '', 1, 1, 1, '', 0, 'Editar Cartão de Crédito'),
	(37, 'cliente', 'configuracoes', 'excluir-cartao', 'Excluir Cartão', '', 1, 1, 1, '', 0, 'Excluir Cartão de Crédito'),
	(38, 'cliente', 'cartoes', 'index', 'Cartões', '', 0, 1, 1, 'Cartões', 0, 'Página Inicial de Cartões'),
	(39, 'gestor', 'funcionalidades', 'index', 'Funcionalidades', '', 1, 1, 3, 'Funcionalidades', 0, 'Página Inicial de Funcionalidades'),
	(40, 'gestor', 'funcionalidades', 'nova-funcionalidade', 'Nova Funcionalidade', '', 1, 1, 3, 'Nova Funcionalidade', 0, 'Criar Nova Funcionalidade'),
	(41, 'gestor', 'teste', 'teste', 'Testando', '', 0, 1, 1, 'Testes', 0, 'Página de Teste'),
	(42, 'gestor', 'funcionalidades', 'editar-funcionalidade', 'Editar Funcionalidade', '', 1, 1, 3, 'Editar', 0, 'Editar Funcionalidade'),
	(43, 'gestor', 'bancos', 'index', 'Bancos', '', 1, 1, 3, 'Bancos', 0, 'Página Inicial dos Bancos'),
	(44, 'cliente', 'charts', 'gastos-anuais', 'Gastos Anuais', '', 1, 1, 4, 'Gastos Anuais', 0, 'Gastos Anuais (Gráfico)'),
	(45, 'gestor', 'planos', 'index', 'Planos', '', 1, 1, 4, '', 0, 'Página Inicial de Planos (Gestor)'),
	(46, 'gestor', 'planos', 'novo-valor-plano', 'Novo Plano Valor', '', 1, 1, 4, '', 0, 'Cadastrar Novo Plano Valor'),
	(47, 'cliente', 'planos', 'response-plano', 'Resposta Pagamento', '', 1, 0, 4, '', 0, 'Página de Resposta de Pagamento'),
	(48, 'gestor', 'pagseguro', 'responses-pagseguro', 'Respostas PagSeguro', '', 1, 1, 4, '', 0, 'Página de Resposta do PagSeguro'),
	(49, 'gestor', 'pagseguro', 'index', 'Pag Seguro', '', 1, 1, 4, '', 0, 'PagSeguro Gestor'),
	(50, 'cliente', 'configuracoes', 'alterar-senha', 'Alterar Senha', '', 1, 1, 4, '', 0, 'Alterar Senha'),
	(51, 'gestor', 'crons', 'index', 'Tarefas Cron', '', 1, 1, 3, '', 0, 'Tarefas do Cron'),
	(52, 'cliente', 'usuarios', 'recuperar-senha', 'Recuperar Senha', '', 1, 0, 4, '', 0, 'Recuperar Senha'),
	(53, 'site', 'index', 'index', 'New Finances', '', 1, 0, 4, '', 0, 'Página Inicial do Site'),
	(54, 'cliente', 'index', 'deny', 'Não Permitido', '', 1, 0, 4, '', 0, 'Página de Acesso Não Permitido'),
	(55, 'gestor', 'access', 'index', 'Controle de Acesso', '', 1, 1, 3, '', 0, 'Página Incial Controle de Acesso');
/*!40000 ALTER TABLE `funcionalidade` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.menu_posicao
CREATE TABLE IF NOT EXISTS `menu_posicao` (
  `id_menu_posicao` int(10) NOT NULL AUTO_INCREMENT,
  `posicao` varchar(200) NOT NULL DEFAULT '0',
  `descricao` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_menu_posicao`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.menu_posicao: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `menu_posicao` DISABLE KEYS */;
INSERT INTO `menu_posicao` (`id_menu_posicao`, `posicao`, `descricao`) VALUES
	(1, 'Menu Principal', 'Menu principal para a navegação no sistema'),
	(2, 'Menu Usuário', 'Menu do cabeçalho '),
	(3, 'Gestor Index', 'Index do painel de controle do gestor'),
	(4, 'Nenhuma', 'Nenhuma posicao');
/*!40000 ALTER TABLE `menu_posicao` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.meta
CREATE TABLE IF NOT EXISTS `meta` (
  `id_meta` int(11) NOT NULL AUTO_INCREMENT,
  `valor_meta` float DEFAULT NULL,
  `mes_meta` int(11) DEFAULT NULL,
  `ano_meta` int(11) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_usuario` int(10) NOT NULL,
  `repetir` tinyint(1) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_meta`,`id_categoria`,`id_usuario`),
  KEY `fk_meta_categoria1` (`id_categoria`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fk_meta_categoria1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.meta: ~95 rows (aproximadamente)
/*!40000 ALTER TABLE `meta` DISABLE KEYS */;
INSERT INTO `meta` (`id_meta`, `valor_meta`, `mes_meta`, `ano_meta`, `id_categoria`, `id_usuario`, `repetir`, `data_cadastro`) VALUES
	(1, 276, 11, 2013, 5, 1, 1, '2013-11-13 16:45:53'),
	(2, 350, 12, 2013, 5, 1, 0, '2013-11-13 16:45:53'),
	(3, 276, 1, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(4, 276, 2, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(5, 276, 3, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(6, 276, 4, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(7, 276, 5, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(8, 276, 6, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(9, 276, 7, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(10, 276, 8, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(11, 276, 9, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(12, 276, 10, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(13, 276, 11, 2014, 5, 1, 0, '2013-11-13 16:45:53'),
	(14, 100, 11, 2013, 6, 1, 1, '2013-11-13 16:46:04'),
	(15, 100, 12, 2013, 6, 1, 0, '2013-11-13 16:46:04'),
	(16, 100, 1, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(17, 100, 2, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(18, 100, 3, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(19, 100, 4, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(20, 100, 5, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(21, 100, 6, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(22, 100, 7, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(23, 100, 8, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(24, 100, 9, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(25, 100, 10, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(26, 100, 11, 2014, 6, 1, 0, '2013-11-13 16:46:04'),
	(27, 273.7, 11, 2013, 3, 1, 1, '2013-11-13 16:46:19'),
	(28, 273.7, 12, 2013, 3, 1, 0, '2013-11-13 16:46:19'),
	(29, 273.7, 1, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(30, 273.7, 2, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(31, 273.7, 3, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(32, 273.7, 4, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(33, 273.7, 5, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(34, 273.7, 6, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(35, 273.7, 7, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(36, 273.7, 8, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(37, 273.7, 9, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(38, 273.7, 10, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(39, 273.7, 11, 2014, 3, 1, 0, '2013-11-13 16:46:19'),
	(40, 1000, 11, 2013, 8, 1, 1, '2013-11-13 16:47:49'),
	(41, 700, 12, 2013, 8, 1, 0, '2013-11-13 16:47:49'),
	(42, 1000, 1, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(43, 1000, 2, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(44, 1000, 3, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(45, 1000, 4, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(46, 1000, 5, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(47, 1000, 6, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(48, 1000, 7, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(49, 1000, 8, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(50, 1000, 9, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(51, 1000, 10, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(52, 1000, 11, 2014, 8, 1, 0, '2013-11-13 16:47:49'),
	(66, 600, 11, 2013, 9, 1, 0, '2013-11-13 17:16:39'),
	(67, 400, 11, 2013, 1, 1, 1, '2013-11-13 17:16:52'),
	(68, 370, 12, 2013, 1, 1, 0, '2013-11-13 17:16:52'),
	(69, 400, 1, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(70, 400, 2, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(71, 400, 3, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(72, 400, 4, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(73, 400, 5, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(74, 400, 6, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(75, 400, 7, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(76, 400, 8, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(77, 400, 9, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(78, 400, 10, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(79, 400, 11, 2014, 1, 1, 0, '2013-11-13 17:16:52'),
	(80, 100, 11, 2013, 5, 9, 1, '2013-11-13 17:30:17'),
	(81, 100, 12, 2013, 5, 9, 0, '2013-11-13 17:30:17'),
	(82, 100, 1, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(83, 100, 2, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(84, 100, 3, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(85, 100, 4, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(86, 100, 5, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(87, 100, 6, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(88, 100, 7, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(89, 100, 8, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(90, 100, 9, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(91, 100, 10, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(92, 100, 11, 2014, 5, 9, 0, '2013-11-13 17:30:17'),
	(93, 250, 11, 2013, 8, 9, 1, '2013-11-13 17:33:12'),
	(94, 250, 12, 2013, 8, 9, 0, '2013-11-13 17:33:12'),
	(95, 250, 1, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(96, 250, 2, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(97, 250, 3, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(98, 250, 4, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(99, 250, 5, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(100, 250, 6, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(101, 250, 7, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(102, 250, 8, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(103, 250, 9, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(104, 250, 10, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(105, 250, 11, 2014, 8, 9, 0, '2013-11-13 17:33:12'),
	(106, 50, 11, 2013, 5, 17, 0, '2013-11-22 11:40:37'),
	(107, 100, 11, 2013, 6, 17, 0, '2013-11-22 11:41:44'),
	(108, 150, 12, 2013, 7, 1, 0, '2013-12-02 09:46:22');
/*!40000 ALTER TABLE `meta` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.movimentacao
CREATE TABLE IF NOT EXISTS `movimentacao` (
  `id_movimentacao` int(11) NOT NULL AUTO_INCREMENT,
  `data_movimentacao` date DEFAULT NULL,
  `descricao_movimentacao` varchar(200) DEFAULT NULL,
  `data_inclusao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_tipo_movimentacao` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_conta` int(11) DEFAULT NULL,
  `id_cartao` int(11) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `valor_movimentacao` float DEFAULT NULL,
  `realizado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_movimentacao`,`id_tipo_movimentacao`,`id_usuario`),
  KEY `fk_movimentacao_tipo_movimentacao1` (`id_tipo_movimentacao`),
  KEY `fk_movimentacao_usuario1` (`id_usuario`),
  KEY `fk_movimentacao_conta1` (`id_conta`),
  KEY `fk_movimentacao_cartao1` (`id_cartao`),
  KEY `fk_movimentacao_categoria1` (`id_categoria`),
  CONSTRAINT `fk_movimentacao_cartao1` FOREIGN KEY (`id_cartao`) REFERENCES `cartao` (`id_cartao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimentacao_categoria1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimentacao_conta1` FOREIGN KEY (`id_conta`) REFERENCES `conta` (`id_conta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimentacao_tipo_movimentacao1` FOREIGN KEY (`id_tipo_movimentacao`) REFERENCES `tipo_movimentacao` (`id_tipo_movimentacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimentacao_usuario1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=994 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.movimentacao: ~11 rows (aproximadamente)
/*!40000 ALTER TABLE `movimentacao` DISABLE KEYS */;
INSERT INTO `movimentacao` (`id_movimentacao`, `data_movimentacao`, `descricao_movimentacao`, `data_inclusao`, `id_tipo_movimentacao`, `id_usuario`, `id_conta`, `id_cartao`, `id_categoria`, `valor_movimentacao`, `realizado`) VALUES
	(983, '2013-12-11', 'Pagto Armx', '2013-12-10 17:39:29', 1, 1, 9, NULL, 9, 500, 0),
	(984, '2013-12-10', 'Presente Thalita', '2013-12-10 17:56:09', 2, 1, 10, NULL, 17, -200, 1),
	(985, '2013-12-10', 'Lanche Shopping', '2013-12-10 18:01:29', 2, 1, 9, NULL, 5, -15.05, 1),
	(986, '2013-12-12', 'Teste', '2013-12-12 09:46:50', 2, 1, 10, NULL, 9, -15.15, 1),
	(987, '2013-12-12', 'teste 2', '2013-12-12 09:47:36', 2, 1, 10, NULL, 5, -15.15, 1),
	(988, '2013-12-12', 'teste 3', '2013-12-12 09:48:03', 2, 1, 10, NULL, 4, -15.15, 1),
	(989, '2013-12-15', 'Passagem Casa', '2013-12-16 15:53:19', 2, 1, 10, NULL, 3, -3.3, 0),
	(990, '2013-12-18', 'Almoço Realter', '2013-12-16 16:59:54', 2, 1, 10, NULL, 5, -12, 0),
	(991, '2013-12-18', 'Passagem Realter', '2013-12-16 17:00:44', 2, 1, 10, NULL, 3, -3.3, 0),
	(992, '2013-12-18', 'Hidroginástica Thalita', '2013-12-16 17:01:19', 2, 1, 9, NULL, 8, -65, 0),
	(993, '2013-12-18', 'Pagto Armx', '2013-12-16 17:01:50', 1, 1, 9, NULL, 9, 500, 0);
/*!40000 ALTER TABLE `movimentacao` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.movimentacao_repeticao
CREATE TABLE IF NOT EXISTS `movimentacao_repeticao` (
  `id_movimentacao_repeticao` int(11) NOT NULL AUTO_INCREMENT,
  `id_movimentacao` int(11) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `modo` varchar(45) DEFAULT NULL,
  `processado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_movimentacao_repeticao`,`id_movimentacao`),
  KEY `fk_movimentacao_repeticao_movimentacao1_idx` (`id_movimentacao`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.movimentacao_repeticao: ~24 rows (aproximadamente)
/*!40000 ALTER TABLE `movimentacao_repeticao` DISABLE KEYS */;
INSERT INTO `movimentacao_repeticao` (`id_movimentacao_repeticao`, `id_movimentacao`, `tipo`, `modo`, `processado`) VALUES
	(1, 2, 'fixo', 'day', 1),
	(2, 368, 'fixo', 'day', 1),
	(3, 747, 'fixo', 'month', 1),
	(4, 760, 'fixo', 'month', 1),
	(5, 777, 'fixo', 'month', 1),
	(6, 791, 'fixo', 'month', 1),
	(7, 804, 'fixo', 'month', 1),
	(8, 817, 'fixo', 'month', 1),
	(9, 830, 'fixo', 'month', 1),
	(10, 843, 'parcelado', '3', 1),
	(11, 846, 'parcelado', '3', 1),
	(12, 854, 'parcelado', '4', 1),
	(13, 858, 'parcelado', '12', 1),
	(14, 870, 'fixo', 'month', 1),
	(15, 883, 'parcelado', '2', 1),
	(16, 892, 'parcelado', '5', 1),
	(17, 899, 'parcelado', '12', 1),
	(18, 913, 'parcelado', '10', 1),
	(19, 924, 'parcelado', '2', 1),
	(20, 935, 'parcelado', '10', 1),
	(21, 945, 'parcelado', '2', 1),
	(22, 949, 'parcelado', '3', 1),
	(23, 952, 'parcelado', '3', 1),
	(24, 961, 'fixo', 'month', 1);
/*!40000 ALTER TABLE `movimentacao_repeticao` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.pagamento
CREATE TABLE IF NOT EXISTS `pagamento` (
  `id_pagamento` int(10) NOT NULL AUTO_INCREMENT,
  `cod_transacao` varchar(36) NOT NULL,
  `id_usuario` int(10) DEFAULT NULL,
  `id_plano` int(10) DEFAULT NULL,
  `id_plano_valor` int(10) DEFAULT NULL,
  `data_solicitado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT NULL,
  `processado` tinyint(1) DEFAULT NULL,
  `data_processado` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pagamento`),
  KEY `id_usuario_id_plano` (`id_usuario`,`id_plano`),
  KEY `id_plano_valor` (`id_plano_valor`),
  KEY `fk_pagamento_plano` (`id_plano`),
  CONSTRAINT `fk_pagamento_plano` FOREIGN KEY (`id_plano`) REFERENCES `plano` (`id_plano`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pagamento_plano_valor` FOREIGN KEY (`id_plano_valor`) REFERENCES `plano_valor` (`id_plano_valor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pagamento_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.pagamento: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `pagamento` DISABLE KEYS */;
INSERT INTO `pagamento` (`id_pagamento`, `cod_transacao`, `id_usuario`, `id_plano`, `id_plano_valor`, `data_solicitado`, `status`, `processado`, `data_processado`) VALUES
	(2, '0', 1, 5, 13, '2013-12-13 13:35:45', 0, 0, NULL),
	(3, '', 1, 3, 18, '2013-12-18 15:41:01', 0, 0, NULL),
	(4, '', 1, 4, 15, '2013-12-18 15:41:20', 0, 0, NULL),
	(5, '', 1, 5, 16, '2013-12-18 15:41:41', 0, 0, NULL);
/*!40000 ALTER TABLE `pagamento` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.pagamento_cartao
CREATE TABLE IF NOT EXISTS `pagamento_cartao` (
  `id_pagamento_cartao` int(10) NOT NULL AUTO_INCREMENT,
  `id_cartao` int(10) NOT NULL,
  `vencimento_fatura` date DEFAULT NULL,
  `data_pagamento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valor_pago` float DEFAULT NULL,
  `id_usuario` int(10) NOT NULL,
  PRIMARY KEY (`id_pagamento_cartao`,`id_cartao`,`id_usuario`),
  KEY `id_cartao` (`id_cartao`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fk_cartao_1` FOREIGN KEY (`id_cartao`) REFERENCES `cartao` (`id_cartao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.pagamento_cartao: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `pagamento_cartao` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagamento_cartao` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.plano
CREATE TABLE IF NOT EXISTS `plano` (
  `id_plano` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_plano` varchar(200) DEFAULT NULL,
  `tempo_plano` int(11) DEFAULT '0',
  `unidade_tempo_plano` varchar(50) NOT NULL DEFAULT '0',
  `ativo_plano` tinyint(1) DEFAULT NULL,
  `usuario_plano` smallint(6) DEFAULT NULL,
  `cobranca` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_plano`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.plano: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `plano` DISABLE KEYS */;
INSERT INTO `plano` (`id_plano`, `descricao_plano`, `tempo_plano`, `unidade_tempo_plano`, `ativo_plano`, `usuario_plano`, `cobranca`) VALUES
	(1, 'Plano Gratuito', NULL, ' ', 0, 0, 0),
	(2, 'Plano 30 dias', 30, 'dias', 1, 1, 0),
	(3, 'Plano Trimestral', 3, 'meses', 1, 1, 1),
	(4, 'Plano Semestral', 6, 'meses', 1, 1, 1),
	(5, 'Plano Anual', 1, 'ano', 1, 1, 1),
	(6, 'Plano Vitalício', NULL, '', 0, 0, 0),
	(7, 'Plano Gestor', NULL, '', 1, 0, 0),
	(8, 'Plano Básico', NULL, ' ', 1, 0, 0);
/*!40000 ALTER TABLE `plano` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.plano_funcionalidade
CREATE TABLE IF NOT EXISTS `plano_funcionalidade` (
  `id_plano_funcionalidade` int(10) NOT NULL AUTO_INCREMENT,
  `id_plano` int(10) NOT NULL,
  `id_funcionalidade` int(10) NOT NULL,
  PRIMARY KEY (`id_plano_funcionalidade`,`id_plano`,`id_funcionalidade`),
  KEY `id_plano` (`id_plano`),
  KEY `id_funcionalidade` (`id_funcionalidade`),
  CONSTRAINT `fk_funcionalidade_1` FOREIGN KEY (`id_funcionalidade`) REFERENCES `funcionalidade` (`id_funcionalidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_plano_1` FOREIGN KEY (`id_plano`) REFERENCES `plano` (`id_plano`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.plano_funcionalidade: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `plano_funcionalidade` DISABLE KEYS */;
INSERT INTO `plano_funcionalidade` (`id_plano_funcionalidade`, `id_plano`, `id_funcionalidade`) VALUES
	(27, 2, 23),
	(28, 2, 38),
	(29, 2, 22),
	(30, 2, 44),
	(31, 2, 21),
	(32, 2, 50),
	(33, 2, 11),
	(34, 2, 10),
	(35, 2, 36),
	(36, 2, 12),
	(37, 2, 37),
	(38, 2, 35),
	(39, 2, 9),
	(40, 2, 13),
	(41, 2, 54),
	(42, 2, 1),
	(43, 2, 15),
	(44, 2, 16),
	(45, 2, 14),
	(46, 2, 6),
	(47, 2, 7),
	(48, 2, 8),
	(49, 2, 4),
	(50, 2, 3),
	(51, 2, 2),
	(52, 2, 5),
	(53, 2, 32),
	(54, 2, 20),
	(55, 2, 34),
	(56, 2, 47),
	(57, 2, 31),
	(58, 2, 24),
	(59, 2, 33),
	(60, 2, 17),
	(61, 2, 18),
	(62, 2, 19),
	(63, 2, 52),
	(64, 3, 23),
	(65, 3, 38),
	(66, 3, 22),
	(67, 3, 44),
	(68, 3, 21),
	(69, 3, 50),
	(70, 3, 11),
	(71, 3, 10),
	(72, 3, 36),
	(73, 3, 12),
	(74, 3, 37),
	(75, 3, 35),
	(76, 3, 9),
	(77, 3, 13),
	(78, 3, 54),
	(79, 3, 1),
	(80, 3, 15),
	(81, 3, 16),
	(82, 3, 14),
	(83, 3, 6),
	(84, 3, 7),
	(85, 3, 8),
	(86, 3, 4),
	(87, 3, 3),
	(88, 3, 2),
	(89, 3, 5),
	(90, 3, 32),
	(91, 3, 20),
	(92, 3, 34),
	(93, 3, 47),
	(94, 3, 31),
	(95, 3, 24),
	(96, 3, 33),
	(97, 3, 17),
	(98, 3, 18),
	(99, 3, 19),
	(100, 3, 52),
	(127, 4, 23),
	(128, 4, 38),
	(129, 4, 22),
	(130, 4, 44),
	(131, 4, 21),
	(132, 4, 50),
	(133, 4, 11),
	(134, 4, 10),
	(135, 4, 36),
	(136, 4, 12),
	(137, 4, 37),
	(138, 4, 35),
	(139, 4, 9),
	(140, 4, 13),
	(141, 4, 54),
	(142, 4, 1),
	(143, 4, 15),
	(144, 4, 16),
	(145, 4, 14),
	(146, 4, 6),
	(147, 4, 7),
	(148, 4, 8),
	(149, 4, 4),
	(150, 4, 3),
	(151, 4, 2),
	(152, 4, 5),
	(153, 4, 32),
	(154, 4, 20),
	(155, 4, 34),
	(156, 4, 47),
	(157, 4, 31),
	(158, 4, 24),
	(159, 4, 33),
	(160, 4, 17),
	(161, 4, 18),
	(162, 4, 19),
	(163, 4, 52),
	(190, 5, 23),
	(191, 5, 38),
	(192, 5, 22),
	(193, 5, 44),
	(194, 5, 21),
	(195, 5, 50),
	(196, 5, 11),
	(197, 5, 10),
	(198, 5, 36),
	(199, 5, 12),
	(200, 5, 37),
	(201, 5, 35),
	(202, 5, 9),
	(203, 5, 13),
	(204, 5, 54),
	(205, 5, 1),
	(206, 5, 15),
	(207, 5, 16),
	(208, 5, 14),
	(209, 5, 6),
	(210, 5, 7),
	(211, 5, 8),
	(212, 5, 4),
	(213, 5, 3),
	(214, 5, 2),
	(215, 5, 5),
	(216, 5, 32),
	(217, 5, 20),
	(218, 5, 34),
	(219, 5, 47),
	(220, 5, 31),
	(221, 5, 24),
	(222, 5, 33),
	(223, 5, 17),
	(224, 5, 18),
	(225, 5, 19),
	(226, 5, 52),
	(1, 8, 21),
	(2, 8, 50),
	(3, 8, 11),
	(4, 8, 12),
	(5, 8, 35),
	(6, 8, 9),
	(7, 8, 13),
	(8, 8, 54),
	(9, 8, 1),
	(10, 8, 53),
	(11, 8, 6),
	(12, 8, 7),
	(13, 8, 8),
	(14, 8, 4),
	(15, 8, 3),
	(16, 8, 2),
	(17, 8, 32),
	(18, 8, 20),
	(19, 8, 34),
	(20, 8, 47),
	(21, 8, 31),
	(22, 8, 33),
	(23, 8, 17),
	(24, 8, 18),
	(25, 8, 19),
	(26, 8, 52);
/*!40000 ALTER TABLE `plano_funcionalidade` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.plano_valor
CREATE TABLE IF NOT EXISTS `plano_valor` (
  `id_plano_valor` int(10) NOT NULL AUTO_INCREMENT,
  `id_plano` int(10) NOT NULL DEFAULT '0',
  `valor_plano` float DEFAULT '0',
  `usuario` tinyint(1) DEFAULT NULL COMMENT 'Flag para mostar o valor que irá aparecer no cadastro de novos usuários',
  `banner` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_plano_valor`,`id_plano`),
  KEY `id_plano` (`id_plano`),
  CONSTRAINT `fk_plano_valor_plano` FOREIGN KEY (`id_plano`) REFERENCES `plano` (`id_plano`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.plano_valor: ~15 rows (aproximadamente)
/*!40000 ALTER TABLE `plano_valor` DISABLE KEYS */;
INSERT INTO `plano_valor` (`id_plano_valor`, `id_plano`, `valor_plano`, `usuario`, `banner`) VALUES
	(1, 1, 0, 0, NULL),
	(2, 2, 0, 1, NULL),
	(3, 3, 15.9, 0, NULL),
	(4, 4, 29.9, 0, NULL),
	(5, 5, 39.9, 0, NULL),
	(6, 6, 0, 0, NULL),
	(7, 7, 0, 0, NULL),
	(8, 4, 19.9, 0, NULL),
	(9, 8, 0, 0, NULL),
	(13, 5, 5.9, 0, NULL),
	(14, 3, 27, 0, 'banner_plano_trimestral.png'),
	(15, 4, 45, 1, 'banner_plano_semestral.png'),
	(16, 5, 78, 1, 'banner_plano_anual.png'),
	(17, 3, 19.9, 0, 'banner_plano_trimestral.png'),
	(18, 3, 19.9, 1, 'banner_plano_trimestral.png');
/*!40000 ALTER TABLE `plano_valor` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.tipo_conta
CREATE TABLE IF NOT EXISTS `tipo_conta` (
  `id_tipo_conta` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_tipo_conta` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_conta`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.tipo_conta: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `tipo_conta` DISABLE KEYS */;
INSERT INTO `tipo_conta` (`id_tipo_conta`, `descricao_tipo_conta`) VALUES
	(1, 'Conta Corrente'),
	(2, 'Conta Poupança'),
	(3, 'Cartão de Crédito'),
	(4, 'Carteira'),
	(5, 'Outros');
/*!40000 ALTER TABLE `tipo_conta` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.tipo_movimentacao
CREATE TABLE IF NOT EXISTS `tipo_movimentacao` (
  `id_tipo_movimentacao` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_movimentacao` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_movimentacao`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.tipo_movimentacao: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `tipo_movimentacao` DISABLE KEYS */;
INSERT INTO `tipo_movimentacao` (`id_tipo_movimentacao`, `tipo_movimentacao`) VALUES
	(1, 'Receita'),
	(2, 'Despesa'),
	(3, 'Cartão de Crédito'),
	(4, 'Transferência'),
	(5, 'Saldo Inicial'),
	(6, 'Cartão de Crédito - Fatura'),
	(7, 'Pgto Fatura '),
	(8, 'Pgto Fatura Parcial');
/*!40000 ALTER TABLE `tipo_movimentacao` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome_completo` varchar(200) DEFAULT NULL,
  `cidade` varchar(500) DEFAULT NULL,
  `cpf_usuario` varchar(14) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `id_cidade` int(11) NOT NULL DEFAULT '1',
  `id_estado` int(10) NOT NULL DEFAULT '1',
  `email_usuario` varchar(200) NOT NULL,
  `senha_usuario` varchar(32) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `data_alteracao` datetime DEFAULT NULL,
  `ativo_usuario` tinyint(1) DEFAULT NULL,
  `politica` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`,`id_cidade`,`id_estado`,`cpf_usuario`,`email_usuario`),
  UNIQUE KEY `cpf_usuario` (`cpf_usuario`),
  UNIQUE KEY `email_usuario` (`email_usuario`),
  KEY `id_cidade` (`id_cidade`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `fk_usuario_cidade1` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_estado1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.usuario: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` (`id_usuario`, `nome_completo`, `cidade`, `cpf_usuario`, `data_nascimento`, `id_cidade`, `id_estado`, `email_usuario`, `senha_usuario`, `data_cadastro`, `data_alteracao`, `ativo_usuario`, `politica`) VALUES
	(1, 'Fernando Rodrigues dos Santos Pires', 'Belo Horizonte', '062.385.206-39', '1982-09-28', 1, 13, 'nandorodpires@gmail.com', 'c42e3273c1a653caac79188efa0349a9', '2013-10-29 17:05:11', '2013-10-29 17:05:11', 1, NULL),
	(3, 'Thalita Regina Araújo de Assis', 'Santa Luzia', '108.716.176-25', '2013-12-19', 1, 13, 'thalitaregina91@gmail.com', 'e7925e7f628f1aea9201cfabb5cfeb8c', '2013-12-19 14:26:00', '2013-12-19 14:26:00', 1, 1),
	(4, 'Rainer Rodrigues', 'Vespaziano', '123.456.789-12', '2013-12-19', 1, 13, 'rainer@gmail.com', '202cb962ac59075b964b07152d234b70', '2013-12-19 16:15:28', '2013-12-19 16:15:28', 1, 1),
	(5, 'Tiago Santiago Botelho', 'Belo Horizonte', '123.456.789-00', '2013-12-20', 1, 13, 'tiago@realter.com.br', '202cb962ac59075b964b07152d234b70', '2013-12-20 15:35:22', '2013-12-20 15:35:22', 1, 1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.usuario_login
CREATE TABLE IF NOT EXISTS `usuario_login` (
  `id_usuario_login` int(10) NOT NULL AUTO_INCREMENT,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario_login`,`id_usuario`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.usuario_login: ~77 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario_login` DISABLE KEYS */;
INSERT INTO `usuario_login` (`id_usuario_login`, `data`, `id_usuario`) VALUES
	(1, '2013-12-06 17:22:41', 1),
	(2, '2013-12-09 11:17:10', 1),
	(3, '2013-12-09 11:28:02', 1),
	(5, '2013-12-09 14:39:55', 1),
	(6, '2013-12-09 15:44:14', 1),
	(7, '2013-12-09 16:19:26', 1),
	(8, '2013-12-09 17:16:14', 1),
	(10, '2013-12-10 09:44:06', 1),
	(11, '2013-12-10 10:43:06', 1),
	(12, '2013-12-10 15:09:53', 1),
	(13, '2013-12-10 15:12:50', 1),
	(14, '2013-12-10 15:57:22', 1),
	(15, '2013-12-10 16:31:32', 1),
	(16, '2013-12-10 16:52:17', 1),
	(17, '2013-12-11 08:59:42', 1),
	(18, '2013-12-11 09:31:35', 1),
	(21, '2013-12-11 12:16:36', 1),
	(22, '2013-12-11 12:36:47', 1),
	(23, '2013-12-11 16:12:23', 1),
	(24, '2013-12-11 16:22:56', 1),
	(25, '2013-12-11 17:15:42', 1),
	(26, '2013-12-11 17:22:33', 1),
	(27, '2013-12-11 17:51:36', 1),
	(28, '2013-12-12 09:34:37', 1),
	(29, '2013-12-12 12:07:35', 1),
	(30, '2013-12-12 12:15:59', 1),
	(31, '2013-12-12 12:34:26', 1),
	(32, '2013-12-12 13:28:10', 1),
	(33, '2013-12-12 13:30:59', 1),
	(34, '2013-12-12 13:32:14', 1),
	(35, '2013-12-12 14:48:33', 1),
	(37, '2013-12-12 17:31:16', 1),
	(39, '2013-12-12 17:53:13', 1),
	(40, '2013-12-13 09:57:57', 1),
	(42, '2013-12-13 13:01:23', 1),
	(43, '2013-12-13 14:16:14', 1),
	(44, '2013-12-13 15:20:29', 1),
	(45, '2013-12-13 16:44:21', 1),
	(49, '2013-12-13 17:45:32', 1),
	(50, '2013-12-16 09:30:36', 1),
	(51, '2013-12-16 10:44:46', 1),
	(52, '2013-12-16 12:53:41', 1),
	(53, '2013-12-17 08:48:12', 1),
	(54, '2013-12-17 08:49:35', 1),
	(55, '2013-12-17 12:27:38', 1),
	(56, '2013-12-17 13:20:21', 1),
	(57, '2013-12-17 13:57:11', 1),
	(58, '2013-12-17 15:01:19', 1),
	(60, '2013-12-18 10:46:41', 1),
	(61, '2013-12-18 12:58:36', 1),
	(62, '2013-12-18 13:18:24', 1),
	(64, '2013-12-18 14:36:52', 1),
	(65, '2013-12-18 15:42:44', 1),
	(66, '2013-12-18 16:22:24', 1),
	(67, '2013-12-18 17:24:50', 1),
	(68, '2013-12-18 17:26:05', 1),
	(69, '2013-12-19 09:50:31', 1),
	(70, '2013-12-19 10:17:14', 1),
	(71, '2013-12-19 12:59:25', 1),
	(72, '2013-12-19 13:35:31', 1),
	(75, '2013-12-19 14:36:20', 1),
	(76, '2013-12-19 15:32:13', 3),
	(77, '2013-12-19 15:34:07', 3),
	(78, '2013-12-19 15:36:51', 3),
	(79, '2013-12-19 15:38:01', 3),
	(80, '2013-12-19 15:39:22', 3),
	(81, '2013-12-19 16:40:31', 1),
	(82, '2013-12-19 17:20:52', 1),
	(83, '2013-12-19 17:48:57', 1),
	(84, '2013-12-20 09:23:29', 1),
	(85, '2013-12-20 10:00:45', 3),
	(86, '2013-12-20 10:47:54', 1),
	(87, '2013-12-20 10:48:24', 3),
	(88, '2013-12-20 11:19:49', 1),
	(89, '2013-12-20 11:26:14', 3),
	(90, '2013-12-20 11:26:39', 1),
	(91, '2013-12-20 11:37:23', 1),
	(92, '2013-12-20 15:41:31', 1);
/*!40000 ALTER TABLE `usuario_login` ENABLE KEYS */;


-- Copiando estrutura para tabela finances.usuario_plano
CREATE TABLE IF NOT EXISTS `usuario_plano` (
  `id_usuario_plano` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_plano` int(11) NOT NULL,
  `data_aderido` datetime DEFAULT NULL,
  `data_encerramento` datetime DEFAULT NULL,
  `ativo_plano` tinyint(1) DEFAULT NULL,
  `id_plano_valor` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_usuario_plano`),
  KEY `fk_usuario_has_plano_usuario` (`id_usuario`),
  KEY `fk_usuario_has_plano_plano1` (`id_plano`),
  KEY `id_plano_valor` (`id_plano_valor`),
  CONSTRAINT `fk_usuarios_has_plano_valor1` FOREIGN KEY (`id_plano_valor`) REFERENCES `plano_valor` (`id_plano_valor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_plano_plano1` FOREIGN KEY (`id_plano`) REFERENCES `plano` (`id_plano`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_plano_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela finances.usuario_plano: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario_plano` DISABLE KEYS */;
INSERT INTO `usuario_plano` (`id_usuario_plano`, `id_usuario`, `id_plano`, `data_aderido`, `data_encerramento`, `ativo_plano`, `id_plano_valor`) VALUES
	(1, 1, 7, '2013-10-29 17:05:19', NULL, 0, 7),
	(9, 3, 8, '2013-12-19 15:36:03', NULL, 0, 9),
	(10, 3, 3, '2013-12-19 15:38:28', '2014-03-19 15:38:30', 1, 8),
	(11, 4, 8, '2013-12-19 16:15:28', NULL, 0, 9),
	(12, 4, 2, '2013-12-19 16:27:17', '2014-01-18 16:27:17', 1, 2),
	(13, 1, 2, '2013-12-19 16:44:20', '2014-01-18 16:44:20', 0, 2),
	(14, 1, 7, '2013-12-19 16:45:04', NULL, 1, 7),
	(15, 5, 8, '2013-12-20 15:35:22', NULL, 0, 9),
	(16, 5, 2, '2013-12-20 15:40:55', '2014-01-19 15:40:55', 1, 2);
/*!40000 ALTER TABLE `usuario_plano` ENABLE KEYS */;


-- Copiando estrutura para view finances.vw_fatura_cartao
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_fatura_cartao` (
	`id_cartao` INT(11) NOT NULL,
	`descricao_cartao` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`fim_fatura` VARCHAR(29) NULL COLLATE 'utf8_general_ci',
	`vencimento_anterior` DATE NULL,
	`vencimento_fatura` DATE NULL,
	`valor_fatura` DOUBLE NULL,
	`id_usuario` INT(11) NOT NULL
) ENGINE=MyISAM;


-- Copiando estrutura para view finances.vw_lancamento_cartao
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_lancamento_cartao` (
	`id_movimentacao` INT(11) NOT NULL,
	`id_tipo_movimentacao` INT(11) NOT NULL,
	`id_cartao` INT(11) NOT NULL,
	`descricao_cartao` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`vencimento_cartao` INT(11) NULL,
	`fechamento_cartao` INT(11) NULL,
	`data_movimentacao` DATE NULL,
	`inicio_fatura` VARCHAR(29) NULL COLLATE 'utf8_general_ci',
	`fim_fatura` VARCHAR(29) NULL COLLATE 'utf8_general_ci',
	`vencimento_fatura` DATE NULL,
	`descricao_movimentacao` VARCHAR(200) NULL COLLATE 'utf8_general_ci',
	`valor_movimentacao` DOUBLE NULL,
	`id_usuario` INT(11) NOT NULL
) ENGINE=MyISAM;


-- Copiando estrutura para view finances.vw_movimentacao
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_movimentacao` (
	`id_movimentacao` BIGINT(20) NULL,
	`id_movimentacao_origem` BIGINT(20) NULL,
	`id_tipo_movimentacao` BIGINT(20) NOT NULL,
	`tipo_movimentacao` VARCHAR(200) NULL COLLATE 'utf8_general_ci',
	`descricao_movimentacao` VARCHAR(216) NULL COLLATE 'utf8_general_ci',
	`id_categoria` INT(11) NULL,
	`descricao_categoria` VARCHAR(200) NULL COLLATE 'utf8_general_ci',
	`valor_movimentacao` DOUBLE NULL,
	`realizado` BIGINT(20) NULL,
	`data_movimentacao` DATE NULL,
	`data_inclusao` DATETIME NULL,
	`id_conta` INT(11) NULL,
	`descricao_conta` VARCHAR(200) NULL COLLATE 'utf8_general_ci',
	`id_conta_destino` INT(11) NULL,
	`descricao_conta_destino` VARCHAR(200) NULL COLLATE 'utf8_general_ci',
	`id_cartao` INT(11) NULL,
	`id_usuario` INT(11) NOT NULL,
	`fatura` BIGINT(20) NOT NULL
) ENGINE=MyISAM;


-- Copiando estrutura para view finances.vw_fatura_cartao
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_fatura_cartao`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `finances`.`vw_fatura_cartao` AS select 	`vlc`.`id_cartao`, 
			`vlc`.`descricao_cartao`, 
			vlc.fim_fatura,
			date_sub(vlc.vencimento_fatura, interval 1 month) as `vencimento_anterior`, 
			date(vlc.vencimento_fatura) as `vencimento_fatura`, 
			(
				case 
					when (sum(vlc.valor_movimentacao) = 0) then null 
					else sum(vlc.valor_movimentacao) end
			) as `valor_fatura`, 
			vlc.id_usuario
from 		`vw_lancamento_cartao` as `vlc`
group by `vlc`.`id_cartao`, 
			`vlc`.`vencimento_fatura`
having 	((format(sum(vlc.valor_movimentacao), 2)) <> 0)
order by `vlc`.`vencimento_fatura` asc ;


-- Copiando estrutura para view finances.vw_lancamento_cartao
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_lancamento_cartao`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `finances`.`vw_lancamento_cartao` AS select	mov.id_movimentacao,
			mov.id_tipo_movimentacao,
			ctr.id_cartao,
			ctr.descricao_cartao,
			ctr.vencimento_cartao,
			ctr.fechamento_cartao,
			mov.data_movimentacao,			 
			case 
				when day(mov.data_movimentacao) > ctr.fechamento_cartao then
					date_add(concat(year(mov.data_movimentacao), '-', month(mov.data_movimentacao), '-', ctr.fechamento_cartao), INTERVAL 0 DAY) 
				else 
					date_sub(concat(year(mov.data_movimentacao), '-', month(mov.data_movimentacao), '-', ctr.fechamento_cartao), INTERVAL 1 MONTH) 
			end  									as inicio_fatura,
			case
				when day(mov.data_movimentacao) > ctr.fechamento_cartao then				
					date_add(concat(year(mov.data_movimentacao), '-', month(mov.data_movimentacao), '-', ctr.fechamento_cartao), INTERVAL 1 MONTH)
				else 
					date_add(concat(year(mov.data_movimentacao), '-', month(mov.data_movimentacao), '-', ctr.fechamento_cartao), INTERVAL 1 DAY)
			end 									as fim_fatura,
			case
				when day(mov.data_movimentacao) > ctr.fechamento_cartao then					
					if (month(mov.data_movimentacao) = 2,
						date(concat(year(mov.data_movimentacao), '-', month(mov.data_movimentacao), '-', 28)),
						date(date_add(concat(year(mov.data_movimentacao), '-', month(mov.data_movimentacao), '-', ctr.vencimento_cartao), INTERVAL 1 MONTH))					) 
					
				else
					if (month(mov.data_movimentacao) = 2,
						date(concat(year(mov.data_movimentacao), '-', month(mov.data_movimentacao), '-', 28)), 
						date(concat(year(mov.data_movimentacao), '-', month(mov.data_movimentacao), '-', ctr.vencimento_cartao))
					) 
			end									as vencimento_fatura,
			mov.descricao_movimentacao,
			case mov.id_tipo_movimentacao
				when 7 then
					mov.valor_movimentacao * -1	
				when 8 then
					mov.valor_movimentacao * -1
			else
					mov.valor_movimentacao			
			end 									as valor_movimentacao,
			mov.id_usuario
from		cartao 								ctr
			inner join movimentacao			mov on ctr.id_cartao = mov.id_cartao 
where		mov.id_tipo_movimentacao in (3,8) ;


-- Copiando estrutura para view finances.vw_movimentacao
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_movimentacao`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `finances`.`vw_movimentacao` AS select 	mov.id_movimentacao , 
			null as id_movimentacao_origem,
			mov.id_tipo_movimentacao,
			tmv.tipo_movimentacao,
			mov.descricao_movimentacao,
			cat.id_categoria,
			cat.descricao_categoria,
			(case 
				when (mov.id_tipo_movimentacao <> 4) then 
					mov.valor_movimentacao 
				else 
					(mov.valor_movimentacao * -(1)) 
			end) as valor_movimentacao,
			mov.realizado,
			mov.data_movimentacao,
			mov.data_inclusao,
			mov.id_conta,
			ct.descricao_conta,  
			null as id_conta_destino, 
			null as descricao_conta_destino,
			mov.id_cartao,
			mov.id_usuario,
			false as fatura
from 		movimentacao mov
			left join tipo_movimentacao 		tmv 	on mov.id_tipo_movimentacao = tmv.id_tipo_movimentacao
			left join categoria 					cat 	on mov.id_categoria = cat.id_categoria
			left join conta 						ct 	on mov.id_conta = ct.id_conta
			left join cartao 						ca 	on mov.id_cartao = ca.id_cartao
where 	mov.id_tipo_movimentacao <> 4
union all
select 	0,
			0,
			5,
			'Saldo Inicial', 
			concat('Saldo inicial - ',ct.descricao_conta), 
			null,
			'Outros',
			ct.saldo_inicial,
			1, 
			cast(ct.data_inclusao as date),
			ct.data_inclusao,
			ct.id_conta,
			ct.descricao_conta, 
			null, 
			null, 
			null,
			ct.id_usuario,
			false as fatura
from 		conta ct
where 	ct.ativo_conta = 1
union all
select (
			select mov2.id_movimentacao
			from movimentacao mov2
			where (
						(mov.data_inclusao = mov2.data_inclusao) 
						and (mov2.id_tipo_movimentacao = 4) 
						and (mov2.valor_movimentacao > 0)
					)
			group by mov2.data_inclusao
		) as id_movimentacao_destino,
		(
			select mov2.id_movimentacao
			from movimentacao mov2
			where (
					(mov.data_inclusao = mov2.data_inclusao) 
					and (mov2.id_tipo_movimentacao = 4) 
					and (mov2.valor_movimentacao < 0)
				)
			group by mov2.data_inclusao
		) as id_movimentacao,
		mov.id_tipo_movimentacao,
		tmv.tipo_movimentacao,
		mov.descricao_movimentacao,
		cat.id_categoria,
		cat.descricao_categoria,
		(
			select mov1.valor_movimentacao
			from movimentacao mov1
			where ((`mov`.`data_inclusao` = `mov1`.`data_inclusao`) and (`mov1`.`id_tipo_movimentacao` = 4))
			group by `mov1`.`data_inclusao`
		) as `valor`,
		`mov`.`realizado` as `realizado`,
		`mov`.`data_movimentacao` as `data_movimentacao`,
		`mov`.`data_inclusao` as `data_inclusao`,
		(
			select `mov2`.`id_conta`
			from `movimentacao` `mov2`
			where ((`mov`.`data_inclusao` = `mov2`.`data_inclusao`) and (`mov2`.`id_tipo_movimentacao` = 4) and (`mov2`.`valor_movimentacao` < 0))
			group by `mov2`.`data_inclusao`
		) as `de`,
		(
			select `ct2`.`descricao_conta`
			from (`movimentacao` `mov2`
			join `conta` `ct2` on((`mov2`.`id_conta` = `ct2`.`id_conta`)))
			where ((`mov`.`data_inclusao` = `mov2`.`data_inclusao`) and (`mov2`.`id_tipo_movimentacao` = 4) and (`mov2`.`valor_movimentacao` < 0))
			group by `mov2`.`data_inclusao`
		) as `conta_de`,
		(
			select `mov2`.`id_conta`
			from `movimentacao` `mov2`
			where ((`mov`.`data_inclusao` = `mov2`.`data_inclusao`) and (`mov2`.`id_tipo_movimentacao` = 4) and (`mov2`.`valor_movimentacao` > 0))
			group by `mov2`.`data_inclusao`
		) as `para`,
		(
			select `ct2`.`descricao_conta`
			from (`movimentacao` `mov2`
			join `conta` `ct2` on((`mov2`.`id_conta` = `ct2`.`id_conta`)))
			where ((`mov`.`data_inclusao` = `mov2`.`data_inclusao`) and (`mov2`.`id_tipo_movimentacao` = 4) and (`mov2`.`valor_movimentacao` > 0))
			group by `mov2`.`data_inclusao`
		) as `conta_para`, 
		null as `null`,
		`mov`.`id_usuario` as `id_usuario`,
		false as fatura
from (((`movimentacao` `mov`
		join `conta` `ct` on((`mov`.`id_conta` = `ct`.`id_conta`)))
		join `tipo_movimentacao` `tmv` on((`mov`.`id_tipo_movimentacao` = `tmv`.`id_tipo_movimentacao`)))
		join `categoria` `cat` on((`mov`.`id_categoria` = `cat`.`id_categoria`)))
where (`mov`.`id_tipo_movimentacao` = 4)
group by `mov`.`data_inclusao`
union all
select 	0,
			0,
			6,
			'Cartão de Crédito - Fatura',
			concat('Fatura cartao ', vlc.descricao_cartao),
			null,
			null,
			sum(vlc.valor_movimentacao),
			null,
			date(vlc.vencimento_fatura),
			null,
			null,
			null,
			null,
			null,
			vlc.id_cartao,
			vlc.id_usuario,
			true as fatura
from		vw_lancamento_cartao vlc
group by vlc.id_cartao,
			vlc.vencimento_fatura ;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
