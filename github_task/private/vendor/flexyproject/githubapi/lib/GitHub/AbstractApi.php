<?php
namespace FlexyProject\GitHub;

use Exception;
use FlexyProject\Curl\Client as CurlClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractApi
 *
 * @package FlexyProject\GitHub
 */
abstract class AbstractApi
{

    /** API version */
    const API_VERSION = 'v3';

    /** API constants */
    const API_URL        = 'https://api.github.com';
    const API_UPLOADS    = 'https://uploads.github.com';
    const API_RAW_URL    = 'https://raw.github.com';
    const CONTENT_TYPE   = 'application/json';
    const DEFAULT_ACCEPT = 'application/vnd.github.' . self::API_VERSION . '+json';
    const USER_AGENT     = 'FlexyProject-GitHubAPI';

    /** Archive constants */
    const ARCHIVE_TARBALL = 'tarball';
    const ARCHIVE_ZIPBALL = 'zipball';

    /** Authentication constants */
    const OAUTH_AUTH             = 0;
    const OAUTH2_HEADER_AUTH     = 1;
    const OAUTH2_PARAMETERS_AUTH = 2;

    /** Branch constants */
    const BRANCH_MASTER  = 'master';
    const BRANCH_DEVELOP = 'develop';

    /** Direction constants */
    const DIRECTION_ASC  = 'asc';
    const DIRECTION_DESC = 'desc';

    /** Environment constants */
    const ENVIRONMENT_PRODUCTION = 'production';
    const ENVIRONMENT_STAGING    = 'staging';
    const ENVIRONMENT_QA         = 'qa';

    /** Events constants */
    const EVENTS_PULL         = 'pull';
    const EVENTS_PULL_REQUEST = 'pull_request';
    const EVENTS_PUSH         = 'push';

    /** Filter constants */
    const FILTER_ALL        = 'all';
    const FILTER_ASSIGNED   = 'assigned';
    const FILTER_CREATED    = 'created';
    const FILTER_MENTIONED  = 'mentioned';
    const FILTER_SUBSCRIBED = 'subscribed';

    /** Media types constants */
    const MEDIA_TYPE_JSON = 'json';
    const MEDIA_TYPE_RAW  = 'raw';
    const MEDIA_TYPE_FULL = 'full';
    const MEDIA_TYPE_TEXT = 'text';

    /** Modes constants */
    const MODE_MARKDOWN = 'markdown';
    const MODE_GFM      = 'gfm';

    /** Permissions constants */
    const PERMISSION_ADMIN = 'admin';
    const PERMISSION_PULL  = 'pull';
    const PERMISSION_PUSH  = 'push';

    /** Sort constants */
    const SORT_COMPLETENESS = 'completeness';
    const SORT_CREATED      = 'created';
    const SORT_DUE_DATE     = 'due_date';
    const SORT_FULL_NAME    = 'full_name';
    const SORT_NEWEST       = 'newest';
    const SORT_OLDEST       = 'oldest';
    const SORT_PUSHED       = 'pushed';
    const SORT_STARGAZERS   = 'stargazers';
    const SORT_UPDATED      = 'updated';

    /** State constants */
    const STATE_ACTIVE  = 'active';
    const STATE_ALL     = 'all';
    const STATE_CLOSED  = 'closed';
    const STATE_ERROR   = 'error';
    const STATE_FAILURE = 'failure';
    const STATE_OPEN    = 'open';
    const STATE_PENDING = 'pending';
    const STATE_SUCCESS = 'success';

    /** Task constants */
    const TASK_DEPLOY            = 'deploy';
    const TASK_DEPLOY_MIGRATIONS = 'deploy:migrations';

    /** Type constants */
    const TYPE_ALL        = 'all';
    const TYPE_COMMENTS   = 'comments';
    const TYPE_GISTS      = 'gists';
    const TYPE_HOOKS      = 'hooks';
    const TYPE_ISSUES     = 'issues';
    const TYPE_MEMBER     = 'member';
    const TYPE_MILESTONES = 'milestones';
    const TYPE_ORGS       = 'orgs';
    const TYPE_OWNER      = 'owner';
    const TYPE_PAGES      = 'pages';
    const TYPE_PUBLIC     = 'public';
    const TYPE_PULLS      = 'pulls';
    const TYPE_PRIVATE    = 'private';
    const TYPE_REPOS      = 'repos';
    const TYPE_USERS      = 'users';

