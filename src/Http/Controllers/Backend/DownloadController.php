<?php
/**
 * Part of the CsCms package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 *
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2022, Coder Studios Ltd
 *
 * @see       https://www.coderstudios.com
 */

namespace CoderStudios\CsCms\Http\Controllers\Backend;

use CoderStudios\CsCms\Http\Controllers\Controller;
use CoderStudios\CsCms\Library\Utils;
use Illuminate\Http\Request;

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

        switch ($type) {
            case 'backup':
                $id = $this->request->get('id');
                $the_backup = [];
                $backups = $this->utils->getBackUps();
                if (count($backups)) {
                    foreach ($backups as $backup) {
                        if (in_array($id, $backup)) {
                            $the_backup = $backup;
                        }
                    }
                }
                if (!empty($the_backup)) {
                    return response()->download($the_backup['location'], $the_backup['name']);
                }

                return redirect()->route('backend.backups');

            break;
        }
    }
}
