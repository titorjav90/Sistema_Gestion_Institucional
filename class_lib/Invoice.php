<?php
class Invoice{
	private $host  = 'localhost';
    private $user  = 'root';
    private $password   = "usbw";
    private $database  = "php_factura";   
	private $invoiceUserTable = 'factura_usuarios';	
    private $invoiceOrderTable = 'factura_orden';
	private $invoiceOrderItemTable = 'factura_orden_producto';
    private $invoiceClientTable = 'clientes';
    private $invoiceReparacTable = 'reparaciones';
	private $dbConnect = false;
    public function __construct(){
        if(!$this->dbConnect){ 
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
			mysqli_set_charset($conn, 'utf8');
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }

    
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$data= array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[]=$row;            
		}
		return $data;
	}
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function loginUsers($email, $password){
		$sqlQuery = "
			SELECT id, email, first_name, last_name, address, mobile, empresa, slogan, logourl, Locate, CP, Facebook
			FROM ".$this->invoiceUserTable." 
			WHERE email='".$email."' AND password='".$password."'";
        return  $this->getData($sqlQuery);
	}	
	public function checkLoggedIn(){
		if(!$_SESSION['userid']) {
			header("Location:index.php");
		}
	}		
	//<!----------------------  Save  ------------------------------>
	public function saveInvoice($POST) {		
		$sqlInsert = "
			INSERT INTO ".$this->invoiceOrderTable."(user_id, order_receiver_name, order_receiver_address, responsable, CUIL, telefono, order_total_before_tax, order_total_tax, order_tax_per, order_total_after_tax, order_amount_paid, order_total_amount_due, note) 
			VALUES ('".$POST['userId']."', '".$POST['companyName']."', '".$POST['address']."', '".$POST['responsable']."','".$POST['CUIL']."','".$POST['telefono']."', '".$POST['subTotal']."', '".$POST['taxAmount']."', '".$POST['taxRate']."', '".$POST['totalAftertax']."', '".$POST['amountPaid']."', '".$POST['amountDue']."', '".$POST['notes']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
		$lastInsertId = mysqli_insert_id($this->dbConnect);
		for ($i = 0; $i < count($POST['productCode']); $i++) {
			$sqlInsertItem = "
			INSERT INTO ".$this->invoiceOrderItemTable."(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount) 
			VALUES ('".$lastInsertId."', '".$POST['productCode'][$i]."', '".$POST['productName'][$i]."', '".$POST['quantity'][$i]."', '".$POST['price'][$i]."', '".$POST['total'][$i]."')";			
			mysqli_query($this->dbConnect, $sqlInsertItem);
		}       	
	}	
	//<!----------------------  Update  ------------------------------>
	public function updateInvoice($POST) {
		if($POST['invoiceId']) {	
			$sqlInsert = "
				UPDATE ".$this->invoiceOrderTable." 
				SET order_receiver_name = '".$POST['companyName']."', order_receiver_address= '".$POST['address']."', order_total_before_tax = '".$POST['subTotal']."', order_total_tax = '".$POST['taxAmount']."', order_tax_per = '".$POST['taxRate']."', order_total_after_tax = '".$POST['totalAftertax']."', order_amount_paid = '".$POST['amountPaid']."', order_total_amount_due = '".$POST['amountDue']."', note = '".$POST['notes']."' 
				WHERE user_id = '".$POST['userId']."' AND order_id = '".$POST['invoiceId']."'";		
			mysqli_query($this->dbConnect, $sqlInsert);	
		}		
		$this->deleteInvoiceItems($POST['invoiceId']);
		for ($i = 0; $i < count($POST['productCode']); $i++) {			
			$sqlInsertItem = "
				INSERT INTO ".$this->invoiceOrderItemTable."(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount) 
				VALUES ('".$POST['invoiceId']."', '".$POST['productCode'][$i]."', '".$POST['productName'][$i]."', '".$POST['quantity'][$i]."', '".$POST['price'][$i]."', '".$POST['total'][$i]."')";			
			mysqli_query($this->dbConnect, $sqlInsertItem);			
		}       	
	}	

	//<!----------------------  Save Clientes ------------------------------>
	public function addCltInvoice($POST) {	
	$CUIL = ' ';
	if (isset($_POST['CUIL'])) {		$CUIL = $_POST['CUIL'];			}	
		$sqlInsert = "
			INSERT INTO ".$this->invoiceClientTable."(nombre_clt, telefono_clt, responsable_clt, CUIL_clt, direccion_clt) 
			VALUES ('".$POST['nombre']."', '".$POST['telefono']."', '".$POST['responsable']."','".$CUIL."','".$POST['direccion']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
	}	


	//<!----------------------   ------------------------------>


		public function getInvoiceClt($invoiceId){
		$sqlQuery = "
			SELECT * FROM ".$this->invoiceClientTable." 
			WHERE id_clt = '$invoiceId'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$row = mysqli_fetch_assoc($result);
		return $row;
	}	

	public function UpdateCltInvoice($POST) {
		if($POST['id']) {	
			$CUIL = ' ';
	if (isset($POST['CUIL'])) {		$CUIL = $POST['CUIL'];			}
			$sqlInsert = "
				UPDATE ".$this->invoiceClientTable." 
				SET nombre_clt = '".$POST['nombre']."', telefono_clt = '".$POST['telefono']."', responsable_clt = '".$POST['responsable']."', CUIL_clt = '".$CUIL."', direccion_clt = '".$POST['direccion']."' 
				WHERE id_clt = '".$_POST['id']."'";		
			mysqli_query($this->dbConnect, $sqlInsert);	
		}		
    	
	}	





	

	//<!----------------------   ------------------------------>



	//<!----------------------  Save Clientes ------------------------------>
	public function saveRepar($POST) {	
	$CUIL = ' ';
	if (isset($_POST['CUIL'])) {		$CUIL = $_POST['CUIL'];			}
	$fecha = ' ';	
		$sqlInsert = "
			INSERT INTO ".$this->invoiceReparacTable."(nombre_clt, telefono_clt, responsable_clt, CUIL_clt, ingreso, marca, modelo, Nserie, tipo, Accesorios, Scliente, reparacion, Comentario, estado, terminado, total, estregado) 
			VALUES ('".$_POST['nombre']."', '".$_POST['telefono']."', '".$_POST['responsable']."','".$CUIL."','".$fecha."','".$_POST['marca']."','".$_POST['modelo']."','".$_POST['Nserie']."','".$POST['tipo']."','".$POST['Accesorio']."','".$POST['Scliente']."','".$_POST['reparacion']."','".$_POST['nota']."','".$_POST['estado']."','".$_POST['terminado']."','".$_POST['total']."','".$_POST['entregado']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
	}	
#    ,'".$POST['direccion']."'

	//<!----------------------   ------------------------------>




	public function search($POST){
		$busqueda = $POST['search'];
		if ($POST['for']== 'cliente') {
			# code...
		$sqlQuery = "SELECT * FROM ".$this->invoiceClientTable." 
			WHERE nombre_clt LIKE  '%$busqueda%' OR CUIL_clt LIKE '%$busqueda%' OR telefono_clt LIKE  '%$busqueda%'  LIMIT 100";
		return  $this->getData($sqlQuery);	
			# code...
		}
	}


	public function getClientList(){
		$sqlQuery = "
			SELECT * FROM ".$this->invoiceClientTable." 
			";
		return  $this->getData($sqlQuery);
	}	


	//<!----------------------   ------------------------------>
	public function getInvoiceList(){
		$sqlQuery = "
			SELECT * FROM ".$this->invoiceOrderTable." 
			WHERE user_id = '".$_SESSION['userid']."'";
		return  $this->getData($sqlQuery);
	}	
	public function getInvoice($invoiceId){
		$sqlQuery = "
			SELECT * FROM ".$this->invoiceOrderTable." 
			WHERE user_id = '".$_SESSION['userid']."' AND order_id = '$invoiceId'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$row = mysqli_fetch_assoc($result);
		return $row;
	}	
	public function getInvoiceItems($invoiceId){
		$sqlQuery = "
			SELECT * FROM ".$this->invoiceOrderItemTable." 
			WHERE order_id = '$invoiceId'";
		return  $this->getData($sqlQuery);	
	}
	public function deleteInvoiceItems($invoiceId){
		$sqlQuery = "
			DELETE FROM ".$this->invoiceOrderItemTable." 
			WHERE order_id = '".$invoiceId."'";
		mysqli_query($this->dbConnect, $sqlQuery);				
	}
	public function deleteInvoice($invoiceId){
		$sqlQuery = "
			DELETE FROM ".$this->invoiceOrderTable." 
			WHERE order_id = '".$invoiceId."'";
		mysqli_query($this->dbConnect, $sqlQuery);	
		$this->deleteInvoiceItems($invoiceId);	
		return 1;
	}

	public function deleteClt($invoiceId){
		$sqlQuery = "
			DELETE FROM ".$this->invoiceClientTable." 
			WHERE id_clt = '".$invoiceId."'";
		mysqli_query($this->dbConnect, $sqlQuery);	
		$this->deleteInvoiceItems($invoiceId);	
		return 1;
	}

}

date_default_timezone_set('America/Buenos_Aires'); 


?>
