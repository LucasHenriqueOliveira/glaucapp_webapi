<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Mailgun\Mailgun;
use Aws\S3\S3Client;

class Utils {

    public function getPedidosDashboard($tipo) {
        if($tipo == 'Procuração') {
            return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` INNER JOIN `tipo_procuracao` AS t ON p.`tipo_procuracao` = t.`tipo_procuracao_id` WHERE p.`tipo` = ? AND p.`status` = 'Aguardando' ORDER BY p.data_hora ASC LIMIT 3", [$tipo]);
        }
        return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data, DATE_FORMAT(p.`agendamento`, '%d/%m/%Y %H:%i') as agendamento FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` WHERE p.`tipo` = ? AND p.`status` = 'Aguardando' ORDER BY p.data_hora ASC LIMIT 3", [$tipo]);
    }

    public function getPedidos($tipo) {
		switch ($tipo) {
			case 'Procuração':
				return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` INNER JOIN `tipo_procuracao` AS t ON p.`tipo_procuracao` = t.`tipo_procuracao_id` WHERE p.`tipo` = ? ORDER BY p.data_hora LIMIT 100", [$tipo]);
				break;
			case 'Testamento':
				return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data, DATE_FORMAT(p.`agendamento`, '%d/%m/%Y %H:%i') as agendamento FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` WHERE p.`tipo` = ? ORDER BY p.agendamento DESC LIMIT 100", [$tipo]);
				break;
			case 'Certidão':
				return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data, DATE_FORMAT(p.`agendamento`, '%d/%m/%Y %H:%i') as agendamento FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` WHERE p.`tipo` = ? ORDER BY p.data_hora LIMIT 100", [$tipo]);
				break;
		}
    }

    public function getMovimentacoes($id) {
        return DB::select("SELECT *, DATE_FORMAT(m.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `movimentacao` AS m LEFT JOIN `users` AS u ON m.`user_id` = u.`id` WHERE m.pedido_id = ? ORDER BY m.`sequencia` ASC", [$id]);
    }

	public function getPartes($id) {
		return DB::select("SELECT * FROM `pedido_parte` AS pp INNER JOIN `parte` AS p ON pp.`parte_id` = p.`parte_id` WHERE pp.pedido_id = ?", [$id]);
	}

    public function getPedido($id, $tipo = '') {
        if($tipo == 'Procuração') {
            return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` INNER JOIN `tipo_procuracao` AS t ON p.`tipo_procuracao` = t.`tipo_procuracao_id` WHERE p.`pedido_id` = ?", [$id])[0];
        }
        return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data, DATE_FORMAT(p.`agendamento`, '%d/%m/%Y %H:%i') as agendamento FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` WHERE p.`pedido_id` = ?", [$id])[0];
    }

    public function atualizarStatus($id, $status) {
        return DB::update('UPDATE `pedido` SET `status` = ? WHERE `pedido_id` = ?', [$status, $id]);
    }

    public function logStatus($id, $status, $status_novo, $date) {
        DB::insert('INSERT INTO `log_status` (`pedido_id`, `data_hora`, `status_antigo`, `status_novo`, `user_id`, `ip`, `proxy`) VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$id, $date, $status, $status_novo, $this->getUserId()->id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']]);
    }

    public function getStatus($id) {
        return DB::select("SELECT * FROM `pedido` WHERE `pedido_id` = ?", [$id])[0];
    }

    public function checkPermissão($tipo) {
        $result = DB::select("SELECT * FROM `permissao` WHERE `user_id` = ?", [$this->getUserId()->id])[0];
        return ($result->{$tipo}) ? true : false;
    }

    public function getUserId() {
        return JWTAuth::parseToken()->authenticate();
    }

	public function getUser($id) {
		return DB::select("SELECT * FROM `users` WHERE `id` = ?", [$id])[0];
	}

    public function addPedido($tipo, $ato, $livro, $folha, $outorgante, $outorgado, $date, $user_id, $status) {
		DB::insert('INSERT INTO `pedido` (`tipo`, `ato`, `livro`, `folha`, `outorgante`, `outorgado`, `data_hora`, `user_id`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
			[$tipo, $ato, $livro, $folha, $outorgante, $outorgado, $date, $user_id, $status]);

		$pedido_id = DB::getPdo()->lastInsertId();
		$descricao = 'Solicitação de ' . $tipo;

		return DB::insert('INSERT INTO `movimentacao` (`pedido_id`, `user_id`, `data_hora`, `sequencia`, `descricao`) VALUES (?, ?, ?, ?, ?)',
			[$pedido_id, $user_id, $date, 1, $descricao]);
	}

	public function getFirma($nome, $cpf) {
		$search = "";
		if($cpf) {
			$search .= " AND cpf = '$cpf' ";
		} else if($nome) {
			$nome = strtoupper($nome);
			$search .= " AND nome = '$nome' ";
		}
		$result = DB::select("SELECT * FROM `firma` WHERE `data_hora` BETWEEN ? AND NOW()" . $search . "LIMIT 1", ['2017-01-01']);

		if(count($result)) {
			return [
				'message' => 'O usuário possui firma neste cartório.',
				'hasFirma' => true
				];
		} else {
			return [
				'message' => 'O usuário não possui firma neste cartório.',
				'hasFirma' => false
			];
		}
	}

	public function historico($id) {
		return DB::select("SELECT tipo, status, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y') as data, DATE_FORMAT(p.`data_hora`, '%H:%i') as hora FROM `pedido` AS p WHERE p.`user_id` = ? ORDER BY p.`data_hora` DESC LIMIT 5", [$id]);
	}

	public function checkCalendarioRestricao($data, $hora) {
		return DB::select("SELECT * FROM `calendario_restricoes` WHERE `data` = ? AND `hora` = ?", [$data, $hora])[0];
	}

	public function addCalendarioRestricao($data, $hora) {
		return DB::insert('INSERT INTO `calendario_restricoes` (`data`, `hora`) VALUES (?, ?)',
			[$data, $hora]);
	}

	public function formatDateBr($date) {
		$newDate = explode( "-" , $date);
		return $newDate[2]."/".$newDate[1]."/".$newDate[0];
	}

	public function sendEmail($email, $assunto, $texto) {
		$mg = Mailgun::create(getenv("MAILGUN_KEY"));

		$mg->messages()->send(getenv("MAILGUN_DOMAIN"), [
			'from' => "CartorioApp <postmaster@cartorioapp.com>",
			'to'      => $email,
			'subject' => $assunto,
			'html'    => $texto
		]);
	}

	public function uploadBase64($content, $name, $pedido_id, $parte_id = '') {
		if($content){
			$content = explode(',', $content);
			$content = str_replace(' ', '+', $content);
			$data = base64_decode($content[1]);
			$extension = $this->getImageMimeType($data);

			if($extension){
				$cartorio = getenv("cartorio");
				if($parte_id) {
					$path = $cartorio . '/' . $pedido_id . '/' . $parte_id . '/' . $name . '.' . $extension;
				} else {
					$path = $cartorio . '/' . $pedido_id . '/' . $name . '.' . $extension;
				}

				return $this->uploadToS3($data, ['path'=>$path,'extension'=>$extension]);
			}
		}
		return false;
	}

	public static function uploadToS3($data, $params=[]) {
		$temp = tempnam('/tmp','image');
		$success = file_put_contents($temp, $data);
		rename($temp, $temp.'.'.$params['extension']);
		$file = $temp.'.'.$params['extension'];
		$fileInfo = pathinfo($file);
		$fullPath = trim($fileInfo['dirname'].'/'.$fileInfo['basename']);
		$fileName = trim($fileInfo['basename']);
		$body = fopen($fullPath, 'r');
		$path = ( $params['path'] ) ? $params['path'] : $fileName;

		$options = [
			'region' => getenv("AWS_REGION"),
			'version' => 'latest'
		];

		$s3 = new S3Client($options);

		try {
			$s3->putObject(array(
				'Bucket' => getenv("AWS_BUCKET"),
				'Key' => $path,
				'Body' => $body,
				'ACL' => 'public-read'
			));
			unlink($temp.'.'.$params['extension']);
		} catch (Aws\S3\Exception\S3Exception $e) {
			return false;
		}

		return $params['path'];
	}

	public function getUrl($path) {
		$options = [
			'region' => getenv("AWS_REGION"),
			'version' => 'latest'
		];

		$s3 = new S3Client($options);

		$signedUrl = $s3->getCommand('GetObject',[
			'Bucket' => getenv("AWS_BUCKET"),
			'Key'    => $path
		]);
		$request = $s3->createPresignedRequest($signedUrl, '+30 minutes');
		$presignedUrl = (string) $request->getUri();

		if($presignedUrl) {
			return [
				'url' => $presignedUrl
			];
		} else {
			return [
				'error' => true,
				'message' => 'Documento não encontrado!'
			];
		}
	}

	public function getBytesFromHexString($hexdata){
		for($count = 0; $count < strlen($hexdata); $count+=2)
			$bytes[] = chr(hexdec(substr($hexdata, $count, 2)));
		return implode($bytes);
	}

	public function getImageMimeType($imagedata){
		$imagemimetypes = array(
			"jpeg" => "FFD8",
			"png" => "89504E470D0A1A0A",
			"gif" => "474946",
			"bmp" => "424D",
			"tiff" => "4949",
			"tiff" => "4D4D"
		);
		foreach ($imagemimetypes as $mime => $hexbytes){
			$bytes = $this->getBytesFromHexString($hexbytes);
			if (substr($imagedata, 0, strlen($bytes)) == $bytes)
				return $mime;
		}

		return NULL;
	}

	public static function script() {
		ini_set('max_execution_time', 300);
		$date = date("Y-m-d");
		$hour = date("H");

		$data = file_get_contents("http://cartorioapp.com/".getenv("script")."/ResultadoDadosClientes ".$date."_".$hour.";05.csv");
		$rows = explode("\n",$data);
		foreach($rows as $row) {
			$string = str_getcsv($row)[0];
			$pos = strpos($string, ';');

			if($pos) {
				$data = explode(";", $string);

				if($data[0] && trim($data[1])) {
					DB::insert('INSERT INTO `firma` (`cpf`, `nome`, `data_hora`) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `data_hora`= "'.date('Y-m-d H:i:s').'"', [trim($data[1]), utf8_encode($data[0]), date('Y-m-d H:i:s')]);
				}
			}
		}

		return [
			'message' => 'Firmas cadastradas.'
		];
	}

	public function addLogDocumento($documento, $pedido_id, $date) {
		return DB::insert('INSERT INTO `log_documento` (`user_id`, `pedido_id`, `documento`, `date`, `ip`, `proxy`) VALUES (?, ?, ?, ?, ?, ?)',
			[$this->getUserId()->id, $pedido_id, $documento, $date, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']]);
	}
}