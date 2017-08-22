Feature: Check all task actions.
  Background:
    Given there are projects with the following details:
      | uid  | title       | hash          | customer    | description       |
      | u1   | Some title  | kdkjkzejf     | Belgacom    | Some description  |

    Given there are task with the following details:
      | uid  | name          | hash             | description       |
      | u1   | Behat test 1  | randomeER97G     | api_v1_get_task   |
      | u2   | Behat test 2  | randomeER123     | api_v1_post_task  |
      | u3   | Behat test 3  | randomeER234     | get project task  |

    Given I login the user "test" "test"

  Scenario: get task by hash code
    Given I login the user "test" "test"
    When I request "GET" "/v1/tasks/" with one parameter "randomeER97G" to get or add a "task"
    Then the response code should be 200
    And the response should contain a "id"

  Scenario: get task for a project by hash code
    Given I login the user "test" "test"
    When I request "GET" "/v1/projects/" with one nested parameter "kdkjkzejf" "/tasks" to get or add a "task"
    Then the response code should be 200
    And the response should be an array

  Scenario: get task for a user by username
    Given I login the user "test" "test"
    When I request "GET" "/v1/users/" with one nested parameter "test" "/tasks" to get or add a "task"
    Then the response code should be 200
    And the response should be an array

  Scenario: create a task for a project
    Given I have the following payload
    """
    {"task":{
      "description":"Fix the issue behat test",
      "name":"Behat test",
      "estimatePomodoros":"1",
      "finished": false,
      "inProgress": true,
      "isWorking":"lottverw"}}
    """
    And  I login the user "test" "test"
    When I request "POST" "/v1/task/test/project/" with one parameter "kdkjkzejf" to get or add a "task"
    Then the response code should be 201
    And the response should be an object with name "task"
