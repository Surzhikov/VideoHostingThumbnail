# Get Video thumbnail for video from video hosting

Using:

```
use Surzhikov\VideoHostingThumbnail\Thumbnail;

$thumbUrl = Thumbnail::forUrl('https://vimeo.com/76979871')->get();

$thumbUrl = Thumbnail::forUrl('https://www.youtube.com/watch?v=izZgZwXeRXU')->get();

```