    /** Properties */
    protected $accept         = self::DEFAULT_ACCEPT;
    protected $apiUrl         = self::API_URL;
    protected $authentication = self::OAUTH_AUTH;
    protected $clientId;
    protected $clientSecret;
    protected $contentType    = self::CONTENT_TYPE;
    protected $failure;
    protected $headers        = [];
    protected $httpAuth       = ['username' => '', 'password' => ''];
    protected $pagination;
    protected $request;
    protected $success;
    protected $timeout        = 240;
    protected $token          = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    /**
     * Get request
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get accept
     *
     * @return mixed
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Set accept
     *
     * @param array|string $accept
     *
     * @return AbstractApi
     */
    public function setAccept($accept): AbstractApi
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Get authentication
     *
     * @return int
     */
    public function getAuthentication(): int
    {
        return $this->authentication;
    }

    /**
     * Set authentication
     *
     * @param int $authentication
     *
     * @return AbstractApi
     */
    public function setAuthentication(int $authentication): AbstractApi
    {
        $this->authentication = $authentication;

        return $this;
    }

    /**
     * Get apiUrl
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * Set apiUrl
     *
     * @param mixed $apiUrl
     *
     * @return AbstractApi
     */
    public function setApiUrl($apiUrl): AbstractApi
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return null|int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set clientId
     *
     * @param mixed $clientId
     *
     * @return AbstractApi
     */
    public function setClientId($clientId): AbstractApi
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientSecret
     *
     * @return null|string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set clientSecret
     *
     * @param mixed $clientSecret
     *
     * @return AbstractApi
     */
    public function setClientSecret($clientSecret): AbstractApi
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get httpAuth
     *
     * @return array
     */
    public function getHttpAuth(): array
    {
        return $this->httpAuth;
    }

