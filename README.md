# PHP Web Server

This is a simple PHP-based web server designed for experimentation and demonstration purposes. It allows you to serve multiple websites from different directories and handle basic `.htaccess` configurations.

## Features

- Serve multiple websites from different directories.
- Basic `.htaccess` support for URL rewriting.
- Customizable configuration file for website mapping and ports.
- Targets `index.html` by default when serving directories.

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/coderooz/php-web-server.git
   cd php-web-server
   ```

2. **Install Dependencies**

   Make sure you have [Composer](https://getcomposer.org/) installed. Run:

   ```bash
   composer install
   ```

## Configuration

1. **Create `config.json`**

   Create a `config.json` file in the project root directory with the following format:

   ```json
   {
       "websites": [
           {
               "port": 8000,
               "documentRoot": "htdocs/website1"
           },
           {
               "port": 8080,
               "documentRoot": "htdocs/website2"
           }
       ]
   }
   ```

   - `port`: The port on which the website should be served.
   - `documentRoot`: The directory where the website files are located.

2. **Directory Structure**

   Place your website directories under the `htdocs` folder. For example:

   ```
   php-web-server/
   ├── htdocs/
   │   ├── website1/
   │   │   ├── .htaccess
   │   │   ├── index.html
   │   │   └── other-files
   │   └── website2/
   │       ├── .htaccess
   │       ├── index.html
   │       └── other-files
   ├── src/
   ├── config.json
   ├── server
   └── README.md
   ```

## Running the Server

Start the server by running:

```bash
php server
```

The server will read the `config.json` file and start serving the websites on the specified ports. For example:

- Website 1 will be served on [http://localhost:8000](http://localhost:8000).
- Website 2 will be served on [http://localhost:8080](http://localhost:8080).

## Handling `.htaccess`

The server performs basic `.htaccess` processing for URL rewriting. If you need more advanced `.htaccess` support, consider using a dedicated web server like Apache or Nginx.

## Example `.htaccess`

Here's a basic example of a `.htaccess` file for URL rewriting:

```
RewriteEngine On
RewriteRule ^old-page\.html$ new-page.html [L,R=301]
```

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

For questions or comments, please contact Ranit Saha at [Coderooz](https://github.com/coderooz).


