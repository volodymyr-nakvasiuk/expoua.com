<?php

Zend_Loader::loadClass("Filesystem_Abstract", PATH_DATAPROVIDERS);

class Filesystem_Upgrade extends Filesystem_Abstract {

	/**
	 * Функция рекурсивного обхода каталога
	 *
	 * @param string|RecursiveDirectoryIterator $directory
	 * @return array
	 */
	public function getDirectoryTree($directory, $returnContent = false) {
		$skipDirectories = array('.svn');
		//static $basePath;

		if ($directory instanceof DirectoryIterator) {
			$dirIterator = $directory;
		} else {
			$basePath = $directory;
			$dirIterator = new RecursiveDirectoryIterator($directory);
		}
		$dirArray = array();

		for($dirIterator->rewind(); $dirIterator->valid(); $dirIterator->next()) {
			if (!$dirIterator->isDot() && !in_array(basename($dirIterator->getSubPathname()), $skipDirectories)) {

				if ($dirIterator->isDir()) {
					$dirArray = array_merge($dirArray, $this->getDirectoryTree($dirIterator->getChildren(), $returnContent));
				} else {
					//$name = str_replace($basePath, "", $dirIterator->getPath() . $dirIterator->getFilename());
					$filename = $dirIterator->getPath() . "/" . $dirIterator->getFilename();
					$fileEntry = array(
						'name' => $dirIterator->getFilename(),
						'path' => $dirIterator->getSubPath(),
						'size' => $dirIterator->getSize(),
						'md5' => md5_file($filename)
					);

					if ($returnContent) {
						$fileEntry['content'] = $this->getFileEncodedContent($filename);
					}

					$dirArray[] = $fileEntry;

				}
			}
		}

		return $dirArray;
	}

	/**
	 * Возвращает массив данных для указанного файла
	 * Если второй параметр = true, функция возвращает закодированное содержимое файла
	 *
	 * @param string $filename
	 * @param boolean $returnContent
	 * @return array
	 */
	public function getFileDataArray($filename, $returnContent = false) {
		$resultArray = array();

		if (is_readable($filename)) {
			$resultArray = array(
				'name' => basename($filename),
				'path' => null,
				'size' => filesize($filename),
				'md5' => md5_file($filename)
			);

			if ($returnContent) {
				$resultArray['content'] = $this->getFileEncodedContent($filename);
			}
		}

		return $resultArray;
	}

	/**
	 * Возвращает закодированное содержимое файла.
	 * Кодирование base64
	 *
	 * @param string $filename
	 * @return string
	 */
	public function getFileEncodedContent($filename) {
		$content = file_get_contents($filename);
		return base64_encode($content);
	}

	/**
	 * Сохраняет закодированное содержимое файла (предварительно раскодируя)
	 *
	 * @param string $filename
	 * @param string $content
	 * @return int
	 */
	public function putFileEncodedContent($filename, $content) {
		$content = base64_decode($content);

		return file_put_contents($filename, $content);
	}

}