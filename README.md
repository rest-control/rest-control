![RestControl](.github/rest-control.jpg)

About RestControl
---
[RestControl](https://rest-control.github.io/) is modern and powerful framework for testing REST services. RestControl provides set of tools for describing HTTP requests and responses in expressive and elegant way.


[![Latest Stable Version](https://poser.pugx.org/rest-control/rest-control/v/stable)](https://packagist.org/packages/rest-control/rest-control)
[![Latest Unstable Version](https://poser.pugx.org/rest-control/rest-control/v/unstable)](https://packagist.org/packages/rest-control/rest-control)
[![License](https://poser.pugx.org/rest-control/rest-control/license)](https://packagist.org/packages/rest-control/rest-control)
[![Build status](https://ci.appveyor.com/api/projects/status/otm3svuo0nol0big?svg=true)](https://ci.appveyor.com/project/kamszel/rest-control)
[![Build Status](https://travis-ci.org/rest-control/rest-control.svg?branch=master)](https://travis-ci.org/rest-control/rest-control)

Examples
---

Here is a simple example of how to send a GET request and validate json response:

```php
 /**
  * @test(
  *     title="Example test",
  *     description="Example test description",
  *     tags="find user"
  * )
  */
 public function exampleFindUser()
 {
    return send()->get('https://jsonplaceholder.typicode.com/users/1')
                 ->expectedResponse()
                 ->json()
                 ->jsonPath('$.address.street', endsWith('Light'));
 }
```

Quick Start
---

The best way for quick start is to use RestControl standalone application. You can find it here https://github.com/rest-control/standalone-testing-application. If you want to save yourself the hassle of installing dependencies required for this project on your local machine, you can use Docker containers as an alternative. Note that you will need Docker and Docker Compose in version >= 2.1.

To build and start RestControl standalone application, run following commands:

```
user@user:~/projects/standalone-testing-application$ make build
user@user:~/projects/standalone-testing-application$ make start
```
Now, you can use Docker machine and run example tests.

```
user@user:~/projects/standalone-testing-application$ docker exec -it restcontrol_cli_1 bash
user@machineid:/app# php vendor/bin/rest-control run
```

Contributing
---

Thank you if you considering contributing to RestControl! The contribution guide will be available soon. Start watching this project to get regular updates.

Project roadmap
---
All planned releases you can find here: [https://github.com/rest-control/rest-control/wiki/Roadmap](https://github.com/rest-control/rest-control/wiki/Roadmap).

Learning RestControl
---
RestControl documentation is under construction, please be patient. Current documentation files are available on [https://rest-control.github.io/](https://rest-control.github.io/)
