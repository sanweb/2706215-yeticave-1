<?php

declare(strict_types=1);

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once BASE_PATH . '/vendor/autoload.php';

/**
 * Sends an email message.
 *
 * @param string $dsn SMTP connection DSN.
 * @param string $to Recipient email address.
 * @param string $from Sender email address.
 * @param string $subject Email subject.
 * @param string $body Email body.
 * @param string $content_type Email content type: text/plain or text/html.
 */
function send_mail(
    string $dsn,
    string $to,
    string $from,
    string $subject,
    string $body,
    string $content_type = 'text/plain'
): void {
    $transport = Transport::fromDsn($dsn);

    $email = new Email();
    $email->to($to);
    $email->from($from);
    $email->subject($subject);

    if ($content_type === 'text/html') {
        $email->html($body);
    } else {
        $email->text($body);
    }

    $mailer = new Mailer($transport);
    $mailer->send($email);
}

/**
 * Builds SMTP DSN from config.
 *
 * @param array{
 *     host: string,
 *     port: int,
 *     username: string,
 *     password: string,
 *     encryption?: string
 * } $smtp_config
 *
 * @return string
 */
function build_dsn(array $smtp_config): string
{
    $encryption = $smtp_config['encryption'] ?? 'tls';

    return sprintf(
        'smtp://%s:%s@%s:%d?encryption=%s',
        urlencode($smtp_config['username']),
        urlencode($smtp_config['password']),
        $smtp_config['host'],
        $smtp_config['port'],
        urlencode($encryption)
    );
}
