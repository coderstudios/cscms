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

namespace CoderStudios\CsCms\Traits;

trait Filled
{
    public function getFilledData(array $fillable = [], $request, $casts = [])
    {
        $data = [];
        foreach ($fillable as $item) {
            if ($request->has($item) && !empty($request->get($item))) {
                if (!is_array($request->get($item))) {
                    $data[$item] = trim($request->get($item));
                } else {
                    $data[$item] = $request->get($item);
                }
            }
            if ($request->has($item) && empty($request->get($item))) {
                $data[$item] = null;
            }
            if (!empty($casts)) {
                foreach ($casts as $attribute => $cast) {
                    if ('serialize' == $cast) {
                        if ($request->has($attribute) && !empty($request->get($attribute))) {
                            $data[$attribute] = serialize($request->get($attribute));
                        } else {
                            unset($data[$attribute]);
                        }
                    }
                    if ('int' == $cast) {
                        if ($request->has($attribute) && !empty($request->get($attribute))) {
                            if (isset($data[$attribute]) && !is_numeric($data[$attribute])) {
                                unset($data[$attribute]);
                            }
                        }
                    }
                    if ('float' == $cast) {
                        if ($request->has($attribute) && !empty($request->get($attribute))) {
                            if (isset($data[$attribute]) && !is_numeric($data[$attribute])) {
                                unset($data[$attribute]);
                            }
                        }
                    }
                    if ('boolean' == $cast || 'bool' == $cast) {
                        if (!$request->has($attribute)) {
                            $data[$attribute] = 0;
                        } else {
                            $data[$attribute] = $request->has($attribute) ?? 0;
                        }
                    }
                    if ('datetime' == $cast) {
                        if ($request->has($attribute) && !empty($request->get($attribute))) {
                            $data[$attribute] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->get($attribute))));
                        }
                    }
                }
            }
        }

        return $data;
    }
}
