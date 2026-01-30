<?php

declare(strict_types=1);

/**
 * Email Service using PHPMailer
 * Sends emails using SMTP configuration from .env
 */

class EmailService
{
    private string $smtpHost;
    private int $smtpPort;
    private string $smtpEncryption;
    private string $smtpUsername;
    private string $smtpPassword;
    private string $fromAddress;
    private string $fromName;

    public function __construct()
    {
        // Load environment variables
        $appRoot = dirname(__DIR__, 2);
        if (is_file($appRoot . '/bootstrap/app.php')) {
            require_once $appRoot . '/bootstrap/app.php';
        }

        // Load .env manually if needed
        if (!function_exists('env')) {
            require_once $appRoot . '/config/database.php';
        }

        $this->smtpHost = env('SMTP_HOST', 'smtp.gmail.com');
        $this->smtpPort = (int) env('SMTP_PORT', '587');
        $this->smtpEncryption = env('SMTP_ENCRYPTION', 'tls');
        $this->smtpUsername = env('SMTP_USERNAME', '');
        $this->smtpPassword = env('SMTP_PASSWORD', '');
        $this->fromAddress = env('MAIL_FROM_ADDRESS', '');
        $this->fromName = env('MAIL_FROM_NAME', 'Golden Z-5 HR System');
    }

    /**
     * Send email using PHPMailer
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $body Email body (HTML)
     * @param string|null $altBody Plain text alternative
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendEmail(string $to, string $subject, string $body, ?string $altBody = null): array
    {
        try {
            // Try to autoload PHPMailer via Composer first
            // In Docker: /var/www/html is mapped to src/, so vendor should be at /var/www/html/vendor
            $appRoot = dirname(__DIR__, 2); // This is src/ in local, /var/www/html in Docker
            $possibleAutoloadPaths = [
                $appRoot . '/vendor/autoload.php',  // Standard: vendor in src root
                dirname($appRoot) . '/vendor/autoload.php',  // Alternative: vendor in parent directory
                __DIR__ . '/../../../vendor/autoload.php',  // Relative from EmailService.php
            ];
            
            $vendorAutoload = null;
            foreach ($possibleAutoloadPaths as $path) {
                if (file_exists($path)) {
                    $vendorAutoload = $path;
                    require_once $vendorAutoload;
                    break;
                }
            }
            
            // Check if PHPMailer class exists (via autoload or manual require)
            if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
                // Try to find PHPMailer manually
                $possibleVendorPaths = [
                    $appRoot . '/vendor',
                    dirname($appRoot) . '/vendor',
                    __DIR__ . '/../../../vendor',
                ];
                
                $phpmailerPath = null;
                foreach ($possibleVendorPaths as $vendorPath) {
                    $testPath = $vendorPath . '/phpmailer/phpmailer/src/PHPMailer.php';
                    if (file_exists($testPath)) {
                        $phpmailerPath = $testPath;
                        break;
                    }
                }

                if (!$phpmailerPath || !file_exists($phpmailerPath)) {
                    return [
                        'success' => false,
                        'message' => 'PHPMailer not found. Please install it via composer: composer require phpmailer/phpmailer'
                    ];
                }

                // Load PHPMailer manually
                require_once $phpmailerPath;
                require_once dirname($phpmailerPath) . '/SMTP.php';
                require_once dirname($phpmailerPath) . '/Exception.php';
            }

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = $this->smtpEncryption;
            $mail->Port = $this->smtpPort;
            $mail->CharSet = 'UTF-8';

            // Recipients
            $mail->setFrom($this->fromAddress, $this->fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            if ($altBody !== null) {
                $mail->AltBody = $altBody;
            }

            $mail->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            error_log('EmailService Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send welcome email with auto-generated password
     *
     * @param string $email User email
     * @param string $username Username
     * @param string $name User full name
     * @param string $password Auto-generated password
     * @param string $role User role
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendWelcomeEmail(string $email, string $username, string $name, string $password, string $role): array
    {
        $subject = 'Welcome to Golden Z-5 HR System - Your Account Credentials';
        
        $body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%); color: #fff; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .credentials { background: #fff; padding: 20px; border-left: 4px solid #d4af37; margin: 20px 0; }
                .password-box { background: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 16px; font-weight: bold; text-align: center; margin: 15px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
                .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Welcome to Golden Z-5 HR System</h1>
                </div>
                <div class="content">
                    <p>Dear ' . htmlspecialchars($name) . ',</p>
                    
                    <p>Your account has been created successfully. Below are your login credentials:</p>
                    
                    <div class="credentials">
                        <p><strong>Username:</strong> ' . htmlspecialchars($username) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                        <p><strong>Role:</strong> ' . htmlspecialchars(ucfirst(str_replace('_', ' ', $role))) . '</p>
                        <p><strong>Your temporary password:</strong></p>
                        <div class="password-box">' . htmlspecialchars($password) . '</div>
                    </div>
                    
                    <div class="warning">
                        <p><strong>⚠️ Important Security Notice:</strong></p>
                        <p>For your security, please change this password immediately after your first login.</p>
                    </div>
                    
                    <p>You can access the system by visiting the login page and using the credentials above.</p>
                    
                    <p>If you have any questions or need assistance, please contact your system administrator.</p>
                    
                    <p>Best regards,<br>
                    <strong>Golden Z-5 HR System Administration</strong></p>
                </div>
                <div class="footer">
                    <p>This is an automated message. Please do not reply to this email.</p>
                    <p>&copy; ' . date('Y') . ' Golden Z-5 Security and Investigation Agency, Inc.</p>
                </div>
            </div>
        </body>
        </html>';

        $altBody = "Welcome to Golden Z-5 HR System\n\n" .
                   "Dear $name,\n\n" .
                   "Your account has been created successfully.\n\n" .
                   "Username: $username\n" .
                   "Email: $email\n" .
                   "Role: " . ucfirst(str_replace('_', ' ', $role)) . "\n" .
                   "Your temporary password: $password\n\n" .
                   "IMPORTANT: Please change this password immediately after your first login.\n\n" .
                   "Best regards,\nGolden Z-5 HR System Administration";

        return $this->sendEmail($email, $subject, $body, $altBody);
    }
}
