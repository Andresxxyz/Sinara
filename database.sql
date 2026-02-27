

-- ETAPA 0: PREPARAÇÃO (O "DO ZERO")
DROP DATABASE IF EXISTS repositorio;
CREATE DATABASE repositorio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE repositorio;

/*
-- -----------------------------------------------------------------
-- ETAPA 1: CRIAÇÃO DE TABELAS (EXATAMENTE O QUE VOCÊ MANDOU)
-- -----------------------------------------------------------------
*/

create table AreaEstudo (
    idAreaEstudo int not null,
    nome varchar(50) not null,
    dtPubli date not null,
    dtAtual date not null,
    primary key (idAreaEstudo)
);

create table Funcao (
    idFuncao int  not null,
    Nome varchar(25) not null,
    dtPubli date not null,
    dtAtual date not null,
    primary key (idFuncao)
);

create table Usuario (
    idUsuario int not null, 
    idFuncao int not null,
    nome varchar(150) not null,
    matricula varchar(20) not null,
    linkCurriculo text,
    emailInstitucional varchar(254),
    senha varchar(255),
    sobre varchar(700),
    dtPubli date not null,
    dtAtual date not null,
    fotoPerfil varchar(150),
    token_recuperacao VARCHAR(255) NULL,
    token_expiracao DATETIME NULL,
    primary key (idUsuario),
    constraint fkFuncao foreign key(idFuncao) references Funcao(idFuncao)
);

create table AreaEstudoUsuario (
    idAreaEstudoUsuario int auto_increment not null,
    idUsuario int not null,
    idAreaEstudo int not null,
    primary key (idAreaEstudoUsuario),
    constraint fkA_Usuario foreign key (idUsuario) references Usuario(idUsuario),
    constraint fkB_AreaEstudo foreign key (idAreaEstudo) references AreaEstudo(idAreaEstudo)
);

create table Projeto (
    idProjeto int auto_increment not null,
    idUsuario int not null,
    nomeProj VARCHAR(100) not null, -- Aumentei de 50 para 100
    descricaoProj text not null,
    palavrasChavesProj varchar(150) not null,
    linhaPesquisaProj text not null,
    urlInstagram text,
    urlYoutube text,
    urlTiktok text,
    urlFacebook text,
    colaboradores text,
    dtEncontros text,
    urlLogo text,
    primary key (idProjeto),
    CONSTRAINT fkD_Usuario FOREIGN KEY(idUsuario) REFERENCES Usuario(idUsuario)
);

create table ProjetoArea (
    idProjetoArea int not null auto_increment,
    idAreaEstudo int not null,
    idProjeto int not null,
    primary key(idProjetoArea),
    constraint fkC_AreaEstudo foreign key(idAreaEstudo) references AreaEstudo(idAreaEstudo),
    constraint fk_Projeto foreign key(idProjeto) references Projeto(idProjeto)
);

create table Imagens (
    idImagem int auto_increment not null,
    urlImagem text,
    primary key(idImagem)
);

create table ImagensProjeto (
    idImagemProjeto int auto_increment not null,
    idImagem int not null,
    idProjeto int not null,
    primary key(idImagemProjeto),
    constraint fkA_Projeto foreign key(idProjeto) references Projeto(idProjeto),
    constraint fk_imagem foreign key(idImagem) references Imagens(idImagem)
);

CREATE TABLE Trabalho (
    idTrabalho INT NOT NULL AUTO_INCREMENT, 
    idUsuario INT NOT NULL,
    idProjeto INT NOT NULL, -- ISTO VAI SER CORRIGIDO ABAIXO
    arquivoTrabalho VARCHAR(100),
    titulo VARCHAR(150) NOT NULL, -- Aumentei de 100 para 150
    palavrasChaves VARCHAR(150),
    resumo TEXT, 
    abstract TEXT,
    linhaPesquisa VARCHAR(255) NULL, 
    anoTrab INT NOT NULL,
    nomePesquisador VARCHAR(150) NOT NULL,
    curriculoAluno TEXT,
    cursoAluno VARCHAR(50), -- Aumentei de 25 para 50
    dtPubli DATETIME NOT NULL,
    PRIMARY KEY (idTrabalho),
    CONSTRAINT fkB_Usuario FOREIGN KEY(idUsuario) REFERENCES Usuario(idUsuario),
    CONSTRAINT fkProj_projeto FOREIGN KEY(idProjeto) REFERENCES Projeto(idProjeto)
);

