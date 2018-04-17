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
namespace App\Payload;

use Ytake\HHhal\Serializer\JsonSerializer;
use Ytake\HHhal\{Link, LinkResource, Serializer, HalResource};

class SampleResourcePayload {

  protected Vector<HalResource> $vec = Vector{};

  public function __construct(
    protected ImmMap<mixed, mixed> $resource
  ) {}

  public function payload(): array<mixed, mixed> {
    $map = $this->resource
      ->filterWithKey(($k, $v) ==> $k != 'embedded')
      ->toMap();
    $hal = new HalResource($map);
    $hal->withLink(new Link(
      'self',
      new Vector([
        new LinkResource('/', shape('type' => 'application/json'))
      ]),
    ));
    $embedded = $this->resource->get('embedded');
    if(is_array($embedded)) {
      foreach($embedded as $row) {
        $embeddedResource = new HalResource();
        foreach($row as $key => $value) {
          if($key === 'url') {
            $embeddedResource->withLink(
              new Link('self', new Vector([new LinkResource($value)]))
            );
            continue;
          }
          $embeddedResource->addResource(strval($key), $value);
        }
        $this->vec->add($embeddedResource);
      }
    }
    $hal = $hal->withEmbedded('enviroments', $this->vec);
    $serialize = new Serializer(new JsonSerializer(), $hal);
    return $serialize->toArray();
  }
}
