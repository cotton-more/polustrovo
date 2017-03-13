Polustrovo construction site screenshot grabber
===============================================

Getting a screenshot
```bash
php ./bin/run.php take:screenshot
```

## Installation

 1. `git clone` _this_ repository.
 2. Download composer: `curl -s https://getcomposer.org/installer | php`
 3. Install dependencies: `php composer.phar install`
 
Copy `.env.dist` to `.env` and enter values for an url to grab `URL`, a key for Browshot API `BROWSHOT_KEY` and a file for sqlite database `DB_DATABASE`

## Usage

Taking a screenshot:

```bash
php ./bin/run.php take:screenshot
```

It will be stored in a `screenshots` directory. The destination folder could be changed in a `config/settings.php` file under a `screenshots_dir` key. 

To run a web application launch a docker container:

```bash
docker-compose up -d
```

## License

This app is licensed under the MIT license.
