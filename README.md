# Controle Financeiro

Projeto consiste em um gerenciador de contas a pagar e a receber, está totalmente em docker para poder subir em qualquer ambiente


## Instalação

Para instalar o projeto, você precisa ter o PHP:8.0.2

```bash
$ git clone https://github.com/bhcosta90/portfolio-controle-financeiro.git
$ cp .env.example .env
$ composer install
$ sail up -d
$ sail php artisan migrate --seed
```

-----

#### Para acessar o sistema, entra no link abaixo
`http://localhost:8888/`
    