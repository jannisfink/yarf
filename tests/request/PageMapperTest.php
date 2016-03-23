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

namespace Yarf\request;

use Yarf\exc\web\HttpInternalServerError;
use Yarf\exc\web\HttpNotFound;
use Yarf\page\WebPage;
use Yarf\wrapper\Server;

class SampleWebPage extends WebPage {
  // empty on purpose
}

class SampleWebPagePrivateConstructor extends WebPage {

  private function __construct() {
    // empty on purpose
  }

}

class PageMapperTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    Server::setDefault();
  }

  public function testGetPageEmptyMap() {
    // empty class map should always result in a 404
    $this->setExpectedException(HttpNotFound::class);
    Server::setDefault([Server::REQUEST_URI => '/some/nonexistent/uri']);
    $pageMapper = new PageMapper([]);
    $pageMapper->getPage();
  }

  public function testGetValidWebPageSimple() {
    $pageMap = [
      'test' => SampleWebPage::class
    ];
    Server::setDefault([Server::REQUEST_URI => '/test']);
    $pageMapper = new PageMapper($pageMap);
    $this->assertTrue($pageMapper->getPage() instanceof SampleWebPage);
  }

  public function testGetValidWebPageUriRootSlash() {
    $pageMap = [
      '' => SampleWebPage::class
    ];
    Server::setDefault([Server::REQUEST_URI => '/']);
    $pageMapper = new PageMapper($pageMap);
    $this->assertTrue($pageMapper->getPage() instanceof SampleWebPage);
  }

  public function testGetValidWebPageUriRootNoSlash() {
    $pageMap = [
      '' => SampleWebPage::class
    ];
    Server::setDefault([Server::REQUEST_URI => '']);
    $pageMapper = new PageMapper($pageMap);
    $this->assertTrue($pageMapper->getPage() instanceof SampleWebPage);
  }

  public function testGetWebPageFalseInstance() {
    $this->setExpectedException(HttpInternalServerError::class);
    $pageMap = [
      'test' => \Exception::class
    ];
    Server::setDefault([Server::REQUEST_URI => '/test']);
    $pageMapper = new PageMapper($pageMap);
    $pageMapper->getPage();
  }

  public function testGetWebPageInvalidClassName() {
    $this->setExpectedException(HttpInternalServerError::class);
    $pageMap = [
      'test' => 'I am not a class name'
    ];
    Server::setDefault([Server::REQUEST_URI => '/test']);
    $pageMapper = new PageMapper($pageMap);
    $pageMapper->getPage();
  }

  public function testGetValidWebPagePrivateConstructor() {
    $this->setExpectedException(HttpInternalServerError::class);
    $pageMap = [
      '' => SampleWebPagePrivateConstructor::class
    ];
    Server::setDefault([Server::REQUEST_URI => '/']);
    $pageMapper = new PageMapper($pageMap);
    $pageMapper->getPage();
  }

}
