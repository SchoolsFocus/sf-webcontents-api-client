# sf-webcontents-api-client

## Introduction

The `sf-webcontents-api-client` is a PHP package designed to provide a simple and convenient way to interact with the SchoolsFocus Webcontents API. This package allows developers to easily fetch website content, events, media, system data, and website menu structures from the Webcontents service. It is framework-agnostic, meaning it can be used with any PHP framework or even in vanilla PHP applications.

## Installation

To install the `sf-webcontents-api-client`, you can use Composer. Run the following command in your terminal:

```bash
composer require schoolsfocus/sf-webcontents-api-client
```

## Usage

### Initialization

To use the `WebcontentsApiClient`, you need to create an instance of the class and provide the API URL and API key.

```php
use SfWebcontentsApiClient\WebcontentsApiClient;

$client = new WebcontentsApiClient([
    'api_url' => 'https://your-api-url.com/',
    'api_key' => 'your-api-key'
]);
```

### Fetching Website Content

To fetch website content, use the `fetchContent` method.

```php
$content = $client->fetchContent([
    'name' => 'example_content',
    'limit' => 10
]);
```

#### Parameters:
- `name` (string): The exact name of the content item.
- `prefix` (string): A prefix to search for content items.
- `id` (int): The specific ID of a content item.
- `limit` (int): The maximum number of results to return.
- `start` (int): The starting offset for pagination.
- `has_uploaded_file` (bool|string): Filter for content with an associated file.
- `content_type` (string): Filter by type (e.g., 'slider_image', 'page', 'content').

#### Return Value:
Returns an associative array containing the API response.

### Fetching Events

To retrieve events, use the `fetchEvents` method.

```php
$events = $client->fetchEvents([
    'timeline' => 'upcoming',
    'type' => 'event',
    'limit' => 5
]);
```

#### Parameters:
- `timeline` (string): Filter by time ('upcoming', 'past', 'previous').
- `type` (string): The type of event ('event', 'blog', 'news', etc.).
- `limit` (int): The maximum number of results to return.
- `start` (int): The starting offset for pagination.
- `id` (int): The specific ID of an event.

#### Return Value:
Returns an associative array containing the API response.

### Fetching Media Gallery

To fetch media items, use the `fetchMediaGallery` method.

```php
$media = $client->fetchMediaGallery([
    'mediaType' => 'images',
    'limit' => 10
]);
```

#### Parameters:
- `mediaType` (string): The type of media ('images', 'videos').
- `limit` (int): The maximum number of results to return.
- `start` (int): The starting offset for pagination.
- `id` (int): The specific ID of a gallery item.

#### Return Value:
Returns an associative array containing the API response.

### Fetching System Data

To retrieve general system configuration and data, use the `fetchSystemData` method.

```php
$systemData = $client->fetchSystemData();
```

#### Return Value:
Returns an associative array containing the API response.

### Fetching Website Menu

To get the navigation menu structure, use the `fetchWebsiteMenu` method.

```php
$menu = $client->fetchWebsiteMenu([
    'menuLevel' => 'parent'
]);
```

#### Parameters:
- `menuLevel` (string): The level of the menu to fetch ('parent', 'child_level_1', 'child_level_2').
- `parentId` (int): The ID of the parent menu item (required for child levels).

#### Return Value:
Returns an associative array containing the API response.

## License

This package is licensed under the MIT License. See the LICENSE file for more details.