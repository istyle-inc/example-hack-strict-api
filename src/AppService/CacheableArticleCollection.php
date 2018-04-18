<?hh // strict

namespace App\AppService;

use Nazg\HCache\Element;
use Nazg\HCache\CacheProvider;
use Psr\Log\LoggerInterface;
use App\Payload\SampleResourcePayload;

final class CacheableArtcleCollection {

  protected string $id = "cacheable.article" ;

  public function __construct(
    private CacheProvider $cacheProvider,
    private LoggerInterface $logger
  ) {}

  public function run(): array<mixed, mixed> {
    if($this->cacheProvider->contains($this->id)) {
      $this->logger->info('cache.hit', ['message' => 'cache']);
      $fetch = $this->cacheProvider->fetch($this->id);
      return /* UNSAFE_EXPR */ $fetch;
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
    $payload = new SampleResourcePayload($map);
    $v = $payload->payload();
    $this->cacheProvider->save($this->id, new Element($v));
    return $v;
  }
}
