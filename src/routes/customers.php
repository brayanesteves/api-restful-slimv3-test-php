<?php

    /**
     * Get error
     */
    ini_set('display_errors', 1);
    $configuration = [
        'settings' => [
            'displayErrorDetails' => true,
        ],
    ];
    $c = new \Slim\Container($configuration);
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    $app = new \Slim\App($c);
    function RemoveSpecialChar($str) {
        $res = str_replace(array("#", "'", ";"), '', $str);
        return $res;
    }
    /**
     * POST
     */

    $app->post('/api/v1/customers', function(Request $request, Response $response) {
        $sql = "SELECT `A`.`name`, `A`.`last_name`, `A`.`address`, `B`.`description` AS `B_description`, `C`.`description` AS `C_description` FROM `customers` AS `A` LEFT JOIN `communes` AS `B` ON `B`.`id_com` = `A`.`id_com` LEFT JOIN `regions` AS `C` ON `C`.`id_reg` = `B`.`id_reg` WHERE `A`.`status` = 'A';";
        try {
            $db = new db();
            $db = $db->connectionDB();
            $result = $db->query($sql);

            if($result->rowCount() > 0) {
                $usrs = $result->fetchAll(PDO::FETCH_OBJ);
                echo json_encode($usrs);
            } else {
                echo json_encode("No exists customers");
            }
            
            $result = null;
            $db = null;
        } catch(PDOException $e) {
            echo '{"error": {"text"' . $e->getMessage() . '}';
        }
    });
    
    /**
     * GET
     * Get specific record
     */

    $app->post('/api/v1/customer/{Rfrnc}', function(Request $request, Response $response) {
        $Rfrnc = $request->getAttribute('Rfrnc');
        $Rfrnc = RemoveSpecialChar($Rfrnc);
        $sql = "SELECT `A`.`name`, `A`.`last_name`, `A`.`address`, `B`.`description` AS `B_description`, `C`.`description` AS `C_description` FROM `customers` AS `A` LEFT JOIN `communes` AS `B` ON `B`.`id_com` = `A`.`id_com` LEFT JOIN `regions` AS `C` ON `C`.`id_reg` = `B`.`id_reg` WHERE (`A`.`dni` = ? OR `A`.`email` = ':Rfrnc') AND `A`.`status` = 'A';";
        
        try {
            $db = new db();
            $db = $db->connectionDB();
            $result = $db->query($sql);
            if($commune->rowCount() != 0) {
                $usrs = $result->fetchAll(PDO::FETCH_OBJ);
                echo json_encode($usrs);
            } else {
                echo json_encode("No exists customer");
            }
            $result = null;
            $db = null;
        } catch(PDOException $e) {
            echo '{"error": {"text"' . $e->getMessage() . '}';
        }
    });
     /**
      * Record data
      */
    $app->post('/api/v1/customers/new', function(Request $request, Response $response) {

        $dni        = $request->getParam('dni');
        $id_reg        = $request->getParam('id_reg');
        $id_com   = $request->getParam('id_com');
        $email = $request->getParam('email');
        $name        = $request->getParam('name');
        $last_name         = $request->getParam('last_name');
        $address         = $request->getParam('address');
        $date_reg     = date('Y-m-d');
        $status       = $request->getParam('status');

        $dni        = RemoveSpecialChar($dni);
        $id_reg        = RemoveSpecialChar($id_reg);
        $id_com       = RemoveSpecialChar($id_com);
        
        
        try {
            $db     = new db();
            $db     = $db->connectionDB();

            $regions = "SELECT * FROM `regions` WHERE `id_reg` = '$id_reg';";
            $_regions = $db->query($regions);

            if($_regions->rowCount() != 0) {

                $commune  = "SELECT * FROM `communes` WHERE `id_com` = '$id_com';";
                $_commune = $db->query($commune);

                if($_commune->rowCount() != 0) {
                    $sql   = "INSERT INTO `customers` (`dni`, `id_reg`, `id_com`, `email`, `name`, `last_name`, `address`, `date_reg`, `status`) VALUES(:dni, :id_reg, :id_com, :email, :name, :last_name, :address, :date_reg, :status);"; 
                    $result = $db->prepare($sql);
            
                    $result->bindParam(':dni', $dni);
                    $result->bindParam(':id_reg', $id_reg);
                    $result->bindParam(':id_com', $id_com);
                    $result->bindParam(':email', $email);
                    $result->bindParam(':name', $name);
                    $result->bindParam(':last_name', $last_name);
                    $result->bindParam(':address', $address);
                    $result->bindParam(':date_reg', $date_reg);
                    $result->bindParam(':status', $status);
            
                    $result->execute();
            
                    echo json_encode("Customer save");
            
                    $result = null; 
                } else {
                    echo json_encode("Commune not exist");
                }


            } else {
                echo json_encode("Region not exist");
            }
            
            $db = null;
        } catch(PDOException $e) {
            echo '{"error": {"text"' . $e->getMessage() . '}';
        }
    });

    $app->post('/api/v1/customers/delete/{Rfrnc}', function(Request $request, Response $response){

        $Rfrnc = $request->getAttribute('Rfrnc');
        
        try {
            $db     = new db();
            $db     = $db->connectionDB();

            $customers = "SELECT * FROM `customers` WHERE (`dni` = '$Rfrnc' OR `email` = '$Rfrnc') AND (`status` = 'A' OR `status` = 'I');";
            $_customers = $db->query($customers);

            if($_customers->rowCount() != 0) {
                
                $sql   = "UPDATE `customers` SET `status` = 'trash' WHERE (`dni` = '$Rfrnc' OR `email` = '$Rfrnc');"; 
                $result = $db->prepare($sql);
            
                $result->execute();
            
                echo json_encode("Customer edit");
            
                $result = null; 

            } else {
                echo json_encode("Trash");
            }
            
            $db = null;
        } catch(PDOException $e) {
            echo '{"error": {"text"' . $e->getMessage() . '}';
        }
    }); 

?>