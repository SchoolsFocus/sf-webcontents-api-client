<?php
namespace SfWebcontentsApiClient;

use CurlHandle;

/**
 * WebcontentsApiClient Class
 *
 * This class provides methods to interact with the Webcontents API.
 * It allows fetching website content, events, media, system data, and website menu.
 *
 * @package SfWebcontentsApiClient
 */
class WebcontentsApiClient
{
    private string $apiUrl;
    private string $apiKey;
    private array $curlOptions;

    /**
     * Constructor
     *
     * Initializes the client with the API URL and API key.
     *
     * @param string $apiUrl The base URL of the Webcontents API.
     * @param string $apiKey The API key for authentication.
     */
    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->apiUrl = rtrim($apiUrl, '/') . '/';
        $this->apiKey = $apiKey;

        $this->curlOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'API-NUM: ' . $this->apiKey
            ],
            CURLOPT_TIMEOUT => 30,
        ];
    }

    /**
     * Fetch Website Content
     *
     * Retrieves website content items from the API.
     *
     * @param array $params Parameters to filter the content.
     *      - string $params['name']: The exact name of the content item.
     *      - string $params['prefix']: A prefix to search for content items.
     *      - int $params['id']: The specific ID of a content item.
     *      - int $params['limit']: The maximum number of results to return.
     *      - int $params['start']: The starting offset for pagination.
     *      - bool|string $params['has_uploaded_file']: Filter for content with an associated file.
     *      - string $params['content_type']: Filter by type (e.g., 'slider_image', 'page', 'content').
     * @return array The API response, decoded as an associative array.
     */
    public function fetchContent(array $params = []): array
    {
        $url = $this->apiUrl . 'api/webcontents/fetchContent';
        return $this->sendRequest($url, 'GET', $params);
    }

    /**
     * Fetch Events
     *
     * Retrieves news, events, or blog posts from the API.
     *
     * @param array $params Parameters to filter the events.
     *      - string $params['timeline']: Filter by time ('upcoming', 'past', 'previous').
     *      - string $params['type']: The type of event ('event', 'blog', 'news', etc.).
     *      - int $params['limit']: The maximum number of results to return.
     *      - int $params['start']: The starting offset for pagination.
     *      - int $params['id']: The specific ID of an event.
     * @return array The API response, decoded as an associative array.
     */
    public function fetchEvents(array $params = []): array
    {
        $url = $this->apiUrl . 'api/webcontents/fetchEvents';
        return $this->sendRequest($url, 'GET', $params);
    }

    /**
     * Fetch Media Gallery
     *
     * Retrieves items from the media gallery (images or videos).
     *
     * @param array $params Parameters to filter the media gallery.
     *      - string $params['mediaType']: The type of media ('images', 'videos').
     *      - int $params['limit']: The maximum number of results to return.
     *      - int $params['start']: The starting offset for pagination.
     *      - int $params['id']: The specific ID of a gallery item.
     * @return array The API response, decoded as an associative array.
     */
    public function fetchMediaGallery(array $params = []): array
    {
        $url = $this->apiUrl . 'api/webcontents/fetchMediaGallery';
        return $this->sendRequest($url, 'GET', $params);
    }

    /**
     * Fetch System Data
     *
     * Retrieves general system configuration and data.
     *
     * @return array The API response, decoded as an associative array.
     */
    public function fetchSystemData(): array
    {
        $url = $this->apiUrl . 'api/webcontents/fetchSystemData';
        return $this->sendRequest($url, 'GET');
    }

    /**
     * Fetch Website Menu
     *
     * Retrieves the navigation menu structure for the website.
     *
     * @param array $params Parameters to filter the menu items.
     *      - string $params['menuLevel']: The level of the menu to fetch ('parent', 'child_level_1', etc.).
     *      - int $params['parentId']: The ID of the parent menu item.
     * @return array The API response, decoded as an associative array.
     */
    public function fetchWebsiteMenu(array $params = []): array
    {
        $url = $this->apiUrl . 'api/webcontents/fetchWebsiteMenu';
        return $this->sendRequest($url, 'GET', $params);
    }

    /**
     * Send Request to API Endpoint
     *
     * A private helper method that handles the actual cURL request to the API.
     *
     * @param string $url The full API endpoint URL.
     * @param string $method The HTTP method (e.g., 'GET', 'POST').
     * @param array $params Parameters to send with the request.
     * @return array The decoded JSON response as an associative array.
     */
    private function sendRequest(string $url, string $method, array $params = []): array
    {
        $ch = curl_init();

        if (strtoupper($method) === 'GET' && !empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt_array($ch, $this->curlOptions);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (strtoupper($method) !== 'GET' && !empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return ['status' => false, 'message' => 'API connection error: ' . $error_message, 'data' => null];
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded_response = json_decode($response, true);

        if ($http_code >= 400) {
            $message = $decoded_response['message'] ?? 'API request failed with HTTP code ' . $http_code;
            return ['status' => false, 'message' => $message, 'data' => $decoded_response];
        }

        return $decoded_response;
    }

    /**
     * Get the API URL.
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * Set the API URL.
     *
     * @param string $apiUrl
     * @return void
     */
    public function setApiUrl(string $apiUrl): void
    {
        $this->apiUrl = rtrim($apiUrl, '/') . '/';
    }

    /**
     * Get the API Key.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set the API Key.
     *
     * @param string $apiKey
     * @return void
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
        // Update the header with the new API key
        foreach ($this->curlOptions[CURLOPT_HTTPHEADER] as &$header) {
            if (strpos($header, 'API-NUM: ') === 0) {
                $header = 'API-NUM: ' . $apiKey;
                break;
            }
        }
    }
}