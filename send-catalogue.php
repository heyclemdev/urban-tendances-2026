<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configuration
$to_email = 'contact@sr-plus.fr, a_n@sr-plus.fr';

// URL publique du catalogue
$catalogue_url = 'https://urban-tendances.fr/assets/catalogue-urban-tendances-2025.pdf';

// Récupère l'email
$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);
$user_email = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : false;

if (!$user_email) {
    echo json_encode(['success' => false, 'message' => 'Email invalide']);
    exit;
}

// Message HTML avec bouton de téléchargement
$html_message = "
<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <style>
    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
    .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
    .header { background: #dfaf4f; color: white; padding: 30px; text-align: center; }
    .header h1 { margin: 0; font-size: 28px; }
    .content { padding: 30px; background: #f9f9f9; }
    .content p { margin: 15px 0; font-size: 16px; }
    .download-box { background: white; border: 2px solid #dfaf4f; border-radius: 10px; padding: 25px; margin: 25px 0; text-align: center; }
    .download-box h2 { color: #dfaf4f; margin: 0 0 15px 0; }
    .button { display: inline-block; padding: 18px 40px; background: #dfaf4f; color: white !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 18px; margin: 10px 0; }
    .button:hover { background: #c99a3d; }
    .info-box { background: #fff; border-left: 4px solid #dfaf4f; padding: 15px; margin: 20px 0; }
    .footer { text-align: center; padding: 20px; font-size: 13px; color: #666; background: #f0f0f0; }
    .footer a { color: #dfaf4f; text-decoration: none; }
  </style>
</head>
<body>
  <div class='container'>
    <div class='header'>
      <h1>✨ Votre catalogue est prêt !</h1>
    </div>
    
    <div class='content'>
      <p>Bonjour,</p>
      <p>Merci pour votre intérêt pour notre gamme de mobilier urbain 100% français.</p>
      
      <div class='download-box'>
        <h2>📥 Téléchargez le catalogue 2025</h2>
        <p style='color: #666; margin: 10px 0;'>Cliquez sur le bouton ci-dessous pour télécharger notre catalogue complet</p>
        <a href='{$catalogue_url}' class='button' download>Télécharger le catalogue PDF</a>
        <p style='font-size: 14px; color: #999; margin-top: 15px;'>Le téléchargement démarre automatiquement</p>
      </div>
      
      
      <p>Notre équipe reste à votre disposition pour toute question, demande de devis ou projet sur-mesure.</p>
      
      <p style='text-align: center; margin: 30px 0;'>
        <a href='https://urban-tendances.fr/contact.html' style='color: #dfaf4f; font-weight: bold; text-decoration: none;'>
          💬 Nous contacter pour un devis gratuit
        </a>
      </p>
      
      <p>À très bientôt,<br><strong>L'équipe Urban Tendances</strong></p>
    </div>
    
    <div class='footer'>
      <p><strong>Urban Tendances</strong> - Mobilier urbain de qualité</p>
      <p>📍 92 impasse Lavoisier, 83260 La Crau</p>
      <p>📞 04 94 21 10 42 | 📧 <a href='mailto:contact@sr-plus.fr'>contact@sr-plus.fr</a></p>
      <p><a href='https://urban-tendances.fr'>Visiter notre site</a></p>
    </div>
  </div>
</body>
</html>
";

// Headers email simple (sans pièce jointe)
$headers = "From: Urban Tendances <contact@urban-tendances.fr>\r\n";
$headers .= "Reply-To: contact@urban-tendances.fr\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Envoi de l'email à l'utilisateur
$subject = '✨ Votre catalogue Urban Tendances 2025';
$email_sent = mail($user_email, $subject, $html_message, $headers);

// Notification à la cliente
$notif_subject = '🎯 Nouvelle demande de catalogue';
$notif_message = "
<html>
<body style='font-family: Arial, sans-serif;'>
  <h2 style='color: #dfaf4f;'>Nouvelle demande de catalogue</h2>
  <p><strong>Email :</strong> {$user_email}</p>
  <p><strong>Date :</strong> " . date('d/m/Y à H:i:s') . "</p>
  <p><em>L'utilisateur a reçu le lien de téléchargement par email.</em></p>
</body>
</html>
";

$notif_headers = "MIME-Version: 1.0\r\n";
$notif_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$notif_headers .= "From: Site Web <noreply@urban-tendances.fr>\r\n";

mail($to_email, $notif_subject, $notif_message, $notif_headers);

// Sauvegarde le lead
$log_file = 'leads-catalogue.csv';
if (!file_exists($log_file)) {
    file_put_contents($log_file, "Date,Email\n");
}
$log_data = date('Y-m-d H:i:s') . ',' . $user_email . "\n";
file_put_contents($log_file, $log_data, FILE_APPEND);

// Log succès
file_put_contents('debug-catalogue.log', 
    date('Y-m-d H:i:s') . " - Email avec lien envoyé à {$user_email}\n", 
    FILE_APPEND
);

// Réponse JSON
echo json_encode([
    'success' => true, 
    'message' => 'Email envoyé avec succès'
]);
?>