<?php

namespace Flogar\Sunat\GRE\Api;

use Flogar\Sunat\GRE\Model\ApiToken;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Flogar\Sunat\GRE\ApiException;
use Flogar\Sunat\GRE\Configuration;
use Flogar\Sunat\GRE\HeaderSelector;
use Flogar\Sunat\GRE\ObjectSerializer;

/**
 * AuthApi Class Doc Comment
 *
 * @category Class
 * @package  Flogar\Sunat\GRE
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class AuthApi implements AuthApiInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /** @var string[] $contentTypes **/
    public const contentTypes = [
        'getToken' => [
            'application/x-www-form-urlencoded',
        ],
    ];

/**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ClientInterface $client = null,
        Configuration $config = null,
        HeaderSelector $selector = null,
        $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: new Configuration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation getToken
     *
     * Generar un nuevo token
     *
     * @param  string $grant_type grant_type (required)
     * @param  string $scope scope (required)
     * @param  string $client_id client_id generado en menú sol (required)
     * @param  string $client_secret client_secret generado en menú sol (required)
     * @param  string $username &lt;Numero de RUC&gt; + &lt;Usuario SOL&gt; (required)
     * @param  string $password Contrasena SOL (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getToken'] to see the possible values for this operation
     *
     * @throws \Flogar\Sunat\GRE\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return ApiToken
     */
    public function getToken(?string $grant_type, ?string $scope, ?string $client_id, ?string $client_secret, ?string $username, ?string $password): ApiToken
    {
        list($response) = $this->getTokenWithHttpInfo($grant_type, $scope, $client_id, $client_secret, $username, $password, self::contentTypes['getToken'][0]);
        return $response;
    }

    /**
     * Operation getTokenWithHttpInfo
     *
     * Generar un nuevo token
     *
     * @param  string $grant_type (required)
     * @param  string $scope (required)
     * @param  string $client_id client_id generado en menú sol (required)
     * @param  string $client_secret client_secret generado en menú sol (required)
     * @param  string $username &lt;Numero de RUC&gt; + &lt;Usuario SOL&gt; (required)
     * @param  string $password Contrasena SOL (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getToken'] to see the possible values for this operation
     *
     * @throws \Flogar\Sunat\GRE\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \Flogar\Sunat\GRE\Model\ApiToken, HTTP status code, HTTP response headers (array of strings)
     */
    public function getTokenWithHttpInfo($grant_type, $scope, $client_id, $client_secret, $username, $password, string $contentType = self::contentTypes['getToken'][0])
    {
        $request = $this->getTokenRequest($grant_type, $scope, $client_id, $client_secret, $username, $password, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\Flogar\Sunat\GRE\Model\ApiToken' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Flogar\Sunat\GRE\Model\ApiToken' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Flogar\Sunat\GRE\Model\ApiToken', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\Flogar\Sunat\GRE\Model\ApiToken';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Flogar\Sunat\GRE\Model\ApiToken',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation getTokenAsync
     *
     * Generar un nuevo token
     *
     * @param  string $grant_type (required)
     * @param  string $scope (required)
     * @param  string $client_id client_id generado en menú sol (required)
     * @param  string $client_secret client_secret generado en menú sol (required)
     * @param  string $username &lt;Numero de RUC&gt; + &lt;Usuario SOL&gt; (required)
     * @param  string $password Contrasena SOL (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getToken'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getTokenAsync($grant_type, $scope, $client_id, $client_secret, $username, $password, string $contentType = self::contentTypes['getToken'][0])
    {
        return $this->getTokenAsyncWithHttpInfo($grant_type, $scope, $client_id, $client_secret, $username, $password, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getTokenAsyncWithHttpInfo
     *
     * Generar un nuevo token
     *
     * @param  string $grant_type (required)
     * @param  string $scope (required)
     * @param  string $client_id client_id generado en menú sol (required)
     * @param  string $client_secret client_secret generado en menú sol (required)
     * @param  string $username &lt;Numero de RUC&gt; + &lt;Usuario SOL&gt; (required)
     * @param  string $password Contrasena SOL (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getToken'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getTokenAsyncWithHttpInfo($grant_type, $scope, $client_id, $client_secret, $username, $password, string $contentType = self::contentTypes['getToken'][0])
    {
        $returnType = '\Flogar\Sunat\GRE\Model\ApiToken';
        $request = $this->getTokenRequest($grant_type, $scope, $client_id, $client_secret, $username, $password, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getToken'
     *
     * @param  string $grant_type (required)
     * @param  string $scope (required)
     * @param  string $client_id client_id generado en menú sol (required)
     * @param  string $client_secret client_secret generado en menú sol (required)
     * @param  string $username &lt;Numero de RUC&gt; + &lt;Usuario SOL&gt; (required)
     * @param  string $password Contrasena SOL (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getToken'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getTokenRequest($grant_type, $scope, $client_id, $client_secret, $username, $password, string $contentType = self::contentTypes['getToken'][0])
    {
        // verify the required parameter 'client_id' is set
        if ($client_id === null || (is_array($client_id) && count($client_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $client_id when calling getToken'
            );
        }

        // verify the required parameter 'grant_type' is set
        if ($grant_type === null || (is_array($grant_type) && count($grant_type) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $grant_type when calling getToken'
            );
        }

        // verify the required parameter 'scope' is set
        if ($scope === null || (is_array($scope) && count($scope) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $scope when calling getToken'
            );
        }

        // verify the required parameter 'client_id' is set
        if ($client_id === null || (is_array($client_id) && count($client_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $client_id when calling getToken'
            );
        }

        // verify the required parameter 'client_secret' is set
        if ($client_secret === null || (is_array($client_secret) && count($client_secret) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $client_secret when calling getToken'
            );
        }

        // verify the required parameter 'username' is set
        if ($username === null || (is_array($username) && count($username) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $username when calling getToken'
            );
        }

        // verify the required parameter 'password' is set
        if ($password === null || (is_array($password) && count($password) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $password when calling getToken'
            );
        }


        $resourcePath = '/clientessol/{client_id}/oauth2/token/';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // path params
        if ($client_id !== null) {
            $resourcePath = str_replace(
                '{' . 'client_id' . '}',
                ObjectSerializer::toPathValue($client_id),
                $resourcePath
            );
        }

        // form params
        if ($grant_type !== null) {
            $formParams['grant_type'] = ObjectSerializer::toFormValue($grant_type);
        }
        // form params
        if ($scope !== null) {
            $formParams['scope'] = ObjectSerializer::toFormValue($scope);
        }
        // form params
        if ($client_id !== null) {
            $formParams['client_id'] = ObjectSerializer::toFormValue($client_id);
        }
        // form params
        if ($client_secret !== null) {
            $formParams['client_secret'] = ObjectSerializer::toFormValue($client_secret);
        }
        // form params
        if ($username !== null) {
            $formParams['username'] = ObjectSerializer::toFormValue($username);
        }
        // form params
        if ($password !== null) {
            $formParams['password'] = ObjectSerializer::toFormValue($password);
        }

        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\json_encode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}
