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

namespace Yarf\response;


use Yarf\exc\web\HttpForbidden;
use Yarf\exc\web\HttpNotFound;
use Yarf\page\HtmlPage;
use Yarf\request\Request;
use Yarf\wrapper\Server;

class PageWithNoPermission extends HtmlPage {
  public function checkPermission() {
    return false;
  }
}

class PageWithNoPermissionNotFound extends PageWithNoPermission {
  public function showForbiddenWithoutPermission() {
    return false;
  }
}

class PageWithRequestAsParameter extends HtmlPage {
  public $request;

  public function get(Request $request) {
    $this->request = $request;
    return new Response();
  }
}

class PageWithResponseAsParameter extends HtmlPage {
  public $response;

  public function get(Response $response) {
    $this->response = $response;
    return $response;
  }
}

class PageWithRequestResponseAndUriVariables extends HtmlPage {
  public $request;
  public $response;

  public $test;
  public $variable;
  public $notSet;


  public function get(Request $request, Response $response, $test, $notSet, $variable) {
    $this->request = $request;
    $this->response = $response;
    $this->test = $test;
    $this->variable = $variable;
    $this->notSet = $notSet;

    return $response;
  }
}

class PageRendererTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    Server::setDefault([]);
  }

  public function setUp() {
    Server::setDefault([Server::REQUEST_METHOD => "get"]);
  }

  public function testThrowForbiddenIfNoPermission() {
    $this->setExpectedException(HttpForbidden::class);

    $renderer = new PageRenderer(new PageWithNoPermission(), []);
    $renderer->evaluatePage();
  }

  public function testThrowNotFounfIfNoPermissionAndSet() {
    $this->setExpectedException(HttpNotFound::class);

    $renderer = new PageRenderer(new PageWithNoPermissionNotFound(), []);
    $renderer->evaluatePage();
  }

  public function testRequestGetPassedInAsParameter() {
    $page = new PageWithRequestAsParameter();
    $renderer = new PageRenderer($page, []);
    $renderer->evaluatePage();

    $this->assertNotNull($page->request);
    $this->assertInstanceOf(Request::class, $page->request);
  }

  public function testResponseGetPassedAsParameter() {
    $page = new PageWithResponseAsParameter();
    $renderer = new PageRenderer($page, []);
    $renderer->evaluatePage();

    $this->assertNotNull($page->response);
    $this->assertInstanceOf(Response::class, $page->response);
  }

  public function testSetAllKindOfVariables() {
    $uriParameters = ["test" => "test", "variable" => "variable"];
    $page = new PageWithRequestResponseAndUriVariables();

    $renderer = new PageRenderer($page, $uriParameters);
    $renderer->evaluatePage();

    $this->assertInstanceOf(Request::class, $page->request);
    $this->assertInstanceOf(Response::class, $page->response);
    $this->assertEquals("test", $page->test);
    $this->assertEquals("variable", $page->variable);
    $this->assertNull($page->notSet);
  }

}
