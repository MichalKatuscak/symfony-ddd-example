Feature: Order Management
  In order to purchase products
  As a customer
  I need to be able to create and place orders

  Scenario: Creating and placing an order
    Given I have a new order with the following items:
      | product  | price   | quantity |
      | Laptop   | 1299.99 | 1        |
      | Mouse    | 49.99   | 2        |
    When I place the order
    Then the order should be in "placed" status
    And the order total should be "1399.97"

  Scenario: Order triggers invoice creation
    Given I have a new order with the following items:
      | product  | price   | quantity |
      | Laptop   | 1299.99 | 1        |
    When I place the order
    Then the order should be in "placed" status
    And an invoice should be created for the order
    And the invoice should be in "issued" status
