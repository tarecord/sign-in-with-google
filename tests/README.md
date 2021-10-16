# Testing

## Requirements
1. Duplicate and fill out `.env.example` with the appropriate values to set up the testing environment.
2. Start a stand alone instance of Selenium with:
```sh
docker run -p 4444:4444 selenium/standalone-chrome
```
3. With Selenium running in a window, run tests with `./vendor/bin/codecept run`
