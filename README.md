
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
$ sail php artisan key:generate
```

-----

#### Para acessar o sistema, entra no link abaixo
http://localhost:8888

```bash
user: local@localhost.com
pass: password
```
## 🔗 Links
[![portfolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://github.com/bhcosta90?tab=repositories&q=portfolio&type=source&language=&sort=)
[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/bhcosta90/)
[![twitter](https://img.shields.io/badge/twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white)](https://twitter.com/bhcosta90)
