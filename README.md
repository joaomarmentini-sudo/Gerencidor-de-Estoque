Sistema Web de Gerenciamento de Estoque - Loja de Calçados

Projeto desenvolvido como parte da disciplina **Desenvolvimento Web**, com o objetivo de aplicar conceitos de **programação back-end (PHP)**, **banco de dados (MySQL)** e **front-end (HTML, CSS, Bootstrap e JavaScript)**.

---

## Descrição do Projeto

O sistema é um **Gerenciador de Estoque para uma Loja de Calçados**, que permite o controle completo de produtos, tamanhos, fornecedores e usuários.  
Possui autenticação, controle de acesso por nível (admin/funcionário), geração de relatórios e segurança de dados.

---

## Funcionalidades Principais

### Autenticação
- Login com controle de sessão.
- Recuperação de senha com geração automática de nova senha.
- Cadastro de usuários com níveis de acesso (Administrador e Funcionário).

### Gestão de Produtos*
- CRUD completo (Criar, Listar, Editar e Excluir produtos).
- Registro de **tamanhos de calçados (33 a 46)** com quantidade individual.
- Upload de imagem do produto.
- Associação com categorias e fornecedores.

### Relatórios
- Geração de relatórios em **Excel (.xls)** com todos os produtos.
- Informações incluem nome, preço, categoria, fornecedor e estoque por tamanho.

### Gestão de Usuários
- Cadastro e controle de usuários no sistema.
- Perfis de acesso:
  - **Administrador**: acesso total.
  - **Funcionário**: acesso restrito à visualização e listagem de produtos.

### Segurança
- Hash de senhas com `password_hash()`.
- Proteção contra SQL Injection (uso de consultas preparadas com PDO).
- Validação de formulários (front-end e back-end).
- Proteção contra XSS.

---

Tecnologias Utilizadas

| Camada | Tecnologia |
|--------|-------------|
| Front-end | HTML5, CSS3, Bootstrap 5, JavaScript |
| Back-end | PHP 8 (PDO) |
| Banco de Dados | MySQL |
| Relatórios | Exportação em Excel |
| Servidor local | XAMPP |

---

## Estrutura de Pastas

```
T1_Desenvolvimentoweb/
│
├── config.php
├── deshboard.php
│
├── controller/
│   ├── loginController.php
│   ├── produtoController.php
│   ├── usuarioController.php
|   |── logout.php
│
├── view/
│   ├── login.php
│   ├── produtos/
│   │   ├── criar.php
│   │   ├── editar.php
│   │   ├── listar.php
│   │   ├── detalhes.php
│   │   ├── relatorio.php
│   │
│   └── usuarios/
│       ├── cadastro.php
│       ├── recuperar.php
│
├── uploads/
│   └── (imagens dos produtos)
│
└── model
      ├── create_db.sql
```

---

**Configuração do Projeto**

### Requisitos
- XAMPP (Apache + MySQL)
- PHP 8+
- MySQL 5.7 ou superior
- Navegador atualizado (Chrome, Edge, Firefox)

### Instalação
1. Copie a pasta do projeto para:
   ```
   C:\xampp\htdocs\
   ```
2. Inicie o **Apache** e o **MySQL** pelo XAMPP.

3. No phpMyAdmin, crie o banco de dados:
   ```sql
   CREATE DATABASE gerenciador_estoque CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```
4. Importe o arquivo `banco_de_dados.sql`.

5. Configure o arquivo `config.php` com suas credenciais MySQL:
   ```php
   $pdo = new PDO("mysql:host=localhost;dbname=gerenciador_estoque;charset=utf8", "root", "");
   ```

6. Acesse o sistema:
   ```
   http://localhost/T1_Desenvolvimentoweb/view/login.php
   ```

## 👤 **Usuário Inicial (Admin)**
Após importar o banco, crie manualmente um administrador:
```sql
INSERT INTO usuarios (nome, email, senha, tipo)
VALUES ('Administrador', 'admin@loja.com', 
        '$2y$10$abcdefghijklmnopqrstuvHASHDAEXEMPLO1234567', 
        'admin');
```
> Substitua o hash por um gerado com `password_hash('1234', PASSWORD_DEFAULT)`.
