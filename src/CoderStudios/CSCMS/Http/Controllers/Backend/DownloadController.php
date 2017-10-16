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

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoderStudios\Library\Utils;

class DownloadController extends Controller
{

	public function __construct(Request $request, Utils $utils)
    {
        $this->utils = $utils;
        $this->request = $request;
    }

	public function index()
	{
        $type = $this->request->get('type');
        switch($type) {
            case 'backup':
                $id = $this->request->get('id');
                $the_backup = [];
                $backups = $this->utils->getBackUps();
                if(count($backups)) {
                    foreach($backups as $backup) {
                        if (in_array($id,$backup)) {
                            $the_backup = $backup;
                        }
                    }
                }
                if (!empty($the_backup)) {
                    return response()->download($the_backup['location'],$the_backup['name']);
                }
                return redirect()->route('backend.backups');
            break;
        }
	}

}