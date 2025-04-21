Feature: End-to-End Order-Invoice-Payment Workflow
  In order to complete a purchase
  As a customer
  I need to be able to place an order, receive an invoice, and make a payment

  Scenario: Complete purchase workflow
    Given I have a new order with the following items:
      | product  | price   | quantity |
      | Laptop   | 1299.99 | 1        |
    When I place the order
    Then the order should be in "placed" status
    And an invoice should be created for the order
    And the invoice should be in "issued" status
    And a payment should be created for the invoice
    And the payment should be in "pending" status
    When I complete the payment
    Then the payment should be in "completed" status
    And the invoice should be in "paid" status
