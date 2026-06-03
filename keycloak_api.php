<?php
//session_destroy();
use Firebase\JWT\JWT;

session_start();

$docRoot = realpath($_SERVER["DOCUMENT_ROOT"]);

$ini_array = parse_ini_file("keycloak_properties.ini", true);
$keycloak_properties = $ini_array['keycloak_properties'];
$oauth_properties = $ini_array['oauth_framework_properties'];


require "$docRoot/".$oauth_properties['composerAutoloadLocation'];        

function checkKeycloakAuth() {
    $ini_array = parse_ini_file("keycloak_properties.ini", true);
    $keycloak_properties = $ini_array['keycloak_properties'];
    $oauth_properties = $ini_array['oauth_framework_properties'];

    $provider = new Stevenmaguire\OAuth2\Client\Provider\Keycloak([
        'authServerUrl'             => $keycloak_properties['authServerUrl'],
        'realm'                     => $keycloak_properties['realm'],
        'clientId'                  => $keycloak_properties['clientId'],
        'clientSecret'              => $keycloak_properties['clientSecret'],
        'redirectUri'               => $keycloak_properties['redirectUri'],
        'encryptionAlgorithm'       => $keycloak_properties['encryptionAlgorithm'],
        'encryptionKey'             => $keycloak_properties['encryptionKey']
        // 'encryptionKeyPath'         => $keycloak_properties['encryptionKeyPath']
    ]);

    // Verificar se a requisição é Ajax
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    if (!isset($_GET['code']) && !isset($_SESSION['code'])) {
        if ($isAjax) {
            // Retornar erro 401 para requisição Ajax, sem redirecionar
            http_response_code(401);
            echo json_encode(['error' => 'Usuário não autenticado via Keycloak.']);
            exit;
        } else {
            // Requisição normal: Redirecionar para o login
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: '.$authUrl);
            exit;
        }
    } elseif (isset($_GET['state']) && ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);

    // Redireciona de volta ao início do fluxo de login
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;
} else {
        $_SESSION['code'] = 'OK';
        try {
            if (!isset($_SESSION['token'])) {

                $aux_token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
                $_SESSION['token'] =  serialize($aux_token);
                $_SESSION['refresh'] = $aux_token->getRefreshToken();


            }
        } catch (Exception $e) {
            exit('Failed to get access token: '.$e->getMessage());
        }

        try {
            $user = $provider->getResourceOwner(unserialize($_SESSION['token']));
            $userArray = $user->toArray();
            $_SESSION['logout_url'] = 'https://www.intranet.labjor.unicamp.br';
        } catch (Exception $e) {
            unset($_SESSION['token'], $_SESSION['code'], $_SESSION['oauth2state']);
            session_destroy();
            if ($isAjax) {
                // Retornar erro para Ajax
                http_response_code(401);
                echo json_encode(['error' => 'Erro ao obter token do usuário via Keycloak.']);
                exit;
            } else {
                // Redirecionamento para requisição normal
                header("Location: " . $keycloak_properties['redirectUri']);
                exit('Failed to get resource owner: ' . $e->getMessage());
            }
        }
    }
}
function getKeycloakRoles($clientID)
{
     $tks = explode('.', unserialize($_SESSION['token'])->getToken());
     if (count($tks) != 3) {
        throw new UnexpectedValueException('Wrong number of segments');
     }
     
     $token_payload =  (array)JWT::jsonDecode(JWT::urlsafeB64Decode($tks[1]));
     
     if(! is_null($clientID))
     {
          return (array)$token_payload['resource_access'][$clientID];
     }
     else
     {
          return (array)$token_payload['resource_access'];
     }                                    
}


function getUserData() {
    $ini_array = parse_ini_file("keycloak_properties.ini", true);
    $keycloak_properties = $ini_array['keycloak_properties'];

    $provider = new Stevenmaguire\OAuth2\Client\Provider\Keycloak([
        'authServerUrl'       => $keycloak_properties['authServerUrl'],
        'realm'               => $keycloak_properties['realm'],
        'clientId'            => $keycloak_properties['clientId'],
        'clientSecret'        => $keycloak_properties['clientSecret'],
        'redirectUri'         => $keycloak_properties['redirectUri'],
        'encryptionAlgorithm' => $keycloak_properties['encryptionAlgorithm'],
        'encryptionKey'       => $keycloak_properties['encryptionKey']
        // 'encryptionKeyPath'   => $keycloak_properties['encryptionKeyPath']
    ]);

    // Obtém o token da sessão e o desserializa
    $token = unserialize($_SESSION['token']);

    if ($token instanceof \League\OAuth2\Client\Token\AccessToken) {
        // Obtém informações do usuário
        $user = $provider->getResourceOwner($token);
        $userArray = $user->toArray();

        // Adiciona o tempo de expiração
        $userArray['token_expires_at'] = $token->getExpires(); // Timestamp de expiração
        $userArray['token_expires_in_seconds'] = $token->getExpires() - time(); // Tempo restante em segundos

        return $userArray;
    } else {
        throw new Exception("Token inválido ou sessão expirada.");
    }
}



function refreshTokenIfNeeded() {
    $ini_array = parse_ini_file("keycloak_properties.ini", true);
    $keycloak_properties = $ini_array['keycloak_properties'];

    $provider = new Stevenmaguire\OAuth2\Client\Provider\Keycloak([
        'authServerUrl'       => $keycloak_properties['authServerUrl'],
        'realm'               => $keycloak_properties['realm'],
        'clientId'            => $keycloak_properties['clientId'],
        'clientSecret'        => $keycloak_properties['clientSecret'],
        'redirectUri'         => $keycloak_properties['redirectUri'],
        'encryptionAlgorithm' => $keycloak_properties['encryptionAlgorithm'],
        'encryptionKey'       => $keycloak_properties['encryptionKey']
    ]);

    $token = unserialize($_SESSION['token']);
    $refresh = $_SESSION['refresh'];
    
    // Se o token estiver para expirar nos próximos 120 segundos
    if ($token->getExpires() - time() < 840) {
        try {
            $newToken = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $refresh,
            ]);

            $_SESSION['token'] = serialize($newToken);
            $_SESSION['refresh'] = $newToken->getRefreshToken();
            return true;
        } catch (Exception $e) {
            // Caso o refresh falhe, destrói a sessão e força novo login
            unset($_SESSION['token'], $_SESSION['refresh'], $_SESSION['code']);
            session_destroy();
            header("Location: " . $keycloak_properties['redirectUri']);
            exit('Erro ao renovar token: ' . $e->getMessage());
        }
    }

    return false; // Não precisou renovar
}

?>
