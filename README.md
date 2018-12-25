# Rolê

A aplicação foi criada utilizando o template básico do Yii 2 (http://www.yiiframework.com/).

## Configuração

### Aplicação
Editar o arquivo `config/web.php`, incluindo uma chave para o
cookieValidationKey.

### Banco de Dados

Editar o arquivo `config/console_db.php` com os dados de conexão do seu banco
com privilégio de definição (DDL):

```php
return [
	'class' => 'yii\db\Connection',
	'dsn' => 'pgsql:host=localhost;dbname=role',
	'username' => 'root',
	'password' => 'root',
	'charset' => 'utf8',
];
```

Editar o arquivo `config/web_db.php` com os dados de conexão do seu banco
com privilégio de manipulação (DML):

```php
return [
	'class' => 'yii\db\Connection',
	'dsn' => 'pgsql:host=localhost;dbname=role',
	'username' => 'root',
	'password' => 'root',
	'charset' => 'utf8',
];
```


### Diretórios

Conceder permissão de escrita para a aplicação aos seguintes diretórios:

		runtime/            contém os arquivos gerados em tempo de execução
		web/assets/         contém os arquivos utilizados pela página web


## Instalação

Alterar os dados de acesso do usuário em `migrations/m130524_201442_create_user_table`.

No terminal, acessar o diretório da aplicação e executar o comando para
criação das tabelas no banco:

~~~
$ php yii migrate
~~~

## Utilização

Copie os arquivo para o diretório raiz de seu servidor web e acesse:

~~~
http://localhost/role/web/
~~~
