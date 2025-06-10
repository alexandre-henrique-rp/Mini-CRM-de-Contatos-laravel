FROM mcr.microsoft.com/devcontainers/php:1-8.2-bookworm

# Instalar dependências básicas como curl e gnupg (necessárias para o Node.js)
RUN apt-get update && apt-get install -y curl gnupg

# Instalar MariaDB client
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y mariadb-client \ 
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# Instalar driver php-mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar extensão do PHP para Redis
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# --- NOVO: Instalar Node.js e NPM ---
# Adicionar o repositório NodeSource para a versão LTS (Long Term Support)
RUN curl -sL https://deb.nodesource.com/setup_lts.x | bash -
# Instalar o Node.js (que inclui o npm)
RUN apt-get install -y nodejs


# [Optional] Uncomment this section to install additional OS packages.
# RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
#     && apt-get -y install --no-install-recommends <your-package-list-here>

# [Optional] Uncomment this line to install global node packages.
# RUN su vscode -c "source /usr/local/share/nvm/nvm.sh && npm install -g <your-package-here>" 2>&1

