Feature: Setting up routing
    In order make my REST API queriable
    As an API developer creating a new service
    I want to set up routing for my API service

    @wip
    Scenario: Querying an API index
        When I send a GET request to '/'
        Then I should get status code '200'

    @wip @critical
    Scenario: Querying a service that is configured in routing
        Given there is a resource of type 'person' with ID '123' and body '{"name": "Eminem"}'
        When I send a GET request to '/person/123'
        Then I should get status code '200'
        And I should get response body '{"name": "Eminem"}'
