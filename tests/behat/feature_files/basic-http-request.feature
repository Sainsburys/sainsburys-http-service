Feature: Setting up routing
    In order make my REST API queriable
    As an API developer creating a new service
    I want to set up routing for my API service

    @wip
    Scenario: Querying an API index
        When I send a GET request to '/'
        Then I should get status code '200'

    @critical
    Scenario: Querying a service that is configured in routing
        Given my API is coded to return a the response '{"name":"Eminem"}' for route '/person/123'
        When I send a GET request to '/person/123'
        Then I should get status code '200'
        And I should get response body '{"name":"Eminem"}'
