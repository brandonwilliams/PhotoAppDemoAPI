# Responsive Image Gallery API
by Brandon Williams

[Frontend Angular Client](https://github.com/brandonwilliams/PhotoAppDemoAngular8Client/)

## Setup the `.env` File

You will need to rename `.env.example` to `.env` and provide the values manually (if necessary).

## Install the Dependencies and Start the API

First, clone/download the repository and cd into your project's directory in your terminal and then run the following commands:

```bash
composer install
php -S localhost:3010
```

The API will be served at `http://localhost:3010`. 
* This assumes that you have PHP and Composer already installed on your machine.

## Endpoint

You must link to this endpoint from the client:

**GET** /api/public/images
* Returns image data. Optional parameters include 'page' (which is the current page number) and 'perpage' (which is the number of images per each page). Both must be included and valid to be recognized. Example: 'http://localhost:3010/PhotoApp/api/public/images?page=1&perpage=10'
