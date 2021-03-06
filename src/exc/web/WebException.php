<?php
// Copyright 2016 Jannis Fink
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace Yarf\exc\web;

use Yarf\exc\BaseException;

/**
 * Class WebException
 *
 * Base class for all exceptions which should send a status code (different from 200, 301, 302 or 307) back to the client
 *
 * @package Yarf\exc\web
 */
abstract class WebException extends BaseException {

  /**
   * the pages status code
   */
  const STATUS_CODE = 0;

  private $details;

  /**
   * WebException constructor.
   *
   * @param string $message message for this error as defined in RFC 2616
   * @param string $details details for this error
   */
  public function __construct($message, $details = null) {
    parent::__construct($message);
    $this->details = $details;
  }

  /**
   * @return int the status code for this return type
   */
  public final function getStatusCode() {
    return static::STATUS_CODE;
  }

  /**
   * @return null|string details for this error, {@code null} if none given
   */
  public function getDetails() {
    return $this->details;
  }

}
