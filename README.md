<p align="center"><img src="Img/logo.png" width="400" alt="Laravel Logo"></p>

## ℹ️ Sobre
Este projeto visa realizar o controle e emissão de contratos de comodato (Termo de Responsabilidade) emitidos, para controle de empréstimos de um ativo para um comodatário (utilizador do ativo).

## 💻 Recomendações

Antes de começar, verifique se você atendeu aos seguintes recomendações de ambiente:

* Precisa ter instalado ferramentas de virtualização de servidores, como o XAMPP ou WAMP, pois possui as distruibuições necessárias para rodar a solução.
* Possuir uma máquina com o `Windows` instalado.
  
## 🚀 Começando

Essas instruções permitirão que você obtenha uma cópia do projeto em operação na sua máquina local para fins de desenvolvimento e teste.

### 📋 Pré-requisitos

Segue o link para download do XAMPP para instalação. Pois é um pré-requisito para o uso da solução.

```
https://www.apachefriends.org/pt_br/index.html
```

### 🔧 Instalação

Para instalação da aplicação, é necessário clonar o repositório:

```
git clone https://github.com/filipealmeida97/harmonico.git
```

Depois de clonado, importar o banco de dados contido no arquivo `contrato.sql`, MySQL, encontrado no diretorio indicado:

```
├───App
│   ├───Config
│   │       contrato.sql
│   ├───Contratos
│   ├───Control
│   ├───Images
│   ├───Model
│   ├───Resources
│   └───Templates
├───Lib
└───vendor
```
Depois da importação do arquivo `contrato.sql`, dar "Start" nos módulos `Apache` e `MySQL` via servidor virtual. Após isso acessar o endereço `localhost/harmonico/` no navegador.

<img src="Img/home.png" alt="Página Home"/>

Se tudo der certo, aparecerá a tela acima 👆​.

## ⚙️ Executando o sistema

Exemplificando o uso da solução, vamos mostrar algumas telas do sistema para o entendimento do projeto. 

#### 🖥️ Visualização de ativos

<img src="Img/v_ativos.png" alt="Datagrid de Ativos"/>

#### ⌨️​ Cadastro de ativos

<img src="Img/c_ativos.png" alt="Formulário de Ativos"/>

#### ​🖋️​ Editando ativo e visualizando seus contratos

<img src="Img/e_ativos.png" alt="Edição de Ativos"/>

#### ​📃 Datagrid de contratos

<img src="Img/v_contratos.png" alt="Edição de Ativos"/>

Exemplo de algumas telas do sistema que podem ser executadas.

## 📦 Implantação

Até o momento esse projeto tem o cunho didático e não comercial, demostrando uso do padrão MVC, o qual sustenta o framework (`Golf`) usado para o desenvolvimento da solução. Porém dependendo do escopo do projeto, que o arquiteto julgue se seu uso é apropriado e útil.

## 🛠️ Construído com

As ferramentas usadas para criação do projeto

<code><img height="32" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/javascript/javascript.png" alt="Javascript"/></code>
<code><img height="32" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/html/html.png" alt="HTML5"/></code>
<code><img height="32" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/css/css.png" alt="CSS"/></code>
<code><img height="32" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/bootstrap/bootstrap.png" alt="Bootstrap"/></code>
<code><img height="32" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/mysql/mysql.png" alt="MySQL"/></code>
<code><img height="32" src="https://raw.githubusercontent.com/github/explore/ccc16358ac4530c6a69b1b80c7223cd2744dea83/topics/php/php.png" alt="PHP"/></code>
<code>FRAMEWORK GOLF</code>

## 📌 Versão

Nós usamos [SemVer](http://semver.org/) para controle de versão.

## ✒️ Autores

Aqui vai algumas menções honrosas.

* **Filipe Almeida** - *Trabalho Inicial e Final* - [filipealmeida97](https://github.com/filipealmeida97)

## 📄 Copyright

Todos os direitos reservados.

## 🎁 Expressões de gratidão

* A Deus 🦁;
* Família 👨‍👩‍👧‍👦;
* Meu Amor ❤️;
