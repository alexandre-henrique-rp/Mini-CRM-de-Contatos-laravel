# Mini CRM de Contatos - Desafio Técnico

Este projeto é uma API RESTful desenvolvida em Laravel para um Mini CRM de gestão de contatos. A aplicação permite o gerenciamento completo de contatos (CRUD) e implementa um sistema assíncrono para processamento de pontuação, com atualizações em tempo real utilizando Laravel Reverb.

## Feito Por

* **Autor:** [Alexandre Henrique]
* **Email:** [kingdevtec@gmail.com]
* **LinkedIn:** (www.linkedin.com/in/dev-alexandre-henrique)
---

## 🚀 Tech Stack

* **Backend:** Laravel 12
* **Base de Dados:** MariaDB
* **Filas e Cache:** Redis
* **Servidor WebSocket (Tempo Real):** Laravel Reverb
* **Ambiente de Desenvolvimento:** VS Code Dev Container (Docker)

O projeto foi inteiramente desenvolvido dentro de um ambiente Docker encapsulado, utilizando a funcionalidade de **Dev Containers** do Visual Studio Code. Isto garante total reprodutibilidade e consistência do ambiente, eliminando a necessidade de instalar PHP, Composer, MariaDB ou Redis localmente na máquina do programador.

---

## ⚙️ Como Começar (Getting Started)

Para executar este projeto, necessita apenas de ter o **Docker Desktop** e o **Visual Studio Code** com a extensão [Dev Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) instalada.

1.  **Clone o Repositório:**
    ```bash
    git clone [https://github.com/caminho/para/seu-repositorio.git](https://github.com/caminho/para/seu-repositorio.git)
    cd seu-repositorio
    ```

2.  **Abra no Dev Container:**
    * Abra a pasta do projeto no VS Code.
    * O VS Code detetará a pasta `.devcontainer` e exibirá uma notificação no canto inferior direito. Clique em **"Reopen in Container"**.
    * Aguarde enquanto o VS Code constrói e inicia os contentores Docker (`app`, `db`, `redis`).

3.  **Instale as Dependências e Configure o Ambiente:**
    * Quando o projeto reabrir dentro do contentor, abra um terminal no VS Code (`Ctrl+'`).
    * Execute os seguintes comandos para finalizar a configuração:

    ```bash
    # Instalar dependências do PHP
    composer install

    # Copiar o ficheiro de ambiente
    cp .env.example .env

    # Gerar a chave da aplicação
    php artisan key:generate

    # Executar as migrações para criar as tabelas da base de dados
    php artisan migrate
    ```

---

## ▶️ Executando a Aplicação

Para que a aplicação funcione completamente (API, processamento de filas e tempo real), é necessário ter **3 processos rodando em 3 terminais separados** dentro do VS Code.

* **Terminal 1 - Servidor Web:**
    ```bash
    php artisan serve
    ```

* **Terminal 2 - Worker da Fila:**
    ```bash
    php artisan queue:work
    ```

* **Terminal 3 - Servidor Reverb:**
    ```bash
    php artisan reverb:start
    ```

---

## 📡 Endpoints da API

A URL base da API é: `http://127.0.0.1:8000/api`

| Método | Rota                     | Descrição                                 | Corpo da Requisição (JSON)                      | Resposta de Sucesso (2xx)                            |
| :----- | :----------------------- | :---------------------------------------- | :---------------------------------------------- | :--------------------------------------------------- |
| `GET`  | `/contacts`              | Lista todos os contatos (paginado).       | N/A                                             | Lista de contatos com estrutura de paginação.        |
| `POST` | `/contacts`              | Cria um novo contato.                     | `{ "name": "...", "email": "...", "phone": "..." }` | `201 Created` com os dados do contato criado.        |
| `GET`  | `/contacts/{id}`         | Exibe um contato específico.              | N/A                                             | Dados do contato solicitado.                         |
| `PUT`  | `/contacts/{id}`         | Atualiza um contato existente.            | `{ "name": "...", "phone": "..." }` (campos opcionais) | Dados do contato atualizado.                         |
| `DELETE`| `/contacts/{id}`         | Apaga um contato.                         | N/A                                             | `204 No Content`.                                    |

---

## ✨ Fluxo Assíncrono e em Tempo Real

A arquitetura do projeto foi desenhada para ser desacoplada e escalável.

1.  **Ação:** Quando um contato é criado ou atualizado através da API (`POST` ou `PUT`).
2.  **Observer (`ContactObserver`):** Deteta a ação de `saved` (salvo) no modelo `Contact`.
3.  **Evento (`ContactSaved`):** O observer dispara este evento.
4.  **Listener (`ProcessContactScore`):** Este listener "ouve" pelo evento `ContactSaved` e a sua única responsabilidade é despachar um job para a fila.
5.  **Job (`UpdateContactScore`):** O job é colocado na fila do Redis. O *worker* (`queue:work`) pega neste job e executa a sua lógica: aguarda 5 segundos (simulando um processo demorado), atualiza a pontuação do contato e o campo `processed_at` no banco de dados.
6.  **Evento de Broadcast (`ScoreUpdated`):** Após o job terminar, ele dispara este segundo evento.
7.  **Reverb:** O evento `ScoreUpdated` implementa `ShouldBroadcast`, então o Laravel o envia para o servidor Reverb, que o transmite em tempo real para o canal privado `contacts.{id}`. Qualquer cliente frontend conectado a este canal receberia a nova pontuação instantaneamente.

```
