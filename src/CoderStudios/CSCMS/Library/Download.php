<?php
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @package    CSCMS
 * @version    1.0.0
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2017, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */
 
namespace CoderStudios\CSCMS\Library;

class Download {

	public function getCSV($filename, array $data = [])
	{
		$headers = [
			'Cache-Control' 			=> '',
			'Content-type' 				=> 'text/csv',
			'Content-Disposition' 		=> 'attachment; filename=' . $filename,
			'Expires'					=> '0',
			'Pragma'					=> 'public',
		];

		//array_unshift($data, array_keys[$data[0]]);

		$callback = function() use ($data) {
			$fh = fopen('php://output', 'w');
			foreach($data as $row) {
				fputcsv($fh, $row);
			}
			fclose($fh);
		};
		return response()->stream($callback, 200, $headers);
	}
}