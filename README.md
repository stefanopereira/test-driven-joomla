## Test Driven Joomla

The principle of this experimental project is to rebuild Joomla from the ground up using test-driven development methodologies.

### Goals

1. New code can be added as long as the existing tests pass.
2. New behaviours can be added as long there are tests for it.
3. The single httpTest should be satisfactory for building nearly the entire application architecture.
4. RESTful resource end-points must be part of the architecture from the very start (in fact, anything that is not navigation must use RESTful routes).
5. The application should be able to run just under the PHP built-in web server.
   - Specific web servers should be tuned by composition.
6. Extensions should be installable via Composer.

### Methodolgy

1. Write a failing test for new behaviours.
2. Write just enough code to make the test pass.
3. Refactor your test code and the tested code.
4. Only test behaviours (interfaces), not implementations.
5. Aim for 100% code coverage from all tests combined.

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

* [ ] Wire up tests to Travis.
* [ ] Devise a basic resource test (for example, `GET /posts/1`)
* [ ] Refactor `httpTest` to loop through all the folders under `/tests/http`.
* [ ] Flesh out the TODO section.
