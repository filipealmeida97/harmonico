# Harmônico

Este projeto visa realizar o controle e emissão de contratos de comodato (Termo de Responsabilidade) emitidos, para controle de empréstimos de um ativo para um comodatário (utilizador do ativo).
## 💻 Pré-requisitos

Antes de começar, verifique se você atendeu aos seguintes requisitos:

* Precisa ter instalado ferramentas de virtualização de servidores, como o XAMPP ou WAMP, pois possui as distruibuições necessárias para rodar a solução.
* Possuir uma máquina com o `Windows` instalado.
  
## 🚀 Começando

Essas instruções permitirão que você obtenha uma cópia do projeto em operação na sua máquina local para fins de desenvolvimento e teste.

Consulte **[Implantação](#-implanta%C3%A7%C3%A3o)** para saber como implantar o projeto.

### 📋 Pré-requisitos

Segue o link para download do XAMPP para instalação.

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
Depois da importação do arquivo `.sql`, dar "Start" no `Apache` e `MySQL` via servidor virtual. Após isso acessar `localhost/harmonico/`.

<img src="Img/home.png" alt="Página Home"/>

Se tudo der certo, aparecerá a tela acima 👆​.

## ⚙️ Executando o sistema

Exemplificando o uso da solução, vamos mostrar as datagrids do sistema. 

#### 👁️ Visualização de ativos

<img src="Img/v_ativos.png" alt="Datagrid de Ativos"/>

#### ⌨️​ Cadastro de ativos

<img src="Img/c_ativos.png" alt="Formulário de Ativos"/>

#### ​🖋️​ Editando ativo e visualizando seus contratos

<img src="Img/e_ativos.png" alt="Edição de Ativos"/>

### 🔩 Analise os testes de ponta a ponta

Explique que eles verificam esses testes e porquê.

```
Dar exemplos
```

### ⌨️ E testes de estilo de codificação

Explique que eles verificam esses testes e porquê.

```
Dar exemplos
```

## 📦 Implantação

Adicione notas adicionais sobre como implantar isso em um sistema ativo

## 🛠️ Construído com

Mencione as ferramentas que você usou para criar seu projeto

* [Dropwizard](http://www.dropwizard.io/1.0.2/docs/) - O framework web usado
* [Maven](https://maven.apache.org/) - Gerente de Dependência
* [ROME](https://rometools.github.io/rome/) - Usada para gerar RSS

## 🖇️ Colaborando

Por favor, leia o [COLABORACAO.md](https://gist.github.com/usuario/linkParaInfoSobreContribuicoes) para obter detalhes sobre o nosso código de conduta e o processo para nos enviar pedidos de solicitação.

## 📌 Versão

Nós usamos [SemVer](http://semver.org/) para controle de versão. Para as versões disponíveis, observe as [tags neste repositório](https://github.com/suas/tags/do/projeto). 

## ✒️ Autores

Mencione todos aqueles que ajudaram a levantar o projeto desde o seu início

* **Um desenvolvedor** - *Trabalho Inicial* - [umdesenvolvedor](https://github.com/linkParaPerfil)
* **Fulano De Tal** - *Documentação* - [fulanodetal](https://github.com/linkParaPerfil)

Você também pode ver a lista de todos os [colaboradores](https://github.com/usuario/projeto/colaboradores) que participaram deste projeto.

## 📄 Licença

Este projeto está sob a licença (sua licença) - veja o arquivo [LICENSE.md](https://github.com/usuario/projeto/licenca) para detalhes.

## 🎁 Expressões de gratidão

* Conte a outras pessoas sobre este projeto 📢;
* Convide alguém da equipe para uma cerveja 🍺;
* Um agradecimento publicamente 🫂;
* etc.


---
⌨️ com ❤️ por [Armstrong Lohãns](https://gist.github.com/lohhans) 😊
