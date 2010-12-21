<?PHP

Zend_Loader::loadClass("Filesystem_Abstract", PATH_DATAPROVIDERS);

class Filesystem_Images extends Filesystem_Abstract {

	/**
	 * Создает уменьшенное изображение из орегинального. Уменьшенное изображение имеет тип jpeg
	 * В первом параметре передается путь к орегинальному изображению
	 * Во втором путь, куда сохранить уменьшенное
	 * В третьем и четвертом - ширина и высота уменьшенной копии
	 * Третим параметром передается набор дополнительных опций создаваемого изображения:
	 *  - image_type : mime-тип изображения (image/jpeg, image/jpg, image/gif, image/png), если не указан, производится попытка угадать по расширению
	 *  - image_quality : качество уменьшенной копии, если не установлено = 90
	 *
	 * Функция позвращает true в случае успеха и false в случае неудачи
	 *
	 * @param string $file_fp
	 * @param string $save_as
	 * @param int $width
	 * @param int $height
	 * @param array $extraParams
	 * @return boolean
	 */
	public function thumbnailCreate ($file_fp, $save_as, $width, $height, Array $extraParams = array()) {

		//Пытаемся угадать mime-тип если он не указан
		if (!isset($extraParams['image_type'])) {
			$file_info = pathinfo($file_fp, PATHINFO_EXTENSION);
			$extraParams['image_type'] = $this->_getMimeByExtension($file_info['extension']);
		}

		//Если не установлено качество, устанавливаем по-умолчанию 90
		if (!isset($extraParams['image_quality'])) {
			$extraParams['image_quality'] = 90;
		}

		if (is_readable($file_fp) && is_file($file_fp)) {
			switch ($extraParams['image_type']) {
				case 'image/jpeg':
				case 'image/pjpeg': // IE :-(
				case 'image/jpg':
					$im = imagecreatefromjpeg($file_fp);
					break;
				case 'image/gif':
					$im = imagecreatefromgif($file_fp);
					break;
				case 'image/png':
					$im = imagecreatefrompng($file_fp);
					break;
				default:
					return false;
			}

			if (!$im) {
				return false;
			}

			$w = imagesx($im);
			$h = imagesy($im);

			$kw = $width / $w;
			$kh = $height / $h;

			if ($kw > $kh) {
				$ks = $kh;
			} else {
				$ks = $kw;
			}

			$sw = floor($w * $ks);
			$sh = floor($h * $ks);

			$ns = imagecreatetruecolor($sw, $sh);
			imagecopyresampled($ns, $im, 0, 0, 0, 0, $sw, $sh, $w, $h);
			imagejpeg($ns, $save_as, $extraParams['image_quality']);
			imagedestroy($ns);
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Возвращет информацию об изображении, путь к которому указан первым параметром
	 * Возвращает массив, содержащий элементы:
	 * width - ширина
	 * height - высота
	 * mime - MIME-тип изображения
	 *
	 * @param string $filename
	 * @return array
	 */
	public function getImageInfo($filename) {
		$result = array();
		$size = getimagesize($filename);

		if ($size === false) {
			return false;
		}

		$result = array('width' => $size[0], 'height' => $size[1], 'mime' => $size['mime']);

		return $result;
	}

	/**
	 * Возвращает mime-тип файла по расширению файла
	 * Если не найдено, возвращает false
	 *
	 * @param string $ext
	 * @return string|boolean
	 */
	private function _getMimeByExtension($ext) {
		$ext = strtolower($ext);

		switch ($ext) {
			case "jpg":
			case "jpeg":
				return "image/jpeg";
				break;
			case "gif":
				return "image/gif";
				break;
			case "png":
				return "image/png";
				break;
			default:
				return false;
		}
	}

}