CREATE TABLE TrabalhoArea (
    idTrabalhoArea INT NOT NULL AUTO_INCREMENT,
    idTrabalho INT NOT NULL,
    idAreaEstudo INT NOT NULL,
    PRIMARY KEY (idTrabalhoArea),
    CONSTRAINT fk_TrabalhoArea_Trabalho FOREIGN KEY (idTrabalho) REFERENCES Trabalho(idTrabalho) ON DELETE CASCADE,
    CONSTRAINT fk_TrabalhoArea_AreaEstudo FOREIGN KEY (idAreaEstudo) REFERENCES AreaEstudo(idAreaEstudo) ON DELETE CASCADE
);

create table Publicacao (
    idPublicacao int not null,
    idUsuario int not null,
    idTrabalho int not null,
    primary key (idPublicacao),
    constraint fkC_Usuario foreign key(idUsuario) references Usuario(idUsuario),
    constraint fkTrabalho foreign key(idTrabalho) references Trabalho(idTrabalho)
);


/*
-- -----------------------------------------------------------------
-- ETAPA 2: CORREÇÃO CRUCIAL DO SCHEMA
-- -----------------------------------------------------------------
*/

-- SEU PEDIDO: "atrele algumas pesquisas a alguns diferentes projetos e ALGUNS NAO"
-- SEU SCHEMA: "idProjeto INT NOT NULL"
-- Esses dois são contraditórios. Para um trabalho NÃO ter projeto (NULL), 
-- a coluna NÃO PODE ser "NOT NULL".
-- Este comando corrige isso, permitindo trabalhos avulsos.
ALTER TABLE Trabalho MODIFY COLUMN idProjeto INT NULL;


