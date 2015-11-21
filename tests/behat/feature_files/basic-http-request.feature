Feature: Setting up routing
    In order make my REST API queriable
    As an API developer creating a new service
    I want to set up routing for my API service

    Scenario: Querying a service that is configured in routing
        Given my API is coded to return a the response '{"name":"Eminem"}' for route '/person/123'
        When I send a GET request to '/person/123'
        Then I should get status code '200'
        And I should get response body '{"name":"Eminem"}'

    Scenario: Getting a generic error
        Given my API is coded to throw a generic, uncaught exception in the controller
        When I send a GET request to '/error/generic-exception'
        Then I should get status code '500'
        And the response body should contain 'Exception message'

    Scenario: Getting an error with a custom statuc code
        Given my API is coded to throw an exception with an HTTP status code on it
        When I send a GET request to '/error/exception-with-status-code'
        Then I should get status code '401'
        And the response body should contain 'Access to resource is not authorised.'
