<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <!-- Replace "test" with your own sandbox Business account app client ID -->
    <script src="https://www.paypal.com/sdk/js?client-id=Ab_qh4uWIjvwYJfiuSyWDPbY9KevULCEef1ymoBHEWmwPfHkf2EzhQ9iRa0eDBhIa4Znhagl0qNUD9Ja&currency=MXN"></script>
    <!-- Set up a container element for the button -->
    <div id="paypal-button-container"></div>
    <script>
      paypal.Buttons({
        createOrder: (data, actions) => {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: '77.44' // Can also reference a variable or function
              }
            }]
          });
        },
        onApprove: (data, actions) => {
          return actions.order.capture().then(function(orderData) {
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const transaction = orderData.purchase_units[0].payments.captures[0];
            alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
            window.location.href="Completado.html"
          });
        },
        onCancel: function(data){
            alert("Pago Cancelado")
            console.log(data)
          }
      }).render('#paypal-button-container');
    </script>
  </body>
</html>
