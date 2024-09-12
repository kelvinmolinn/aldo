<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public $fromEmail = 'aldo.games@textilesbym.com';  // Tu correo
    public $fromName  = 'Aldo Games Store';
    public $recipients = '';

    // Configuración SMTP
    public $protocol = 'smtp';
    public $SMTPHost = 'mail.textilesbym.com';  // Servidor SMTP
    public $SMTPPort = 465;  // Puerto SSL para SMTP
    public $SMTPUser = 'aldo.games@textilesbym.com';  // Tu usuario (correo)
    public $SMTPPass = '#Aldo2024$';  // Tu contraseña de Gmail o contraseña de aplicación
    public $SMTPCrypto = 'ssl';  // SSL para cifrado

    public $mailType = 'html';  // O 'text' si prefieres texto plano
    public $charset  = 'utf-8';
    public $newline  = "\r\n";
    public $wordWrap = true;
}
