# Site IVIP - Instruções de Instalação

## 📋 Arquivos do Projeto

- `ivip-site.html` - Página principal do site
- `contact.php` - Script PHP para processar o formulário de contato
- `README.md` - Este arquivo de instruções

## 🚀 Como Instalar

### Opção 1: Servidor Web com PHP (Recomendado)

1. **Requisitos:**
   - Servidor web (Apache, Nginx, etc.)
   - PHP 7.0 ou superior
   - Função `mail()` do PHP configurada

2. **Instalação:**

   ```bash
   # Copie os arquivos para o diretório do seu servidor web
   # Exemplo no Apache (Linux):
   sudo cp ivip-site.html /var/www/html/index.html
   sudo cp contact.php /var/www/html/contact.php

   # Exemplo no XAMPP (Windows):
   # Copie para C:\xampp\htdocs\
   ```

3. **Configuração do email:**
   - Abra o arquivo `contact.php`
   - Altere a linha 7 com seu email:

   ```php
   $destinatario = "seu-email@ivip.com";
   ```

4. **Acesse no navegador:**
   ```
   http://localhost/ivip-site.html
   ```

### Opção 2: Servidor Local PHP (Desenvolvimento)

```bash
# No terminal, navegue até a pasta dos arquivos
cd /caminho/para/os/arquivos

# Inicie o servidor PHP embutido
php -S localhost:8000

# Acesse no navegador:
# http://localhost:8000/ivip-site.html
```

### Opção 3: Hospedagem Online

1. **Faça upload dos arquivos** para seu servidor de hospedagem via FTP/SFTP
2. **Configure o email** no arquivo `contact.php`
3. **Teste o formulário** enviando uma mensagem

## ⚙️ Configuração do PHP Mail

### Linux (Postfix)

```bash
# Instale o Postfix
sudo apt-get update
sudo apt-get install postfix

# Configure o PHP
sudo nano /etc/php/7.4/apache2/php.ini

# Encontre e configure:
sendmail_path = /usr/sbin/sendmail -t -i

# Reinicie o Apache
sudo systemctl restart apache2
```

### Windows (XAMPP)

1. Abra o arquivo `php.ini` (geralmente em `C:\xampp\php\php.ini`)
2. Configure o SMTP:

```ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_from = seu-email@gmail.com
sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
```

3. Configure o `sendmail.ini` (em `C:\xampp\sendmail\sendmail.ini`):

```ini
smtp_server=smtp.gmail.com
smtp_port=587
auth_username=seu-email@gmail.com
auth_password=sua-senha-de-app
force_sender=seu-email@gmail.com
```

**Nota:** Para Gmail, você precisa criar uma "Senha de App" nas configurações de segurança.

## 🔧 Alternativas ao PHP Mail

Se a função `mail()` não funcionar, você pode usar:

### PHPMailer (Recomendado)

```bash
# Instale via Composer
composer require phpmailer/phpmailer
```

### SMTP Externo

Serviços como:

- SendGrid
- Mailgun
- Amazon SES
- SMTP2GO

## 🧪 Testando o Formulário

1. Abra o site no navegador
2. Vá até a seção "Entre em Contato"
3. Preencha o formulário
4. Clique em "Enviar Mensagem"
5. Verifique se recebeu o email

## 📝 Personalização

### Alterar Email de Destino

No arquivo `contact.php`, linha 7:

```php
$destinatario = "contato@ivip.com"; // Seu email aqui
```

### Alterar Informações de Contato

No arquivo `ivip-site.html`, procure pela seção de contato e altere:

- Email exibido
- Telefone
- Endereço

### Alterar Cores e Design

As cores principais estão definidas com as classes do Tailwind:

- `bg-purple-600` - Cor principal (roxo)
- `text-purple-600` - Texto roxo
- Para mudar, substitua `purple` por outra cor: `blue`, `green`, `red`, etc.

## 🐛 Solução de Problemas

### Formulário não envia

1. Verifique se o PHP está instalado: `php -v`
2. Verifique se a função `mail()` está habilitada
3. Verifique os logs de erro do PHP
4. Teste o `contact.php` diretamente

### Email não chega

1. Verifique a pasta de SPAM
2. Configure corretamente o SMTP
3. Use serviços externos (PHPMailer + SMTP)
4. Verifique logs do servidor de email

### Erro CORS

Se estiver testando localmente e tiver problemas CORS:

- Use o servidor PHP embutido (`php -S localhost:8000`)
- Ou configure o CORS no servidor web

## 📧 Suporte

Para mais informações sobre configuração de email no PHP:

- [Documentação PHP Mail](https://www.php.net/manual/pt_BR/function.mail.php)
- [PHPMailer GitHub](https://github.com/PHPMailer/PHPMailer)

## 📄 Licença

Este projeto foi desenvolvido para a empresa IVIP.

---

Desenvolvido com ❤️ usando TypeScript, React e PHP
