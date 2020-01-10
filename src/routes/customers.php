<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

$app = AppFactory::create();
// Add error middleware
//$app->addErrorMiddleware(false, false, false );
//get all customre
// $app->options('/{routes:.+}', function ($request, $response, $args) {
//     return $response;
// });
// $app->add(function ($req, $res, $next) {
//     $response = $next($req, $res);
//     return $response
//             ->withHeader('Access-Control-Allow-Origin', '*')
//             ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//             ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
// });
$app->get('/api/customers',function(Request $request, Response $response) {
   $sql = "SELECT * FROM customers";
   try {
     //get the database
     $db = new db();
     //call the connect
     $db=$db->connect();
     $stmt= $db->query($sql);
     $customers= $stmt->fetchAll(PDO::FETCH_OBJ);
     $db=null;
     echo json_encode($customers);
   } catch (PDOException $e) {
     echo '{"error": {"text":'.$e->getMessage().'}}';
   }
   return $response;
});



//get single customre
$app->get('/api/customer/{id}',function(Request $request, Response $response) {

    $id= $request-> getAttribute('id');
    $sql = "SELECT * FROM customers WHERE id =$id";
    try {
     //get the database
     $db = new db();
     //call the connect
     $db=$db->connect();
     $stmt= $db->query($sql);
     $customer= $stmt->fetch(PDO::FETCH_OBJ);
     $db=null;
     echo json_encode($customer);
    } catch (PDOException $e) {
     echo '{"error": {"text":'.$e->getMessage().'}}';
      }
      return $response;
});
//add custopmer
$app->post('/api/customer/add1',function(Request $request, Response $response) {
  $postArr  = $request->getBody();
  $arr=json_decode($postArr, true);
  $first_name = $arr['first_name'];
  $last_name=$arr['last_name'];
  $phone=$arr['phone'];
  $email=$arr['email'];
  $address=$arr['address'];
  $city=$arr['city'];
  $state=$arr['state'];
  $sql = "INSERT INTO customers (first_name,last_name,phone, email, address,city,state) VALUES
  (:first_name,:last_name,:phone, :email, :address,:city,:state)";
  try {
    //get the database
    $db = new db();
    //call the connect
    $db=$db->connect();

    $stmt= $db->prepare($sql);
    $stmt->bindParam(':first_name',$first_name);
    $stmt->bindParam(':last_name',$last_name);
    $stmt->bindParam(':phone',$phone);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state',$state);

    $stmt->execute();
    echo'{"notice":{"text":"Customer added"}}';
  } catch (PDOException $e) {
    echo '{"error": {"text":'.$e->getMessage().'}}';
  }
  return $response;
});
//update customer
$app->put('/api/customer/update/{id}',function(Request $request, Response $response) {
  $id=$request->getAttribute('id');
  $postArr  = $request->getBody();
  $arr=json_decode($postArr, true);
  $first_name = $arr['first_name'];
  $last_name=$arr['last_name'];
  $phone=$arr['phone'];
  $email=$arr['email'];
  $address=$arr['address'];
  $city=$arr['city'];
  $state=$arr['state'];
  $sql = "UPDATE customers SET
          first_name=:first_name,
          last_name=:last_name,
          phone=:phone,
          email=:email,
          address=:address,
          city=:city,
          state=:state
  WHERE id=$id";
  try {
    //get the database
    $db = new db();
    //call the connect
    $db=$db->connect();

    $stmt= $db->prepare($sql);
    $stmt->bindParam(':first_name',$first_name);
    $stmt->bindParam(':last_name',$last_name);
    $stmt->bindParam(':phone',$phone);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state',$state);

    $stmt->execute();

    echo '{"notice":{"text": "customer update"}}';
  } catch (PDOException $e) {
    echo '{"error": {"text":'.$e->getMessage().'}}';
  }
  return $response;
});


//delete customre
$app->delete('/api/customer/delete/{id}',function(Request $request, Response $response) {

    $id= $request-> getAttribute('id');
    $sql = "DELETE FROM customers WHERE id =$id";
    try {
     //get the database
     $db = new db();
     //call the connect
     $db=$db->connect();
     $stmt= $db->prepare($sql);
     $stmt->execute();
     $db=null;
     echo '{"notice":{"text": "customer delete"}}';
    } catch (PDOException $e) {
     echo '{"error": {"text":'.$e->getMessage().'}}';
      }
      return $response;
});
