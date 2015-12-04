Feature: Setting up routing
    In order make my REST API queriable
    As an API developer creating a new service
    I want to set up routing for my API service

    @critical
    Scenario: Querying a service that is configured in routing
        Given my API is coded to return a the response '{"id":"123","name":"Eminem"}' for route '/person/123'
        When I send a GET request to '/person/123'
        Then I should get status code '200'
        And I should get response body '{"id":"123","name":"Eminem"}'

    Scenario: Getting a generic error
        Given my API is coded to throw a generic, uncaught exception in the controller
        When I send a GET request to '/error/generic-exception'
        Then I should get status code '500'
        And the response body should contain 'Exception message'

    Scenario: Getting an error with a custom status code
        Given my API is coded to throw an exception with an HTTP status code on it
        When I send a GET request to '/error/exception-with-status-code'
        Then I should get status code '401'
        And the response body should contain 'Access to resource is not authorised.'

    Scenario: Using middlewares to get the right Content-Type header
        Given my API is coded put the correct Content-Type with a middleware
        When I send a GET request to '/empty/response'
        And the response headers should contain 'Content-Type: application/json'

    @critical
    Scenario: Handling 404s
        Given my API is coded not to have a route for '/unknown/route'
        When I send a GET request to '/unknown/route'
        Then I should get status code '404'
        And the response body should contain 'No route configured for request.'
        And the response headers should contain 'Content-Type: application/json'
