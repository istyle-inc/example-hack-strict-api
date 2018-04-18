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
namespace App\Module;

use App\Action\IndexAction;
use App\Responder\IndexResponder;
use App\AppService\CacheableArtcleCollection;
use Ytake\HHContainer\Scope;
use Ytake\HHContainer\ServiceModule;
use Ytake\HHContainer\FactoryContainer;
use Nazg\HCache\CacheProvider;
use Psr\Log\LoggerInterface;

final class ActionServiceModule extends ServiceModule {
  <<__Override>>
  public function provide(FactoryContainer $container): void {
    $container->set(
      IndexAction::class,
      $container ==> new IndexAction(
        new IndexResponder(),
        $this->detectCacheableArticle($container->get(CacheableArtcleCollection::class))
      ),
      Scope::PROTOTYPE,
    );
    $container->set(
      CacheableArtcleCollection::class,
      $container ==> new CacheableArtcleCollection(
        $this->detectCacheProvider($container->get(CacheProvider::class)),
        $this->detectPsrLogger($container->get(LoggerInterface::class))
      ),
      Scope::PROTOTYPE
    );
  }

  protected function detectCacheProvider(mixed $instance): CacheProvider {
    invariant($instance instanceof CacheProvider, "implimantaion error");
    return $instance;
  }

  protected function detectPsrLogger(mixed $instance): LoggerInterface {
    invariant($instance instanceof LoggerInterface, "implimantaion error");
    return $instance;
  }

  protected function detectCacheableArticle(mixed $instance): CacheableArtcleCollection {
    invariant($instance instanceof CacheableArtcleCollection, "implimantaion error");
    return $instance;
  }
}
