<?php

namespace Flogar\Sunat\GRE\Api;

use Flogar\Sunat\GRE\Model\CpeResponse;
use Flogar\Sunat\GRE\Model\StatusResponse;
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
 * CpeApi Class Doc Comment
 *
 * @category Class
 * @package  Flogar\Sunat\GRE
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class CpeApi implements CpeApiInterface
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
        'consultarEnvio' => [
            'application/json',
        ],
        'enviarCpe' => [
            'application/json',
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
     * Operation consultarEnvio
     *
     * Permite realizar la consulta del envío realizado
     *
     * @param  string $ticket Número de ticket (UUID) generado por el envío realizado (required)
     *
     * @throws \Flogar\Sunat\GRE\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return StatusResponse
     */
    public function consultarEnvio(string $ticket): StatusResponse
    {
        list($response) = $this->consultarEnvioWithHttpInfo($ticket, self::contentTypes['consultarEnvio'][0]);
        return $response;
    }

    /**
     * Operation consultarEnvioWithHttpInfo
     *
     * Permite realizar la consulta del envío realizado
     *
     * @param  string $num_ticket Número de ticket (UUID) generado por el envío realizado (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consultarEnvio'] to see the possible values for this operation
     *
     * @throws \Flogar\Sunat\GRE\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \Flogar\Sunat\GRE\Model\StatusResponse|\Flogar\Sunat\GRE\Model\CpeError|\Flogar\Sunat\GRE\Model\CpeErrorValidation, HTTP status code, HTTP response headers (array of strings)
     */
    public function consultarEnvioWithHttpInfo($num_ticket, string $contentType = self::contentTypes['consultarEnvio'][0])
    {
        $request = $this->consultarEnvioRequest($num_ticket, $contentType);

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
                    if ('\Flogar\Sunat\GRE\Model\StatusResponse' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Flogar\Sunat\GRE\Model\StatusResponse' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Flogar\Sunat\GRE\Model\StatusResponse', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\Flogar\Sunat\GRE\Model\StatusResponse';
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
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Flogar\Sunat\GRE\Model\CpeError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Flogar\Sunat\GRE\Model\CpeErrorValidation',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation consultarEnvioAsync
     *
     * Permite realizar la consulta del envío realizado
     *
     * @param  string $num_ticket Número de ticket (UUID) generado por el envío realizado (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consultarEnvio'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consultarEnvioAsync($num_ticket, string $contentType = self::contentTypes['consultarEnvio'][0])
    {
        return $this->consultarEnvioAsyncWithHttpInfo($num_ticket, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation consultarEnvioAsyncWithHttpInfo
     *
     * Permite realizar la consulta del envío realizado
     *
     * @param  string $num_ticket Número de ticket (UUID) generado por el envío realizado (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consultarEnvio'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consultarEnvioAsyncWithHttpInfo($num_ticket, string $contentType = self::contentTypes['consultarEnvio'][0])
    {
        $returnType = '\Flogar\Sunat\GRE\Model\StatusResponse';
        $request = $this->consultarEnvioRequest($num_ticket, $contentType);

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
     * Create request for operation 'consultarEnvio'
     *
     * @param  string $num_ticket Número de ticket (UUID) generado por el envío realizado (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consultarEnvio'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function consultarEnvioRequest($num_ticket, string $contentType = self::contentTypes['consultarEnvio'][0])
    {

        // verify the required parameter 'num_ticket' is set
        if ($num_ticket === null || (is_array($num_ticket) && count($num_ticket) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $num_ticket when calling consultarEnvio'
            );
        }


        $resourcePath = '/contribuyente/gem/comprobantes/envios/{numTicket}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // path params
        if ($num_ticket !== null) {
            $resourcePath = str_replace(
                '{' . 'numTicket' . '}',
                ObjectSerializer::toPathValue($num_ticket),
                $resourcePath
            );
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

        // this endpoint requires Bearer authentication (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
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
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation enviarCpe
     *
     * Permite realizar el envio del comprobante
     *
     * @param  string $filename Nombre del archivo sin extension (required)
     * @param  \Flogar\Sunat\GRE\Model\CpeDocument $cpe_document cpe_document (optional)
     *
     * @throws \Flogar\Sunat\GRE\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return CpeResponse
     */
    public function enviarCpe($filename, $cpe_document = null): CpeResponse
    {
        list($response) = $this->enviarCpeWithHttpInfo($filename, $cpe_document, self::contentTypes['enviarCpe'][0]);
        return $response;
    }

    /**
     * Operation enviarCpeWithHttpInfo
     *
     * Permite realizar el envio del comprobante
     *
     * @param  string $filename Nombre del archivo sin extension (required)
     * @param  \Flogar\Sunat\GRE\Model\CpeDocument $cpe_document (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['enviarCpe'] to see the possible values for this operation
     *
     * @throws \Flogar\Sunat\GRE\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \Flogar\Sunat\GRE\Model\CpeResponse HTTP status code, HTTP response headers (array of strings)
     */
    public function enviarCpeWithHttpInfo($filename, $cpe_document = null, string $contentType = self::contentTypes['enviarCpe'][0])
    {
        $request = $this->enviarCpeRequest($filename, $cpe_document, $contentType);

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
                    if ('\Flogar\Sunat\GRE\Model\CpeResponse' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Flogar\Sunat\GRE\Model\CpeResponse' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Flogar\Sunat\GRE\Model\CpeResponse', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\Flogar\Sunat\GRE\Model\CpeResponse';
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
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Flogar\Sunat\GRE\Model\CpeError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Flogar\Sunat\GRE\Model\CpeErrorValidation',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation enviarCpeAsync
     *
     * Permite realizar el envio del comprobante
     *
     * @param  string $filename Nombre del archivo sin extension (required)
     * @param  \Flogar\Sunat\GRE\Model\CpeDocument $cpe_document (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['enviarCpe'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function enviarCpeAsync($filename, $cpe_document = null, string $contentType = self::contentTypes['enviarCpe'][0])
    {
        return $this->enviarCpeAsyncWithHttpInfo($filename, $cpe_document, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation enviarCpeAsyncWithHttpInfo
     *
     * Permite realizar el envio del comprobante
     *
     * @param  string $filename Nombre del archivo sin extension (required)
     * @param  \Flogar\Sunat\GRE\Model\CpeDocument $cpe_document (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['enviarCpe'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function enviarCpeAsyncWithHttpInfo($filename, $cpe_document = null, string $contentType = self::contentTypes['enviarCpe'][0])
    {
        $returnType = '\Flogar\Sunat\GRE\Model\CpeResponse';
        $request = $this->enviarCpeRequest($filename, $cpe_document, $contentType);

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
     * Create request for operation 'enviarCpe'
     *
     * @param  string $filename Nombre del archivo sin extension (required)
     * @param  \Flogar\Sunat\GRE\Model\CpeDocument $cpe_document (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['enviarCpe'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function enviarCpeRequest($filename, $cpe_document = null, string $contentType = self::contentTypes['enviarCpe'][0])
    {

        // verify the required parameter 'filename' is set
        if ($filename === null || (is_array($filename) && count($filename) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $filename when calling enviarCpe'
            );
        }



        $resourcePath = '/contribuyente/gem/comprobantes/{filename}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // path params
        if ($filename !== null) {
            $resourcePath = str_replace(
                '{' . 'filename' . '}',
                ObjectSerializer::toPathValue($filename),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($cpe_document)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\json_encode(ObjectSerializer::sanitizeForSerialization($cpe_document));
            } else {
                $httpBody = $cpe_document;
            }
        } elseif (count($formParams) > 0) {
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

        // this endpoint requires Bearer authentication (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
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
