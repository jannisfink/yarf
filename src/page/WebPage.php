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

namespace Yarf\page;

use Yarf\exc\web\HttpMethodNotAllowed;
use Yarf\exc\web\WebException;

/**
 * Class WebPage
 *
 * Base class for all web pages.
 *
 * @package Yarf\page
 */
abstract class WebPage {

  /**
   * @const string content type of this website
   */
  const CONTENT_TYPE = null;

  /**
   * WebPage constructor. This constructor is final on purpose to disallow any constructor for
   * the subclasses.
   */
  public final function __construct() {
    // empty on purpose
  }

  /**
   * @return string the content type of this page
   */
  public function getContentType() {
    return static::CONTENT_TYPE;
  }

  /**
   * Method mapping to the HTTP GET method
   *
   * @throws WebException if anything goes wrong
   */
  public function get() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP POST method
   *
   * @throws WebException if anything goes wrong
   */
  public function post() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP PUT method
   *
   * @throws WebException if anything goes wrong
   */
  public function put() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP DELETE method
   *
   * @throws WebException if anything goes wrong
   */
  public function delete() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP PATCH method
   *
   * @throws WebException if anything goes wrong
   */
  public function patch() {
    throw new HttpMethodNotAllowed();
  }

}
