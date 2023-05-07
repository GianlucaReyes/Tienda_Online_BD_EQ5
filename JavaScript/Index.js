createOrder: (data, actions) => {
    return actions.order.create({
       "purchase_units": [{
          "amount": {
            "currency_code": "USD",
            "value": "100",
            "breakdown": {
              "item_total": {  /* Required when including the items array */
                "currency_code": "USD",
                "value": "100"
              }
            }
          },
          "items": [
            {
              "name": "First Product Name", /* Shows within upper-right dropdown during payment approval */
              "description": "Optional descriptive text..", /* Item details will also be in the completed paypal.com transaction view */
              "unit_amount": {
                "currency_code": "USD",
                "value": "50"
              },
              "quantity": "2"
            },
          ]
        }]
    });
  };