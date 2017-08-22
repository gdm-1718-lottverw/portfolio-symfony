Feature: Authenticate
  In order to access the app
  As an API client
  I need to login and register

  Background:
    Given there are Users with the following details:
      | uid | username | email          | password |
      | u1  | peter    | peter@test.com | testpass |
      | u2  | john     | john@test.org  | johnpass |

  Scenario: login a user
    Given I have a user with following username "test" and password "test"
    When I check the user "POST" "/login_check"
    Then the response code should be 200
    And the response should contain a "token"

  Scenario: Register a user
    Given I have the following payload
    """
      {
        "username":"chelsyvdb",
        "email":"chelsyvdb@gmail.com",
        "emailCanonical":"chelsyvdb@gmail.com",
        "plainPassword": "chelsy",
        "password":"chelsy",
        "firstName":"chelsy",
        "lastName":"van den Broeck"
      }
    """
    When I request "POST" "/v1/register"
    Then the response code should be 201
