<?
	/*
		Realizada por: Henoc Díaz (@roofdier)
		Última modificación: Diciembre'06 2011
		México, DF.
	*/
	
	class bd
	{
		var $user;
		var $password;
		var $host;
		var $status;
		var $db;
		var $cxn;
		var $error;
					
		function bd()
		{				
			$this->user  = "randz";
			$this->password = "contrasenna";
			$this->host = "localhost";
			$this->status = "Correct";
			$this->db = "Newsletter";
			$this->cxn = mysql_connect($this->host,$this->user,$this->password);
			mysql_select_db($this->db,$this->cxn);
		}
		
		function select($sql)
		{
			$query = mysql_query($sql,$this->cxn);
			if(mysql_errno()!=0)
			{
				$this->status = "Error";
				return mysql_error();
			}
		   else if(mysql_num_rows($query)>0)
				return $query;
			else 
				return "No results";
		}
		
		function insert($table,$values)
		{
			$sql = "INSERT INTO $table VALUES($values)";
			if(mysql_query($sql,$this->cxn))
			{
				$this->status = "Correct";
				return true;
			}
			else
			{
				$this->status = "Error Inserting";
				$this->error = mysql_error();
				return false;
			}
		}
		
		function update($sql)
		{
			if(mysql_query($sql))
				$ret = true;
			else
				$ret = "Error: ".mysql_error();
				
				return $ret;
		}
		
		function delete($table,$fill,$value,$sec)
		{
			if($sec>0)
				$sql = "DELETE FROM $table WHERE $fill='$value'";
			else 
				$sql = "DELETE FROM $table WHERE $fill=$value";
				
			if(mysql_query($sql,$this->cxn))
			{
				$this->status = "Correct";
				return true;
			}
			else
			{
				$this->status = "Error Inserting";
				$this->error = mysql_error();
				return false;
			}
		}
		
		// Verificar si existe un registro según parámetros
		function verify1($table,$fill,$keyword,$type)
		{
				$ex = false;			
				// Evaluar tipo de dato del campo que se desea buscar
				if($type == 0)
					$sql = "SELECT $fill FROM $table WHERE $fill=$keyword";
				elseif($type == 1)
					$sql = "SELECT $fill FROM $table WHERE $fill='$keyword'";
					
				
				$sq = mysql_query($sql,$this->cxn);
												
				if(mysql_num_rows($sq)>0)
				{
					$keyword2 = mysql_result($sq,0,0);
					if($keyword2 == $keyword)
						$ex = true;
					else 
						$ex = false;
				}
				else
					$ex = false;				
				
				return $ex;
		}
		
		function login($table,$usr1,$usr,$pass1,$pass)
		{
			$sql = "SELECT $usr1 FROM $table WHERE $usr1='$usr' AND $pass1='$pass'";
			
			if(mysql_num_rows(mysql_query($sql))>0)
				return  true;
			else
				return false;
		}
		
		function autoid($table,$fill,$lng,$sec)
		{
			$ex = false;			
			
			$keyword = $this->idsecure($sec,$lng);

			if($sec == 0)
				$sql = "SELECT $fill FROM $table WHERE $fill=$keyword";
			else
				$sql = "SELECT $fill FROM $table WHERE $fill='$keyword'";
			
			$sq = mysql_query($sql,$this->cxn);
			
			if(mysql_errno()>0)
			{
				$this->status = "Error".mysql_error();
				exit();
			}
							
			if(mysql_num_rows($sq)>0)
			{
				$keyword2 = mysql_result($sq,0,0);
				while($keyword == $keyword2)
				{			
					$keyword = $this->idsecure($sec,$lng);
					
					if($sec == 0)
						$sql = "SELECT $fill FROM $table WHERE $fill=$keyword";
					else
						$sql = "SELECT $fill FROM $table WHERE $fill='$keyword'";
					
					$sq = mysql_query($sql,$this->cxn);
					$keyword2 = mysql_result($sq,0,0);
					
					if(mysql_errno()>0)
					{
						$id = "MySQL Error: ".mysql_error();
						break;
					}
					elseif(mysql_num_rows($sq)==0)
						break;						
				}
			}
			else
				$id = $keyword;
			
			return $id;
		}
		
		function close()
		{	
			mysql_close($this->cxn);
		}
	
	
		function idsecure($type,$long)
		{	
			switch($type)
			{
				case 0:
					$id = $this->generador($long,false,false,true,false);
				break;
				case 1:
					$id = $this->generador($long,true,false,true,false);
				break;
				case 2:
					$id = $this->generador($long,true,true,true,false);
				break;
				case 3:
					$id = $this->generador($long,true,true,true,true);
				break;
				default:
					$id = "Error";
			}
			
			return $id;
		}
	
		function generador($longitud,$letras_min,$letras_may,$numeros,$simbolos)
		{
			$variacteres = $letras_min?'abdefghijklmnopqrstuvwxyz':'';
			$variacteres .= $letras_may?'ABDCEFGHIJKLMNOPQRSTUVWXYZ':'';
			$variacteres .= $numeros?'0123456789':'';
			$variacteres .= $simbolos?'-_-':'';
			
			$i = 0;
			$clv = '';

			while($i<$longitud)
				{
					$numrad = rand(0,strlen($variacteres)-1);
					$clv .= substr($variacteres,$numrad,1);
					$i++;
				}		
			return $clv;
		}
	
		function isold($ddb)
		{
			$tor = false;
			list($d1,$m1,$y1) = explode("-",$ddb); // Fecha de la máquina

			if(mktime(0,0,0,date("n"),date("j"),date("Y"))>mktime(0,0,0,$m1,$d1,$y1))
				$tor = true;
			
			return $tor;
		}
	}
?>