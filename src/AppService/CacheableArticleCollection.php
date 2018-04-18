<?hh // strict

namespace App\AppService;

use Nazg\HCache\Element;
use Nazg\HCache\CacheProvider;
use Psr\Log\LoggerInterface;

final class CacheableArtcleCollection {

  protected string $id = "cacheable.article" ;

  public function __construct(
    private CacheProvider $cacheProvider,
    private LoggerInterface $logger
  ) {}

  public function run(): ImmMap<mixed, mixed> {
    if($this->cacheProvider->contains($this->id)) {
      $this->logger->info('cache.hit', ['message' => 'cache']);
      $fetch = $this->cacheProvider->fetch($this->id);
      invariant($fetch instanceof ImmMap, "type error");
      return $fetch;
    }
    $map = new ImmMap([
      'id' => 1234,
      'name' => 'ytake',
      'title' => 'type-assert for api response',
      'embedded' => [
        [
          'name' => 'HHVM/Hack',
          'url' => 'https://docs.hhvm.com/'
        ],
      ]
    ]);
    $this->cacheProvider->save($this->id, new Element($map));
    return $map;
  }
}