/*
-- -----------------------------------------------------------------
-- ETAPA 3: INSERTS BÁSICOS (O QUE VOCÊ MANDOU)
-- -----------------------------------------------------------------
*/
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (101, "Artes", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (102, "Audiovisual", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (103, "Biologia", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (104, "Design", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (105, "Educação Física", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (106, "Filosofia", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (107, "Física", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (108, "Geografia", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (109, "História", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (110, "Informática", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (111, "Letras", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (112, "Matemática", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (113, "Química", "2025-05-20", "2025-05-20");
INSERT INTO AreaEstudo(idAreaEstudo, nome, dtPubli, dtAtual) VALUES (114, "Sociologia", "2025-05-20", "2025-05-20");

INSERT INTO Funcao(idFuncao, nome, dtPubli, dtAtual) values(1, "Professor", "2025-05-20", "2025-05-20"), (2, "Coordenador", "2025-05-20", "2025-05-20");

/*
-- -----------------------------------------------------------------
-- ETAPA 4: INSERTS DE USUÁRIOS (O SEU + O MEU "COMPLETO")
-- -----------------------------------------------------------------
-- Senhas hasheadas usando password_hash() do PHP.
-- NUNCA salve 'akira@1234' direto no banco.
*/
INSERT INTO Usuario(idUsuario, idFuncao, nome, matricula, linkCurriculo, emailInstitucional, senha, sobre, dtPubli, dtAtual, fotoPerfil) VALUES
(201, 2, "Enio Akira Oishi", "SM229477", "http://lattes.cnpq.br/7998780072651670", "akira.oishi@ifsp.edu.br", '$2y$10$T.f2V.PzHl3/SgD8/P.14.sW61/z82/qK./.gq.b3J.iO3kZ.N6Pq', "Mestre em Matemática. (Senha: akira@1234)","2025-05-20", "2025-05-26", "uploads/semFotoPerfil.png"),
(202, 2, "Altair Aparecido de Oliveira Filho", "SM229283", "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(203, 2, "Andre Batista Noronha Moreira", "SP210699",  "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(204, 1, "Daniela Garcia Bueno", "SM24157X",  "","daniela.bueno@ifsp.edu.br",'$2y$10$J0vD7E.R.n.G/k.K.8.L.t.u.R.U.I.H.y.c.X.q.c.Y.z.v.Z.e.w', "Especialista em Artes e Design. (Senha: dani@ifsp)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(205, 2, "Daniel Martins Gusmai", "SM248228",  "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(206, 1, "Enoque Marques Portes", "SM22859X",  "","enoque.portes@ifsp.edu.br",'$2y$10$r.T.v.Z.q.H.M.K.J.U.t.G.Y.e.W.r.Y.m.X.v.N.Z.U.H.I.L.g', "Professor de desenvolvimento de sistemas. (Senha: portesEnoque)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(207, 1, "Erico de Souza Veriscimo", "SM239252",  "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(208, 1, "Fabio Donizete Bueno", "SM228722",  "","fabio.bueno@ifsp.edu.br",'$2y$10$o.L.B.r.X.K.s.g.H.W.E.R.G.D.X.q.P.K.L.s.y.o.A.M.K.O.q', "Professor de Física e Matemática. (Senha: fabioB123)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(209, 1, "Gilberto de Almeida Correa Junior", "IQ213469",  "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(210, 1, "Glaucia Bueno Benedetti Berbel", "SM262237",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(211, 1, "Gleyce Rodrigues dos Anjos Araújo", "SM262298",  "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(212, 1, "Greice Kelly de Oliveira", "SM229301",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(213, 1, "João Rodrigues de Souza Filho Valença", "SM266589",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(214, 2, "Kelma Cristina de Freitas", "CJ210997",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(215, 1, "Leonardo Alves da Cunha Carvalho", "SM228692",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(216, 1, "Leonardo Coelho Siqueira", "SM267545",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(217, 1, "Marcelo Eduardo Pereira Sgrilli", "SM235131",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(218, 1, "Mayara Fior Oliveira", "SM233304",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(219, 1, "Milca Vasni Ceccon", "SM228965",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(220, 1, "Priscila Silva Queiroz Cevada", "SM259834",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(221, 1, "Renata Carolina e Silva Rocha Pinto", "SM255361",  "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(222, 1, "Rita de Cassia da Silva Soares", "SM268070",  "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(223, 1, "Rodrigo Holdschip", "SM233286",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(224, 1, "Silas Luiz Alves Silva", "SM229325",   "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(225, 1, "Simone Caldeira Alencar", "SM261865",  "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(226, 1, "Suzy Sayuri Sassamoto Kurokawa", "SM228801", "","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(227, 1, "Vladimir Camelo Pinto", "SP240291","","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(228, 1, "Teste da silva", "SM1234567","","","", NULL, "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(229, 1, "Teste Logado da silva", "SM7654321","","teste.logado@ifsp.edu.br",'$2y$10$yI1E9Kk/T3i/A5G.lJc.J.J.w./Q.d.t.e.j/X.G.m.J.H.i.Q.q.C', "(Senha: 1234)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(230, 1, "Carla Matos", "SM300101", "", "carla.matos@ifsp.edu.br", '$2y$10$E.a.Q.O.p.F.r.j.R.Z.T.u.q.V.R.k.I.o.l.u.h.Z.C.x.R.o', "Especialista em Modelagem Matemática. (Senha: carla@mat)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(231, 1, "Roberto Dias", "SM300102", "", "roberto.dias@ifsp.edu.br", '$2y$10$t.G.y.T.L.r.X.i.A.d.J.W.K.t.x.z.N.J.Y.p.V.b.W.h.k', "Pesquisador de História Digital. (Senha: roberto@hist)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(232, 1, "Sofia Mendes", "SM300103", "", "sofia.mendes@ifsp.edu.br", '$2y$10$F.A.p.e.m.i.o.g.q.e.k.t.L.X.p.q.e.v.X.j.W.o.i.r.u', "Doutora em Química Verde. (Senha: sofia@quim)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(233, 1, "Marcos Andrade", "SM300104", "", "marcos.andrade@ifsp.edu.br", '$2y$10$W.h.W.I.X.A.k.m.K.l.s.S.g.u.G.m.J.J.B.G.N.i.n.O.W', "Inteligência Artificial aplicada à Educação. (Senha: marcos@ia)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(234, 1, "Paula Ferreira", "SM300105", "", "paula.ferreira@ifsp.edu.br", '$2y$10$J.C.o.R.S.Y.P.u.U.G.l.m.Q.W.y.X.C.B.G.P.C.Q.v.s.u', "Design Inclusivo e Acessibilidade. (Senha: paula@design)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(235, 1, "Bruno Costa", "SM300106", "", "bruno.costa@ifsp.edu.br", '$2y$10$K.O.q.o.W.V.o.S.e.X.x.j.I.o.z.X.U.Y.K.L.Q.n.X.Y.Y', "Especialista em Robótica Social. (Senha: bruno@robo)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(236, 1, "Helena Gomes", "SM300107", "", "helena.gomes@ifsp.edu.br", '$2y$10$P.w.l.V.H.e.E.j.Z.e.w.r.i.Z.O.y.S.S.L.g.o.l.j.w.a', "Narrativas em Audiovisual. (Senha: helena@audio)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(237, 1, "Alex Ribeiro", "SM300108", "", "alex.ribeiro@ifsp.edu.br", '$2y$10$e.L.q.v.w.b.e.W.w.K.g.H.X.O.E.i.O.Y.e.q.q.E.H.w.o', "Filosofia da Tecnologia. (Senha: alex@filo)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(238, 1, "Lucia Martins", "SM300109", "", "lucia.martins@ifsp.edu.br", '$2y$10$V.K.A.i.P.o.k.g.f.J.A.W.P.P.z.j.Z.h.J.U.X.k.S.o.M', "Mestrado em Biologia Marinha. (Senha: lucia@bio)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(239, 1, "Tiago Alves", "SM300110", "", "tiago.alves@ifsp.edu.br", '$2y$10$T.A.S.i.e.q.x.l.i.m.U.y.R.K.S.G.y.Z.t.W.S.U.n.L.i', "Geoprocessamento e Cartografia. (Senha: tiago@geo)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png"),
(240, 1, "Sandra Nunes", "SM300111", "", "sandra.nunes@ifsp.edu.br", '$2y$10$s.f.z.I.c.j.Z.A.m.H.B.L.k.W.X.k.D.l.F.A.Z.U.p.O.E', "Sociologia do Trabalho. (Senha: sandra@socio)", "2025-05-20", "2025-08-05", "uploads/semFotoPerfil.png");

/*
-- -----------------------------------------------------------------
-- ETAPA 5: ÁREAS DE ESTUDO DOS USUÁRIOS (O "COMPLETO")
-- -----------------------------------------------------------------
*/
INSERT INTO AreaEstudoUsuario (idUsuario, idAreaEstudo) VALUES
(201, 110), (201, 112), (204, 101), (204, 104), (206, 110), (208, 107), (208, 112), (209, 103), (211, 111), (212, 113),
(215, 110), (217, 101), (223, 110), (224, 105), (226, 109), (230, 112), (231, 109), (231, 110), (232, 113), (233, 110),
(233, 105), (234, 104), (234, 114), (235, 110), (236, 102), (237, 106), (237, 110), (238, 103), (239, 108), (240, 114);

/*
-- -----------------------------------------------------------------
-- ETAPA 6: PROJETOS CABULOSOS (O "COMPLETO")
-- -----------------------------------------------------------------
*/
INSERT INTO Projeto (idUsuario, nomeProj, descricaoProj, palavrasChavesProj, linhaPesquisaProj, colaboradores, urlLogo, urlInstagram) VALUES 
(201, 'Sinara Repositório', 'Projeto de desenvolvimento do repositório institucional Sinara para TCCs e Pesquisas do IFSP Salto.', 'repositório, sinara, tcc, php, mysql', 'Engenharia de Software, Sistemas Web', 'João da Silva, Maria Oliveira, Lucas Martins', 'assets/img/fotoPerfilProj.png', NULL),
(204, 'Arte e Comunidade', 'Projeto de extensão focado na interação da arte urbana com a comunidade de Salto.', 'arte, extensão, comunidade, grafite', 'Artes Visuais, Sociologia Urbana', 'Carlos Pereira', 'assets/img/fotoPerfilProj.png', 'https://instagram.com/artecomunidade'),
(208, 'Física de Partículas (Grupo de Estudos)', 'Grupo de estudos avançados sobre a física de partículas e simulações computacionais.', 'física, partículas, cern, simulação', 'Física Teórica, Computação Quântica', 'Ana Beatriz Souza, Mateus Prado', 'assets/img/fotoPerfilProj.png', NULL),
(209, 'Biogenética de Briófitas', 'Pesquisa de laboratório sobre a resiliência genética de briófitas em ambientes controlados.', 'biologia, genética, briófitas, musgo', 'Biologia Celular, Genética', 'Rafael Bessa', 'assets/img/fotoPerfilProj.png', NULL),
(230, 'Matemática Aplicada à Engenharia', 'Modelagem matemática para problemas complexos de engenharia e otimização de processos.', 'matemática, modelagem, engenharia', 'Análise Numérica', 'Fernando Abreu, Beatriz Lima', 'assets/img/fotoPerfilProj.png', NULL),
(231, 'História Digital de Salto', 'Digitalização e análise de arquivos históricos da região de Salto, criando um acervo digital.', 'história, digitalização, arquivos, salto', 'Humanidades Digitais', 'Gabriel Costa, Isabela Rangel', 'assets/img/fotoPerfilProj.png', NULL),
(232, 'Química Verde e Sustentabilidade', 'Desenvolvimento de solventes biodegradáveis para a indústria local e análise de ciclo de vida.', 'química, sustentabilidade, solventes', 'Química Ambiental', 'Larissa Moreira, Daniel Alves', 'assets/img/fotoPerfilProj.png', 'https://instagram.com/quimicaverde'),
(233, 'Inteligência Artificial na Educação', 'Uso de Inteligência Artificial para personalização do ensino de lógica de programação.', 'ia, educação, lógica, algoritmos', 'Tecnologias Educacionais', 'Tiago Nunes, Vitor Hugo', 'assets/img/fotoPerfilProj.png', NULL),
(234, 'Design Inclusivo e Acessibilidade Web', 'Criação de interfaces e produtos físicos acessíveis para Pessoas com Deficiência (PcD).', 'design, acessibilidade, pcd, ux, web', 'Design de Interação, Ergonomia', 'Juliana Paes', 'assets/img/fotoPerfilProj.png', NULL),
(235, 'Robótica Social e Interação Humano-Robô', 'Estudo da interação humano-robô em ambientes de assistência social e terapêutica.', 'robótica, social, interação, ia', 'Inteligência Artificial, Engenharia de Controle', 'Vanessa Reis', 'assets/img/fotoPerfilProj.png', NULL);

INSERT INTO ProjetoArea (idAreaEstudo, idProjeto) VALUES
(110, 1), (101, 2), (114, 2), (107, 3), (112, 3), (110, 3), (103, 4), (112, 5), (110, 5), (109, 6),
(110, 6), (102, 6), (113, 7), (103, 7), (110, 8), (104, 9), (114, 9), (110, 10), (101, 10), (106, 10);

/*
-- -----------------------------------------------------------------
-- ETAPA 8: TRABALHOS CABULOSOS (O "COMPLETO")
-- (Note os valores 'NULL' na coluna idProjeto: são os trabalhos AVULSOS)
-- -----------------------------------------------------------------
*/
INSERT INTO Trabalho (idUsuario, idProjeto, arquivoTrabalho, titulo, palavrasChaves, resumo, abstract, linhaPesquisa, anoTrab, nomePesquisador, cursoAluno, dtPubli) VALUES
(201, 1, 'uploads/trabalhos/TCC_Joao_BD.pdf', 'Otimização de Consultas SQL em Repositórios de Larga Escala', 'sql, otimização, banco de dados, repositório', 'Este trabalho explora técnicas de otimização de consultas SQL para sistemas de repositórios com grande volume de dados...', 'This work explores SQL query optimization techniques for large-scale repository systems...', 'Engenharia de Software', 2024, 'João da Silva', 'Técnico em Informática', '2024-11-20 10:00:00'),
(206, 1, 'uploads/trabalhos/TCC_Maria_Frontend.pdf', 'Desenvolvimento de Interface Responsiva com React e Bootstrap', 'react, bootstrap, frontend, responsivo', 'Análise comparativa de frameworks frontend para a criação de interfaces responsivas e acessíveis...', 'A comparative analysis of frontend frameworks for creating responsive and accessible interfaces...', 'Sistemas Web', 2024, 'Maria Oliveira', 'Técnico em Informática', '2024-11-21 14:30:00'),
(204, 2, 'uploads/trabalhos/TCC_Carlos_Arte.pdf', 'O Impacto da Arte Urbana na Percepção Social em Salto-SP', 'arte urbana, grafite, sociologia, salto', 'Estudo de caso sobre os murais do projeto Arte e Comunidade e sua influência na percepção de segurança...', 'A case study on the murals of the Art and Community project and their influence on the perception of safety...', 'Artes Visuais, Sociologia Urbana', 2023, 'Carlos Pereira', 'Ensino Médio', '2023-12-05 09:00:00'),
(208, 3, 'uploads/trabalhos/IC_Ana_Particulas.pdf', 'Simulação de Colisão de Partículas em Python', 'física, python, simulação, partículas', 'Utilizando a biblioteca Matplotlib para simular e visualizar a trajetória de partículas subatômicas...', 'Using the Matplotlib library to simulate and visualize the trajectory of subatomic particles...', 'Física Teórica', 2024, 'Ana Beatriz Souza', 'Ensino Médio', '2024-10-01 11:00:00'),
(211, NULL, 'uploads/trabalhos/TCC_Pedro_Letras.pdf', 'A Figura Feminina em Dom Casmurro', 'machado de assis, dom casmurro, literatura', 'Uma análise crítica do papel de Capitu na obra de Machado de Assis...', 'A critical analysis of Capitu''s role in the work of Machado de Assis...', 'Literatura Brasileira', 2023, 'Pedro Guedes', 'Ensino Médio', '2023-11-30 16:00:00'),
(212, NULL, 'uploads/trabalhos/IC_Sofia_Quimica.pdf', 'Reações Químicas em Microgravidade: Um Estudo Teórico', 'química, microgravidade, reação', 'Revisão bibliográfica sobre os desafios da química em ambientes de microgravidade...', 'A literature review on the challenges of chemistry in microgravity environments...', 'Química Inorgânica', 2024, 'Sofia Lima', 'Ensino Médio', '2024-05-15 08:00:00'),
(230, 5, 'uploads/trabalhos/TCC_Fernando_Mat.pdf', 'Modelagem Matemática de Fluxo de Tráfego Urbano', 'matemática, tráfego, modelagem, simulação', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ... (resumo aqui)', '(abstract here)', 'Análise Numérica', 2024, 'Fernando Abreu', 'Técnico em Informática', '2024-11-25 10:00:00'),
(230, 5, 'uploads/trabalhos/IC_Beatriz_MatComp.pdf', 'Análise de Séries Temporais com Python e Modelos ARIMA', 'python, séries temporais, arima, estatística', 'Vestibulum ante ipsum primis in faucibus orci luctus et... (resumo aqui)', '(abstract here)', 'Análise Numérica', 2024, 'Beatriz Lima', 'Técnico em Informática', '2024-11-26 11:00:00'),
(231, 6, 'uploads/trabalhos/TCC_Gabriel_Hist.pdf', 'A Memória Ferroviária de Salto-SP: Uma Análise Digital', 'história, salto, ferrovia, memória digital', 'Aenean nonummy hendrerit mauris. Phasellus porta... (resumo aqui)', '(abstract here)', 'Humanidades Digitais', 2023, 'Gabriel Costa', 'Ensino Médio', '2023-12-01 15:00:00'),
(232, 7, 'uploads/trabalhos/TCC_Larissa_Quimica.pdf', 'Biossolventes Derivados de Glicerol para Tintas', 'química verde, glicerol, biossolvente, sustentabilidade', 'Cum sociis natoque penatibus et magnis dis parturient montes... (resumo aqui)', '(abstract here)', 'Química Ambiental', 2024, 'Larissa Moreira', 'Técnico em Química', '2024-11-10 09:30:00'),
(233, 8, 'uploads/trabalhos/IC_Tiago_IA.pdf', 'Detecção de Plágio em Código Fonte usando Machine Learning', 'ia, machine learning, plágio, educação', 'Donec nec justo eget felis facilisis fermentum... (resumo aqui)', '(abstract here)', 'Tecnologias Educacionais', 2024, 'Tiago Nunes', 'Técnico em Informática', '2024-06-05 14:00:00'),
(235, 10, 'uploads/trabalhos/TCC_Vanessa_Robo.pdf', 'Interface de Controle Gestual para Braço Robótico Assistivo', 'robótica, gestos, acessibilidade, arduino', 'Nullam feugiat, turpis at pulvinar vulputate... (resumo aqui)', '(abstract here)', 'Inteligência Artificial', 2024, 'Vanessa Reis', 'Técnico em Informática', '2024-11-22 17:00:00'),
(208, NULL, 'uploads/trabalhos/IC_Ricardo_Fisica.pdf', 'Estudo da Eficiência de Células Solares de Perovskita', 'física, energia solar, perovskita, eficiência', 'Pellentesque habitant morbi tristique senectus et netus... (resumo aqui)', '(abstract here)', 'Física de Materiais', 2023, 'Ricardo Jorge', 'Ensino Médio', '2023-08-15 10:00:00'),
(211, NULL, 'uploads/trabalhos/TCC_Camila_Letras.pdf', 'Neologismos na Era das Redes Sociais: Um Estudo de Caso do X (Twitter)', 'letras, linguística, neologismo, redes sociais, twitter', 'Morbi in sem quis dui placerat ornare. Pellentesque... (resumo aqui)', '(abstract here)', 'Linguística Aplicada', 2024, 'Camila Furtado', 'Ensino Médio', '2024-11-28 13:00:00'),
(206, NULL, 'uploads/trabalhos/TCC_Diego_Info.pdf', 'Análise de Desempenho: API Gateway em Microsserviços', 'microsserviços, api gateway, spring boot, desempenho', 'Praesent id justo in neque elementum ultrices... (resumo aqui)', '(abstract here)', 'Engenharia de Software', 2024, 'Diego Souza', 'Técnico em Informática', '2024-11-29 10:00:00'),
(201, 1, 'uploads/trabalhos/TCC_Lucas_Seguranca.pdf', 'Implementação de Autenticação OAuth 2.0 em Aplicações PHP', 'segurança, php, oauth 2.0, jwt, sinara', 'Quisque ultrices, eros nec auctor dictum... (resumo aqui)', '(abstract here)', 'Sistemas Web', 2023, 'Lucas Martins', 'Técnico em Informática', '2023-11-20 18:00:00'),
(234, 9, 'uploads/trabalhos/TCC_Juliana_Design.pdf', 'Gamificação como Ferramenta de Acessibilidade em Museus Virtuais', 'design, gamificação, acessibilidade, museu, ux', 'In hac habitasse platea dictumst. Class aptent taciti... (resumo aqui)', '(abstract here)', 'Design de Interação', 2024, 'Juliana Paes', 'Ensino Médio', '2024-11-18 16:00:00'),
(209, NULL, 'uploads/trabalhos/IC_Rafael_Bio.pdf', 'Análise da Qualidade da Água do Rio Tietê em Salto: Bioindicadores', 'biologia, rio tietê, salto, poluição, bioindicadores', 'Sed eget lacus. Donec lectus. Class aptent taciti... (resumo aqui)', '(abstract here)', 'Ecologia Aplicada', 2023, 'Rafael Bessa', 'Técnico em Química', '2023-10-10 12:00:00'),
(236, NULL, 'uploads/trabalhos/TCC_Clara_Audio.pdf', 'O Som como Narrativa no Cinema de Terror Brasileiro', 'audiovisual, cinema, som, narrativa, terror', 'Fusce tellus. Sed consequat, leo eget bibendum... (resumo aqui)', '(abstract here)', 'Comunicação Audiovisual', 2024, 'Clara Bastos', 'Ensino Médio', '2024-11-27 19:00:00'),
(208, 3, 'uploads/trabalhos/IC_Mateus_Cosmo.pdf', 'Detecção de Ondas Gravitacionais: Análise de Dados do LIGO', 'física, ondas gravitacionais, ligo, cosmologia', 'Mauris dictum facilisis augue. Fusce pellentesque... (resumo aqui)', '(abstract here)', 'Física Teórica, Astrofísica', 2023, 'Mateus Prado', 'Ensino Médio', '2023-11-05 15:00:00'),
(237, NULL, 'uploads/trabalhos/TCC_Bruna_Filo.pdf', 'Ética da Inteligência Artificial: O Dilema do Carro Autônomo', 'filosofia, ética, ia, carro autônomo', 'Vestibulum libero nisl, porta vel, scelerisque eget... (resumo aqui)', '(abstract here)', 'Filosofia da Tecnologia', 2024, 'Bruna Teixeira', 'Técnico em Informática', '2024-11-30 11:00:00'),
(201, NULL, 'uploads/trabalhos/TCC_AnaClara_Web.pdf', 'Web Assembly: Comparativo de Performance com JavaScript', 'web assembly, javascript, wasm, performance, web', 'Nam sed tellus id magna elementum tincidunt... (resumo aqui)', '(abstract here)', 'Sistemas Web', 2023, 'Ana Clara', 'Técnico em Informática', '2023-11-25 14:00:00'),
(233, 8, 'uploads/trabalhos/IC_Vitor_IA.pdf', 'Sistema de Recomendação de Atividades Físicas com IA', 'ia, educação física, recomendação, python', 'Proin in tellus sit amet nibh dignissim sagittis... (resumo aqui)', '(abstract here)', 'Tecnologias Educacionais', 2024, 'Vitor Hugo', 'Ensino Médio', '2024-09-10 10:00:00'),
(231, 6, 'uploads/trabalhos/TCC_Isabela_Hist.pdf', 'Reconstituição 3D de Edifícios Históricos de Salto', 'história, 3d, blender, patrimônio', 'Curabitur sagittis hendrerit ante. Aliquam erat volutpat... (resumo aqui)', '(abstract here)', 'Humanidades Digitais', 2024, 'Isabela Rangel', 'Técnico em Informática', '2024-11-26 16:30:00'),
(232, NULL, 'uploads/trabalhos/IC_Daniel_Quimica.pdf', 'Análise de Metais Pesados em Amostras de Solo Urbano', 'química, metais pesados, solo, contaminação', 'Ut tempus purus at lorem. Maecenas fermentum... (resumo aqui)', '(abstract here)', 'Química Analítica', 2023, 'Daniel Alves', 'Técnico em Química', '2023-09-01 13:00:00'),
(230, NULL, 'uploads/trabalhos/TCC_Patricia_Mat.pdf', 'Teoria dos Jogos Aplicada a Decisões Econômicas Simples', 'matemática, teoria dos jogos, economia', 'Integer lacinia. Cras elementum. Fusce consectetuer... (resumo aqui)', '(abstract here)', 'Matemática Aplicada', 2024, 'Patrícia Melo', 'Ensino Médio', '2024-11-15 11:30:00');

/*
-- -----------------------------------------------------------------
-- ETAPA 9: ÁREAS DOS TRABALHOS (O "COMPLETO")
-- -----------------------------------------------------------------
*/
INSERT INTO TrabalhoArea (idTrabalho, idAreaEstudo) VALUES
(1, 110), (2, 110), (2, 104), (3, 101), (3, 114), (4, 107), (4, 110), (5, 111), (6, 113),
(7, 112), (7, 110), (8, 112), (8, 110), (9, 109), (9, 110), (10, 113), (11, 110), (12, 110),
(12, 104), (13, 107), (13, 113), (14, 111), (14, 114), (15, 110), (16, 110), (17, 104),
(17, 110), (17, 101), (18, 103), (18, 113), (19, 102), (20, 107), (21, 106), (21, 110),
(22, 110), (23, 110), (23, 105), (24, 109), (24, 110), (24, 104), (25, 113), (26, 112);

/*
-- -----------------------------------------------------------------
-- ETAPA 10: PUBLICAÇÕES (O "COMPLETO")
-- (Respeitando seu schema com ID manual)
-- -----------------------------------------------------------------
*/
INSERT INTO Publicacao (idPublicacao, idUsuario, idTrabalho) VALUES
(1, 201, 1), (2, 206, 2), (3, 204, 3), (4, 208, 4), (5, 211, 5), (6, 212, 6), (7, 230, 7), 
(8, 230, 8), (9, 231, 9), (10, 232, 10), (11, 233, 11), (12, 235, 12), (13, 208, 13),
(14, 211, 14), (15, 206, 15), (16, 201, 16), (17, 234, 17), (18, 209, 18), (19, 236, 19),
(20, 208, 20), (21, 237, 21), (22, 201, 22), (23, 233, 23), (24, 231, 24), (25, 232, 25),
(26, 230, 26),
-- Twists "Cabulosos" (Coordenadores publicando trabalhos de outros)
(27, 201, 2),  -- Enio (Coord.) publica o trabalho de Maria (que é do Enoque)
(28, 202, 15), -- Altair (Coord.) publica o trabalho avulso do Diego (do Enoque)
(29, 234, 24), -- Paula (Design) publica o trabalho de História 3D (do Roberto)
(30, 201, 20); -- Enio (Coord.) publica o trabalho de Cosmologia (do Fabio)


/*
-- -----------------------------------------------------------------
-- ETAPA 11: FIM
-- -----------------------------------------------------------------
*/
SELECT 'BANCO CRIADO E POPULADO COM SUCESSO CABULOSO!' AS status;
