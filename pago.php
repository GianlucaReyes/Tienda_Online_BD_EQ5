<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

// print_r($_SESSION);

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {

        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}else{
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="Css/estilos.css">
</head>
<body>
<header>
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="Index.php" class="navbar-brand">
        <strong>Tienda Online</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="#" class="nav-link active">Catalago</a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">Contacto</a>
          </li>
        </ul>
        <a href="carrito.php" class="btn btn-primary">
          Carrito <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart ?></span>
        </a>
      </div>
    </div>
  </div>
</header>

<main>
  <div class="container">

  <div class="row">
    <div class="col-6">
        <h4>Detalles de pago</h4>
        <div id="paypal-button-container"></div>
    </div>

    <div class="col-6">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if($lista_carrito == null){
                    echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                }else{
                    $total = 0;
                    foreach($lista_carrito as $producto){
                        $_id = $producto['id'];
                        $nombre = $producto['nombre'];
                        $precio = $producto['precio'];
                        $descuento = $producto['descuento'];
                        $cantidad = $producto['cantidad'];
                        $precio_desc = $precio - (($precio * $descuento) / 100);
                        $subtotal = $cantidad * $precio_desc;
                        $total += $subtotal;
                    ?>

                <tr>
                    <td><?php echo $nombre; ?></td>
                    <td>
                        <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal,2, '.', ','); ?></div>
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <td colspan="2">
                        <p class="h3 text-end" id="total"> <?php echo MONEDA . number_format($total, 2, '.', ','); ?>
                        </p>
                    </td>
                </tr>

            </tbody>
            <?php } ?>
        </table>
    </div>

    </div>
</div>
</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY?>"></script>

<script>
      paypal.Buttons({
        createOrder: (data, actions) => {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: <?php echo $total; ?> // Can also reference a variable or function
              }
            }]
          });
        },
        onApprove: (data, actions) => {
          let URL = 'clases/captura.php'
          return actions.order.capture().then(function(orderData) {
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const transaction = orderData.purchase_units[0].payments.captures[0];
            alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
            console.log(orderData)
            let url = 'clases/captura.php'
            return fetch(url, {
              method: 'post',
              headers: {
                'content-type': 'application/json'
              },
              body: JSON.stringify({
                orderData: orderData
              })
            }).then(function(response){
              window.location.href = "Completado.php?key=" + orderData['id'];
            })
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