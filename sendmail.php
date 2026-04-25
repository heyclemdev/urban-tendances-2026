<?php
// Récupérer le token reCAPTCHA
$recaptcha_token = $_POST['recaptcha_token'];
$secret_key = '6LdBsRYsAAAAAD2FpsqOQHlrODvzWxHwhZmlDubW'; // ← Ta clé secrète

// Vérifier avec Google
$verify_url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => $secret_key,
    'response' => $recaptcha_token
);

$options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);
$verify_response = file_get_contents($verify_url, false, $context);
$response_data = json_decode($verify_response);

// Si le score est trop bas, on rejette
if ($response_data->score < 0.3) {  // Seuil plus permissif
    echo "error_bot_score:" . $response_data->score;
    exit;
}

// Sinon, on envoie l'email normalement
$nom = $_POST["nom"];
$prenom = $_POST["prenom"];
$email = $_POST["email"];
$telephone = $_POST["telephone"];
$projet = $_POST["projet"];
$ville = $_POST["ville"];
$typeEntreprise = $_POST["type-entreprise"];
$message = $_POST["message"];

$to = "contact@sr-plus.fr";
$subject = "Nouveau message depuis le site Urban Tendances";

$body = "Nom: $nom\nPrénom: $prenom\nEmail: $email\nTéléphone: $telephone\nProjet: $projet\nVille: $ville\nProfil: $typeEntreprise\n\nMessage:\n$message\n\n---\nScore reCAPTCHA: " . $response_data->score;

$headers = "From: contact@urban-tendances.fr";

mail($to, $subject, $body, $headers);

echo "success";
?>