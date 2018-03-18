<?hh // strict

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2017-2018 Yuuki Takezawa
 */
namespace App\Action;

use App\Responder\IndexResponder;
use App\Payload\SampleResourcePayload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IndexAction implements MiddlewareInterface {

  public function __construct(private IndexResponder $responder) {}

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $resource = new SampleResourcePayload(new ImmMap([
      'id' => 1234,
      'name' => 'ytake',
      'title' => 'type-assert for api response',
      'embedded' => [
        [
          'name' => 'HHVM/Hack',
          'url' => 'https://docs.hhvm.com/'
        ],
        [
          'name' => 'zend-diactoros',
          'url' => 'https://zendframework.github.io/zend-diactoros/',
        ]
      ]
    ]));
    return $this->responder->response(
      $resource->payload()
    );
  }
}
