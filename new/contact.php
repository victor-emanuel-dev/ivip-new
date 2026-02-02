<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configurações de email
$destinatario = "v.emanuel.pacheco@gmail.com"; // Altere para o email da sua empresa
$assunto = "Novo contato do site iVip";

// Verifica se é uma requisição POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recebe os dados JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validação básica
    if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, preencha todos os campos.'
        ]);
        exit;
    }
    
    // Sanitiza os dados
    $nome = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $mensagem = filter_var($data['message'], FILTER_SANITIZE_STRING);
    
    // Valida o email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Email inválido.'
        ]);
        exit;
    }
    
    // Monta o corpo do email em HTML
    $corpo_email = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f4f4f4;
            }
            .header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px;
                text-align: center;
                border-radius: 5px 5px 0 0;
            }
            .content {
                background-color: white;
                padding: 20px;
                border-radius: 0 0 5px 5px;
            }
            .info-row {
                margin: 15px 0;
                padding: 10px;
                background-color: #f9f9f9;
                border-left: 4px solid #667eea;
            }
            .label {
                font-weight: bold;
                color: #667eea;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                font-size: 12px;
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <img src='https://ivip.dev/new/assets/img/ivip-black-p.png' alt='iVip' style='height: 40px; margin: 0 auto 10px; display: block; filter: brightness(0) invert(1);'>
                <h2>Nova Mensagem de Contato - iVip</h2>
            </div>
            <div class='content'>
                <div class='info-row'>
                    <span class='label'>Nome:</span><br>
                    " . htmlspecialchars($nome) . "
                </div>
                <div class='info-row'>
                    <span class='label'>Email:</span><br>
                    " . htmlspecialchars($email) . "
                </div>
                <div class='info-row'>
                    <span class='label'>Mensagem:</span><br>
                    " . nl2br(htmlspecialchars($mensagem)) . "
                </div>
                <div class='info-row'>
                    <span class='label'>Data/Hora:</span><br>
                    " . date('d/m/Y H:i:s') . "
                </div>
            </div>
            <div class='footer'>
                <p>Esta mensagem foi enviada através do formulário de contato do site iVip.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Headers do email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Site iVip <noreply@ivip.com>" . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Tenta enviar o email
    if (mail($destinatario, $assunto, $corpo_email, $headers)) {
        // Envia email de confirmação para o usuário
        $assunto_confirmacao = "Recebemos sua mensagem - iVip";
        $corpo_confirmacao = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 30px;
                    text-align: center;
                    border-radius: 5px;
                }
                .content {
                    background-color: #f9f9f9;
                    padding: 30px;
                    margin-top: 20px;
                    border-radius: 5px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='https://ivip.dev/new/assets/img/ivip-black-p.png' alt='iVip' style='height: 50px; margin: 0 auto 15px; display: block; filter: brightness(0) invert(1);'>
                    <p>Obrigado por entrar em contato!</p>
                </div>
                <div class='content'>
                    <p>Olá <strong>" . htmlspecialchars($nome) . "</strong>,</p>
                    <p>Recebemos sua mensagem e retornaremos o mais breve possível.</p>
                    <p>Nossa equipe está analisando sua solicitação e em breve você terá uma resposta.</p>
                    <p><strong>Sua mensagem:</strong></p>
                    <p style='background-color: white; padding: 15px; border-left: 4px solid #667eea;'>
                        " . nl2br(htmlspecialchars($mensagem)) . "
                    </p>
                    <p>Atenciosamente,<br><strong>Equipe iVip</strong></p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $headers_confirmacao = "MIME-Version: 1.0" . "\r\n";
        $headers_confirmacao .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers_confirmacao .= "From: iVip <contato@ivip.com>" . "\r\n";
        
        mail($email, $assunto_confirmacao, $corpo_confirmacao, $headers_confirmacao);
        
        // Resposta de sucesso
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
        ]);
    } else {
        // Erro ao enviar
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao enviar mensagem. Por favor, tente novamente mais tarde.'
        ]);
    }
    
} else {
    // Método não permitido
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido.'
    ]);
}
?>
