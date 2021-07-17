<?php
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoloader.php';

$configs = require('config.php');

$collection = new App\ProviderCollection($configs);

$providers = $collection->getProvider('facebook');

var_dump($providers->getAuthLink());die;

/**
 * "client_id":"client_6070546c6aba63.16480463"
 * "client_secret":"38201ad253c323a79d9108f4588bbc62d2e1a5c6"
 */


function getUser($params)
{
    $result = file_get_contents("http://oauth-server:8081/token?"
        . "client_id=" . CLIENT_ID
        . "&client_secret=" . CLIENT_SECRET
        . "&" . http_build_query($params));
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("http://oauth-server:8081/me", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
}

function handleLogin()
{
    echo '<h1>Login with Auth-Code</h1>';
    echo "<a href='http://localhost:8081/auth?"
        . "response_type=code"
        . "&client_id=" . CLIENT_ID
        . "&scope=basic&state=dsdsfsfds'>Login with oauth-server</a>";
    echo "<a href='https://www.facebook.com/v2.10/dialog/oauth?"
        . "response_type=code"
        . "&client_id=" . CLIENT_FBID
        . "&scope=email&state=dsdsfsfds&redirect_uri=https://localhost/fbauth-success'>Login with Facebook</a>";
    echo "<a href='https://github.com/login/oauth/authorize?"
        . "client_id=" . CLIENT_GITHUBID
        . "&scope=user&state=dsdsfsfds&redirect_uri=https://localhost/githubauth-success'>Login with GitHub</a>";
    echo "<a href='https://accounts.google.com/o/oauth2/v2/auth?"
        . "scope=email"
        . "&access_type=online"
        . "&redirect_uri=" . urlencode('https://localhost/googleauth-success')
        . "&client_id=" . CLIENT_GOOGLEID
        . "&response_type=code'>Login with Google</a>";
}

function handleSuccess()
{
    ["code" => $code, "state" => $state] = $_GET;
    // ECHANGE CODE => TOKEN
    getUser([
        "grant_type" => "authorization_code",
        "code" => $code
    ]);
}

function handleFBSuccess()
{
    ["code" => $code, "state" => $state] = $_GET;
    // ECHANGE CODE => TOKEN
    $result = file_get_contents("https://graph.facebook.com/oauth/access_token?"
        . "client_id=" . CLIENT_FBID
        . "&client_secret=" . CLIENT_FBSECRET
        . "&redirect_uri=https://localhost/fbauth-success"
        . "&grant_type=authorization_code&code={$code}");
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("https://graph.facebook.com/me?fields=id,name,email", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
}

function handleGitHubSuccess()
{
    ["code" => $code] = $_GET;
    // ECHANGE CODE => TOKEN
    $contextEchangeCode = stream_context_create([
        'http'=> [
            'method' => "GET",
            'header' => "Accept: application/json"
        ]
    ]);
    $result = file_get_contents("https://github.com/login/oauth/access_token?"
        . "client_id=" . CLIENT_GITHUBID
        . "&client_secret=" . CLIENT_GITHUBSECRET
        . "&code={$code}"
        . "&redirect_uri=https://localhost/githubauth-success", false, $contextEchangeCode);
    $token = json_decode($result, true)["access_token"];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.github.com/user',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: token '. $token,
            'User-Agent: PHP'
        ),
    ));

    $result = curl_exec($curl);

    curl_close($curl);
    $user = json_decode($result, true);
    var_dump($user);
}

function handleGoogleSuccess()
{
    ["code" => $code] = $_GET;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://oauth2.googleapis.com/token?'
            . "client_id=" . CLIENT_GOOGLEID
            . "&client_secret=" . CLIENT_GOOGLESECRET
            . "&code=" . $code
            . "&redirect_uri=https://localhost/googleauth-success"
            . "&grant_type=authorization_code",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Host: oauth2.googleapis.com',
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: 0'
        ),
    ));

    $result = curl_exec($curl);

    curl_close($curl);

    $token = json_decode($result, true)["access_token"];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://openidconnect.googleapis.com/v1/userinfo',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '. $token
        )
    ));

    $result = curl_exec($curl);

    curl_close($curl);

    $user = json_decode($result, true);
    var_dump($result);
}

function handleError()
{
    echo "refusé";
}

/**
 * AUTH_CODE WORKFLOW
 * => GET Code <- Générer le lien /auth (login)
 * => EXCHANGE Code <> Token (auth-success)
 * => GET USER by Token (auth-success)
 */
$route = strtok($_SERVER["REQUEST_URI"], '?');
switch ($route) {
    case '/login':
        handleLogin();
        break;
    case '/auth-success':
        handleSuccess();
        break;
    case '/fbauth-success':
        handleFBSuccess();
        break;
    case '/githubauth-success':
        handleGitHubSuccess();
        break;
    case '/googleauth-success':
        handleGoogleSuccess();
        break;
    case '/auth-error':
        handleError();
        break;
    case '/password':
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            echo "<form method='POST'>";
            echo "<input name='username'>";
            echo "<input name='password'>";
            echo "<input type='submit' value='Log with oauth'>";
            echo "</form>";
        } else {
            ['username' => $username, 'password' => $password] = $_POST;
            getUser([
                'grant_type' => "password",
                'username' => $username,
                'password' => $password
            ]);
        }
        break;
    default:
        http_response_code(404);
}
/**
 *@Todo Christian
 */

function getAllProviders()
{
    $redirect_uri = 'https://localhost/auth';
    return [
        'facebook' => [
            'link_label' => 'Login with Facebook',
            'instance' => new Facebook(FB_CLIENT_ID, FB_SECRET, "${redirect_uri}?provider=facebook")
        ],
        'app' => [
            'link_label' => 'Login with App',
            'instance' => new App(APP_CLIENT_ID, APP_SECRET, "${redirect_uri}?provider=app", ['scope' => 'userinfo', 'state' => 'state_example'])
        ],
        'github' => [
            'link_label' => 'Login with Github',
            'instance' => new Github(GITHUB_CLIENT_ID, GITHUB_SECRET, "${redirect_uri}?provider=github", [], GITHUB_APP)
        ],
        'google' => [
            'link_label' => 'Login with Google',
            'instance' => new Google(GOOGLE_CLIENT_ID, GOOGLE_SECRET, "${redirect_uri}?provider=google", ['scope' => 'https://www.googleapis.com/auth/userinfo.profile'])
        ],
    ];
}

$response = new App\Response();

$route = strtok($_SERVER["REQUEST_URI"], '?');
switch ($route) {
    case '/':
        displayHome($providers);
        break;
    case '/auth':
        if (!$provider = $providers[$_GET['provider']]['instance']) die("Une erreur est survenue : le provider {$_GET['provider']} n'est pas reconnu");
        handleResponse($provider, $_GET);
        break;
    default:
        http_response_code(404);
}
