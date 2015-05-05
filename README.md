## Test Driven Joomla [![Build Status](https://travis-ci.org/icarus/test-driven-joomla.svg?branch=master)](https://travis-ci.org/icarus/test-driven-joomla)

The principle of this experimental project is to rebuild Joomla from the ground up using test-driven development methodologies.

### Goals

1. New code can be added as long as the existing tests pass.
1. New behaviours can be added as long there are tests for them.
1. The single httpTest should be, initially, satisfactory for building nearly the entire application architecture.
1. RESTful resource end-points must be part of the architecture from the very start (in fact, anything that is not navigation must use RESTful routes).
  - `GET /path/to/path` is a navigational route (no "v" prefix).
  - `GET /v1/users/1` is a RESTful API route.
  - `POST /v1/users/` is what an HTML form would use to create a new user (a `redirect` may be included in the request).
  - `PUT /v1/users/1` is what is used to update properties in an existing record.
  - `DELETE /v1/users/1` is what is used to remove a resource.
1. The application should be able to run just under the PHP built-in web server.
   - Specific web servers should be tuned by composition.
1. Should be able to install extensions via Composer.
1. Should be able to run a site from a file-based storage system (for simple sites, and only move to an RDMS when you need to - compare with Jekyll), and an in-memory solution for testing purposes, or even a NoSQL solution.
1. All executable code, except for the application entry-point, should be kept out of the `public` directory. The `public` directory is the only directory allow to be accessed by the web server.
1. Checkin/checkout should be replaced with "ETag" comparison on update (or similar).

### Methodolgy

1. Write a failing test for new behaviours.
2. Write just enough code to make the test pass.
3. Refactor your test code and the tested code.
4. Only test behaviours (interfaces), not implementations.
5. Aim for 100% code coverage from all tests combined.
6. Individual test methods should aim to have a maximum of 4xA lines of code:
   - Arrange    - set up the system state
   - Act        - do the thing we are testing
   - Assert     - inspect the resulting state
   - Annihilate - tear down


### Running the Application

Just serve the application from the command line (instructions for running under specific web server can come later).

```sh
./serve.sh
```

Open a browser at http://localhost:8000.

### Running Tests

Ensure you have PHPUnit installed.

```sh
phpunit
```

### TODO

* [x] Wire up tests to Travis.
* [ ] Register on Composer.
* [ ] Devise a basic resource test (for example, `GET /posts/1`)
* [ ] Refactor `httpTest` to loop through all the folders under `/tests/http`.
* [ ] Flesh out the TODO section.