    /**
     * Set httpAuth
     *
     * @param string $username
     * @param string $password
     *
     * @return AbstractApi
     */
    public function setHttpAuth(string $username, string $password = ''): AbstractApi
    {
        $this->httpAuth['username'] = $username;
        $this->httpAuth['password'] = $password;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set token
     *
     * @param string $token
     * @param int    $authentication
     *
     * @return AbstractApi
     */
    public function setToken(string $token, int $authentication = self::OAUTH_AUTH): AbstractApi
    {
        $this->token = $token;
        $this->setAuthentication($authentication);

        return $this;
    }

    /**
     * Get timeout
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set timeout
     *
     * @param int $timeout
     *
     * @return AbstractApi
     */
    public function setTimeout(int $timeout): AbstractApi
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return AbstractApi
     */
    public function setContentType(string $contentType): AbstractApi
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get pagination
     *
     * @return Pagination|null
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * Set pagination
     *
     * @param Pagination $pagination
     *
     * @return AbstractApi
     */
    public function setPagination(Pagination $pagination): AbstractApi
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Curl request
     *
     * @param string      $url
     * @param string      $method
     * @param array       $postFields
     * @param null|string $apiUrl
     *
     * @return array
     */
    public function request(string $url, string $method = Request::METHOD_GET, array $postFields = [],
                            string $apiUrl = null): array
    {
        /** Building url */
        if (null === $apiUrl) {
            $apiUrl = $this->getApiUrl();
        }
        $url   = $apiUrl . $url;
        $query = [];

        /**
         * OAuth2 Key/Secret authentication
         *
         * @see https://developer.github.com/v3/#oauth2-keysecret
         */
        if (null !== $this->getClientId() && null !== $this->getClientSecret()) {
            $query['client_id']     = $this->getClientId();
            $query['client_secret'] = $this->getClientSecret();
        } /**
         * Basic authentication via OAuth2 Token (sent as a parameter)
         *
         * @see https://developer.github.com/v3/#oauth2-token-sent-as-a-parameter
         */ else if ($this->getAuthentication() === self::OAUTH2_PARAMETERS_AUTH) {
            $query['access_token'] = $this->getToken();
        }

        /**
         * Pagination
         * Requests that return multiple items will be paginated to 30 items by default.
         * You can specify further pages with the ?page parameter.
         * For some resources, you can also set a custom page size up to 100 with the ?per_page parameter.
         * Note that for technical reasons not all endpoints respect the ?per_page parameter,
         *
         * @see https://developer.github.com/v3/#pagination
         */
        if (null !== $this->getPagination()) {
            if (null !== $this->getPagination()->getPage()) {
                $query['page'] = $this->getPagination()->getPage();
            }
            if (null !== $this->getPagination()->getLimit()) {
                $query['per_page'] = $this->getPagination()->getLimit();
            }
        }

        /**
         * Add URL-encoded query string parameters
         */
        if (!empty($query)) {
            $url .= (strstr($url, '?') !== false ? '&' : '?');
            $url .= http_build_query($query);
        }

        /** Call curl */
        $curl = new CurlClient();
        $curl->setOption([
            CURLOPT_USERAGENT      => self::USER_AGENT,
            CURLOPT_TIMEOUT        => $this->getTimeout(),
            CURLOPT_HEADER         => false, // Use $client->getHeaders() to get full header
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: ' . $this->getAccept(),
                'Content-Type: ' . $this->getContentType()
            ],
            CURLOPT_URL            => $url
        ]);

        /**
         * Basic authentication via username and Password
         *
         * @see https://developer.github.com/v3/auth/#via-username-and-password
         */
        if (!empty($this->getHttpAuth())) {
            if (!isset($this->getHttpAuth()['password']) || empty($this->getHttpAuth()['password'])) {
                $curl->setOption([
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_USERPWD  => $this->getHttpAuth()['username']
                ]);
            } else {
                $curl->setOption([
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_USERPWD  => sprintf('%s:%s', $this->getHttpAuth()['username'],
                        $this->getHttpAuth()['password'])
                ]);
            }
        }

        if (!empty($this->getToken()) && $this->getAuthentication() !== self::OAUTH2_PARAMETERS_AUTH) {
            /**
             * Basic authentication via OAuth token
             *
             * @see https://developer.github.com/v3/auth/#via-oauth-tokens
             **/
            if ($this->getAuthentication() === self::OAUTH_AUTH) {
                $curl->setOption([
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_USERPWD  => sprintf('%s:x-oauth-basic', $this->getToken())
                ]);
            } /**
             * Basic authentication via OAuth2 Token (sent in a header)
             *
             * @see https://developer.github.com/v3/#oauth2-token-sent-in-a-header
             */ else if ($this->getAuthentication() === self::OAUTH2_HEADER_AUTH) {
                $curl->setOption([
                    CURLOPT_HTTPAUTH   => CURLAUTH_BASIC,
                    CURLOPT_HTTPHEADER => [sprintf('Authorization: token %s', $this->getToken())]
                ]);
            }
        }

        /** Methods */
        switch ($method) {
            /** @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.7 */
            case Request::METHOD_DELETE:
                /** @see http://tools.ietf.org/html/rfc5789 */
            case Request::METHOD_PATCH:
                $curl->setOption([
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => json_encode(array_filter($postFields))
                ]);
                break;

            /** @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.3 */
            case Request::METHOD_GET:
                $curl->setOption(CURLOPT_HTTPGET, true);
                break;

            /** @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.4 */
            case Request::METHOD_HEAD:
                $curl->setOption([
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_NOBODY        => true
                ]);
                break;

            /** @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5 */
            case Request::METHOD_POST:
                $curl->setOption([
                    CURLOPT_POST       => true,
                    CURLOPT_POSTFIELDS => json_encode(array_filter($postFields))
                ]);
                break;

            /** @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.6 */
            case Request::METHOD_PUT:
                $curl->setOption([
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_PUT           => true,
                    CURLOPT_HTTPHEADER    => [
                        'X-HTTP-Method-Override: PUT',
                        'Content-type: application/x-www-form-urlencoded'
                    ]
                ]);
                break;

            default:
                break;
        }

        $curl->success(function (CurlClient $instance) {
            $this->headers = $instance->getHeaders();
            $this->success = $instance->getResponse();
            $data          = json_decode($this->success, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                $this->success = $data;
            }
        });
        $curl->error(function (CurlClient $instance) {
            $this->headers = $instance->getHeaders();
            $this->failure = $instance->getResponse();
            $data          = json_decode($this->failure, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                $this->failure = $data;
            }
        });
        $curl->perform();

        return (array)($this->success ?? $this->failure);
    }

    /**
     * Return a formatted string. Modified version of sprintf using colon(:)
     *
     * @param string $string
     * @param array  $params
     *
     * @return String
     * @throws Exception
     */
    public function sprintf(string $string, ...$params): string
    {
        preg_match_all('/\:([A-Za-z0-9_]+)/', $string, $matches);
        $matches = $matches[1];

        if (count($matches)) {
            $tokens   = [];
            $replaces = [];

            foreach ($matches as $key => $value) {
                if (count($params) > 1 || !is_array($params[0])) {
                    if (!array_key_exists($key, $params)) {
                        throw new Exception('Too few arguments, missing argument: ' . $key);
                    }
                    $replaces[] = $params[$key];
                } else {
                    if (!array_key_exists($value, $params[0])) {
                        throw new Exception('Missing array argument: ' . $key);
                    }
                    $replaces[] = $params[0][$value];
                }
                $tokens[] = ':' . $value;
            }

            $string = str_replace($tokens, $replaces, $string);
        }

        return $string;
    }
}